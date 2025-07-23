<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webhook extends CI_Controller {

    public $Order_model;
    public $load;
    public $email;

    public function __construct() {
        parent::__construct();
        $this->load->model('Order_model');
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
            http_response_code(400);
            echo json_encode(['error' => 'Campos obrigatórios ausentes: order_id, status']);
            return;
        }

        $order_id = $data['order_id'];
        $new_status = $data['status'];
        $notes = $data['notes'] ?? 'Atualização via webhook';


        $valid_statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($new_status, $valid_statuses)) {
            http_response_code(400);
            echo json_encode(['error' => 'Status inválido. Status válidos: ' . implode(', ', $valid_statuses)]);
            return;
        }


        $order = $this->Order_model->get_order($order_id);
        if (!$order) {
            http_response_code(404);
            echo json_encode(['error' => 'Pedido não encontrado']);
            return;
        }

        try {
            // Handle cancelled orders - delete them
            if ($new_status === 'cancelled') {
                $result = $this->Order_model->delete_order($order_id);
                if ($result['success']) {
                    // Send cancellation email
                    $this->send_cancellation_email($order);
                    
                    log_message('info', "Order {$order_id} cancelled and deleted via webhook");
                    echo json_encode(['success' => true, 'message' => 'Pedido cancelado e excluído']);
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => $result['message']]);
                }
            } else {
                // Update order status
                $result = $this->Order_model->update_status($order_id, $new_status, 'webhook', $notes);
                
                if ($result['success']) {
                    // Send status update email
                    $this->send_status_update_email($order, $new_status);
                    
                    log_message('info', "Order {$order_id} status updated to {$new_status} via webhook");
                    echo json_encode(['success' => true, 'message' => 'Status do pedido atualizado']);
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => $result['message']]);
                }
            }
        } catch (Exception $e) {
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
            
            if (!$this->email->send()) {
                // Falha ao enviar email de atualização de status
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
            $message = $this->load->view('emails/order_cancelled', [
                'order' => $order
            ], true);

            $this->email->clear();
            $this->email->from('noreply@montink.com', 'Montink');
            $this->email->to($order->customer_email);
            $this->email->subject('Pedido Cancelado - Pedido #' . $order->id);
            $this->email->message($message);
            
            if (!$this->email->send()) {
                // Falha ao enviar email de cancelamento
            }
        } catch (Exception $e) {
            log_message('error', 'Erro de email: ' . $e->getMessage());
        }
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