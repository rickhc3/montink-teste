<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webhook extends CI_Controller {

    public $Order_model;
    public $Webhook_logs_model;
    public $load;
    public $email;
    public $input;

    public function __construct() {
        parent::__construct();
        $this->load->model('Order_model');
        $this->load->model('Webhook_logs_model');
        $this->load->library('email');
    }

    /**
     * Handle order status webhook
     * Expected payload: {"order_id": 123, "status": "confirmed"}
     */
    public function order_status() {
        // Get raw POST data
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        // Log webhook request for debugging
        log_message('info', 'Webhook received: ' . $input);

        // Validate required fields
        if (!isset($data['order_id']) || !isset($data['status'])) {
            // Log failed webhook - missing required fields
            $this->Webhook_logs_model->log_webhook(
                $data['order_id'] ?? null,
                null,
                $data['status'] ?? null,
                $input,
                false,
                'Campos obrigatórios ausentes: order_id, status'
            );
            
            http_response_code(400);
            echo json_encode(['error' => 'Campos obrigatórios ausentes: order_id, status']);
            return;
        }

        $order_id = $data['order_id'];
        $new_status = $data['status'];
        $notes = $data['notes'] ?? 'Atualização via webhook';


        $valid_statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($new_status, $valid_statuses)) {
            // Log failed webhook - invalid status
            $this->Webhook_logs_model->log_webhook(
                $order_id,
                null,
                $new_status,
                $input,
                false,
                'Status inválido. Status válidos: ' . implode(', ', $valid_statuses)
            );
            
            http_response_code(400);
            echo json_encode(['error' => 'Status inválido. Status válidos: ' . implode(', ', $valid_statuses)]);
            return;
        }


        $order = $this->Order_model->get_order($order_id);
        if (!$order) {
            // Log failed webhook - order not found
            $this->Webhook_logs_model->log_webhook(
                $order_id,
                null,
                $new_status,
                $input,
                false,
                'Pedido não encontrado'
            );
            
            http_response_code(404);
            echo json_encode(['error' => 'Pedido não encontrado']);
            return;
        }

        $old_status = $order->status;
        
        try {
            // Handle cancelled orders - delete them
            if ($new_status === 'cancelled') {
                $result = $this->Order_model->delete_order($order_id);
                if ($result['success']) {
                    // Log successful webhook - order cancelled
                    $this->Webhook_logs_model->log_webhook(
                        $order_id,
                        $old_status,
                        $new_status,
                        $input,
                        true,
                        null
                    );
                    
                    // Send cancellation email
                    $this->send_cancellation_email($order);
                    
                    log_message('info', "Order {$order_id} cancelled and deleted via webhook");
                    echo json_encode(['success' => true, 'message' => 'Pedido cancelado e excluído']);
                } else {
                    // Log failed webhook - deletion error
                    $this->Webhook_logs_model->log_webhook(
                        $order_id,
                        $old_status,
                        $new_status,
                        $input,
                        false,
                        $result['message']
                    );
                    
                    http_response_code(500);
                    echo json_encode(['error' => $result['message']]);
                }
            } else {
                // Update order status
                $result = $this->Order_model->update_status($order_id, $new_status, 'webhook', $notes);
                
                if ($result['success']) {
                    // Log successful webhook - status updated
                    $this->Webhook_logs_model->log_webhook(
                        $order_id,
                        $old_status,
                        $new_status,
                        $input,
                        true,
                        null
                    );
                    
                    // Send status update email
                    $this->send_status_update_email($order, $new_status);
                    
                    log_message('info', "Order {$order_id} status updated to {$new_status} via webhook");
                    echo json_encode(['success' => true, 'message' => 'Status do pedido atualizado']);
                } else {
                    // Log failed webhook - update error
                    $this->Webhook_logs_model->log_webhook(
                        $order_id,
                        $old_status,
                        $new_status,
                        $input,
                        false,
                        $result['message']
                    );
                    
                    http_response_code(500);
                    echo json_encode(['error' => $result['message']]);
                }
            }
        } catch (Exception $e) {
            // Log failed webhook - exception
            $this->Webhook_logs_model->log_webhook(
                $order_id,
                $old_status,
                $new_status,
                $input,
                false,
                'Erro interno: ' . $e->getMessage()
            );
            
            log_message('error', 'Webhook error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Erro interno do servidor']);
        }
    }

    /**
     * Send order status update email
     */
    private function send_status_update_email($order, $new_status) {
        try {
            // Configurar SMTP para Mailpit
            $config = [
                'protocol' => 'smtp',
                'smtp_host' => 'ci3_mailpit',
                'smtp_port' => 1025,
                'smtp_user' => '',
                'smtp_pass' => '',
                'smtp_crypto' => '',
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'newline' => "\r\n"
            ];
            
            $this->email->initialize($config);
            
            $status_messages = [
                'pending' => 'Seu pedido está pendente',
                'confirmed' => 'Seu pedido foi confirmado!',
                'processing' => 'Seu pedido está sendo processado',
                'shipped' => 'Seu pedido foi enviado!',
                'delivered' => 'Seu pedido foi entregue!'
            ];

            $subject = $status_messages[$new_status] ?? 'Atualização do seu pedido';
            
            $message = $this->load->view('emails/order_status_update', [
                'order' => $order,
                'new_status' => $new_status,
                'status_message' => $status_messages[$new_status] ?? 'Status atualizado'
            ], true);

            $this->email->clear();
            $this->email->from('noreply@montink.com', 'Montink');
            $this->email->to($order->customer_email);
            $this->email->subject($subject . ' - Pedido #' . $order->id);
            $this->email->message($message);
            
            $result = $this->email->send();
            
            if ($result) {
                log_message('info', 'E-mail de atualização de status enviado para: ' . $order->customer_email);
            } else {
                log_message('error', 'Falha ao enviar e-mail de atualização de status para: ' . $order->customer_email);
            }
        } catch (Exception $e) {
            log_message('error', 'Erro de email: ' . $e->getMessage());
        }
    }

    /**
     * Send order cancellation email
     */
    private function send_cancellation_email($order) {
        try {
            // Configurar SMTP para Mailpit
            $config = [
                'protocol' => 'smtp',
                'smtp_host' => 'ci3_mailpit',
                'smtp_port' => 1025,
                'smtp_user' => '',
                'smtp_pass' => '',
                'smtp_crypto' => '',
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'newline' => "\r\n"
            ];
            
            $this->email->initialize($config);
            
            $message = $this->load->view('emails/order_cancelled', [
                'order' => $order
            ], true);

            $this->email->clear();
            $this->email->from('noreply@montink.com', 'Montink');
            $this->email->to($order->customer_email);
            $this->email->subject('Pedido Cancelado - Pedido #' . $order->id);
            $this->email->message($message);
            
            $result = $this->email->send();
            
            if ($result) {
                log_message('info', 'E-mail de cancelamento enviado para: ' . $order->customer_email);
            } else {
                log_message('error', 'Falha ao enviar e-mail de cancelamento para: ' . $order->customer_email);
            }
        } catch (Exception $e) {
            log_message('error', 'Erro de email: ' . $e->getMessage());
        }
    }

    /**
     * Get webhook logs
     * GET /webhook/logs?order_id=123&limit=50&offset=0&failed_only=1
     */
    public function logs() {
        $order_id = $this->input->get('order_id');
        $limit = (int)($this->input->get('limit') ?? 50);
        $offset = (int)($this->input->get('offset') ?? 0);
        $failed_only = $this->input->get('failed_only') === '1';
        
        if ($order_id) {
            // Get logs for specific order
            $logs = $this->Webhook_logs_model->get_logs_by_order($order_id);
            $total = count($logs);
        } elseif ($failed_only) {
            // Get only failed logs
            $logs = $this->Webhook_logs_model->get_failed_logs($limit, $offset);
            $total = $this->Webhook_logs_model->count_failed_logs();
        } else {
            // Get all logs
            $logs = $this->Webhook_logs_model->get_all_logs($limit, $offset);
            $total = $this->Webhook_logs_model->count_logs();
        }
        
        echo json_encode([
            'success' => true,
            'data' => $logs,
            'pagination' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'has_more' => ($offset + $limit) < $total
            ]
        ]);
    }

    /**
     * Health check endpoint
     */
    public function health() {
        echo json_encode([
            'status' => 'ok',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0.0'
        ]);
    }
}