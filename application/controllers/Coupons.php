<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coupons extends CI_Controller {

    public $session;
    public $form_validation;
    public $Coupon_model;
    public $input;
    public $load;
    public $output;

    public function __construct() {
        parent::__construct();
        $this->load->model('Coupon_model');
        $this->load->helper(['url', 'form']);
        $this->load->library(['form_validation', 'session']);
    }

    /**
     * List all coupons
     */
    public function index() {
        $page = $this->input->get('page') ?? 1;
        $per_page = 10;
        $offset = ($page - 1) * $per_page;

        $data['coupons'] = $this->Coupon_model->get_all_coupons($per_page, $offset);
        $data['total_coupons'] = $this->Coupon_model->get_total_coupons();
        $data['current_page'] = $page;
        $data['total_pages'] = ceil($data['total_coupons'] / $per_page);

        $this->load->view('layouts/base', [
            'title' => 'Gerenciar Cupons',
            'content' => $this->load->view('coupons/content', $data, true)
        ]);
    }

    /**
     * Create new coupon form
     */
    public function create() {
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('code', 'Código', 'required|min_length[3]|max_length[50]');
            $this->form_validation->set_rules('discount_type', 'Tipo de Desconto', 'required|in_list[percentage,fixed]');
            $this->form_validation->set_rules('discount_value', 'Valor do Desconto', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('min_amount', 'Valor Mínimo', 'numeric');
            $this->form_validation->set_rules('max_uses', 'Máximo de Usos', 'integer');
            $this->form_validation->set_rules('valid_from', 'Válido de', 'required');
            $this->form_validation->set_rules('valid_until', 'Válido até', 'required');

            if ($this->form_validation->run()) {
                $data = [
                    'code' => $this->input->post('code'),
                    'discount_type' => $this->input->post('discount_type'),
                    'discount_value' => $this->input->post('discount_value'),
                    'min_amount' => $this->input->post('min_amount') ?: 0,
                    'max_uses' => $this->input->post('max_uses') ?: null,
                    'valid_from' => $this->input->post('valid_from'),
                    'valid_until' => $this->input->post('valid_until'),
                    'is_active' => $this->input->post('is_active') ? true : false
                ];

                $result = $this->Coupon_model->create_coupon($data);

                if ($result['success']) {
                    $this->session->set_flashdata('success', 'Cupom criado com sucesso!');
                    redirect('coupons');
                } else {
                    $this->session->set_flashdata('error', $result['message']);
                }
            }
        }

        $this->load->view('layouts/base', [
            'title' => 'Criar Cupom',
            'content' => $this->load->view('coupons/create', [], true)
        ]);
    }

    /**
     * Edit coupon form
     */
    public function edit($id) {
        $data['coupon'] = $this->Coupon_model->get_coupon($id);

        if (!$data['coupon']) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('code', 'Código', 'required|min_length[3]|max_length[50]');
            $this->form_validation->set_rules('discount_type', 'Tipo de Desconto', 'required|in_list[percentage,fixed]');
            $this->form_validation->set_rules('discount_value', 'Valor do Desconto', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('min_amount', 'Valor Mínimo', 'numeric');
            $this->form_validation->set_rules('max_uses', 'Máximo de Usos', 'integer');
            $this->form_validation->set_rules('valid_from', 'Válido de', 'required');
            $this->form_validation->set_rules('valid_until', 'Válido até', 'required');

            if ($this->form_validation->run()) {
                $update_data = [
                    'code' => $this->input->post('code'),
                    'discount_type' => $this->input->post('discount_type'),
                    'discount_value' => $this->input->post('discount_value'),
                    'min_amount' => $this->input->post('min_amount') ?: 0,
                    'max_uses' => $this->input->post('max_uses') ?: null,
                    'valid_from' => $this->input->post('valid_from'),
                    'valid_until' => $this->input->post('valid_until'),
                    'is_active' => $this->input->post('is_active') ? true : false
                ];

                $result = $this->Coupon_model->update_coupon($id, $update_data);

                if ($result['success']) {
                    $this->session->set_flashdata('success', 'Cupom atualizado com sucesso!');
                    redirect('coupons');
                } else {
                    $this->session->set_flashdata('error', $result['message']);
                }
            }
        }

        $this->load->view('layouts/base', [
            'title' => 'Editar Cupom',
            'content' => $this->load->view('coupons/edit', $data, true)
        ]);
    }

    /**
     * Delete coupon
     */
    public function delete($id) {
        $coupon = $this->Coupon_model->get_coupon($id);

        if (!$coupon) {
            show_404();
        }

        if ($this->Coupon_model->delete_coupon($id)) {
            $this->session->set_flashdata('success', 'Cupom deletado com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao deletar cupom');
        }

        redirect('coupons');
    }

    /**
     * Get all coupons via AJAX
     */
    public function get_coupons() {
        $this->output->set_content_type('application/json');

        $coupons = $this->Coupon_model->get_all_coupons();

        $this->output->set_output(json_encode([
            'success' => true,
            'coupons' => $coupons
        ]));
    }

    /**
     * Get coupon data via AJAX
     */
    public function get($id) {
        $coupon = $this->Coupon_model->get_coupon($id);

        if ($coupon) {
            echo json_encode([
                'success' => true,
                'coupon' => $coupon
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Cupom não encontrado'
            ]);
        }
    }

    /**
     * Store new coupon via AJAX
     */
    public function store() {
        $this->output->set_content_type('application/json');

        $this->form_validation->set_rules('code', 'Código', 'required|min_length[3]|max_length[50]');
        $this->form_validation->set_rules('discount_type', 'Tipo de Desconto', 'required|in_list[percentage,fixed]');
        $this->form_validation->set_rules('discount_value', 'Valor do Desconto', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('min_amount', 'Valor Mínimo', 'numeric');
        $this->form_validation->set_rules('max_uses', 'Máximo de Usos', 'integer');
        $this->form_validation->set_rules('valid_from', 'Válido de', 'required');
        $this->form_validation->set_rules('valid_until', 'Válido até', 'required');

        if ($this->form_validation->run()) {
            $data = [
                'code' => $this->input->post('code'),
                'discount_type' => $this->input->post('discount_type'),
                'discount_value' => $this->input->post('discount_value'),
                'min_amount' => $this->input->post('min_amount') ?: 0,
                'max_uses' => $this->input->post('max_uses') ?: null,
                'valid_from' => $this->input->post('valid_from'),
                'valid_until' => $this->input->post('valid_until'),
                'is_active' => $this->input->post('is_active') ? true : false
            ];

            $result = $this->Coupon_model->create_coupon($data);

            $this->output->set_output(json_encode([
                'success' => $result['success'],
                'message' => $result['success'] ? 'Cupom criado com sucesso!' : $result['message']
            ]));
        } else {
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => validation_errors()
            ]));
        }
    }

    /**
     * Update coupon via AJAX
     */
    public function update($id) {
        $this->output->set_content_type('application/json');

        $coupon = $this->Coupon_model->get_coupon($id);

        if (!$coupon) {
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Cupom não encontrado'
            ]));
            return;
        }

        $this->form_validation->set_rules('code', 'Código', 'required|min_length[3]|max_length[50]');
        $this->form_validation->set_rules('discount_type', 'Tipo de Desconto', 'required|in_list[percentage,fixed]');
        $this->form_validation->set_rules('discount_value', 'Valor do Desconto', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('min_amount', 'Valor Mínimo', 'numeric');
        $this->form_validation->set_rules('max_uses', 'Máximo de Usos', 'integer');
        $this->form_validation->set_rules('valid_from', 'Válido de', 'required');
        $this->form_validation->set_rules('valid_until', 'Válido até', 'required');

        if ($this->form_validation->run()) {
            $update_data = [
                'code' => $this->input->post('code'),
                'discount_type' => $this->input->post('discount_type'),
                'discount_value' => $this->input->post('discount_value'),
                'min_amount' => $this->input->post('min_amount') ?: 0,
                'max_uses' => $this->input->post('max_uses') ?: null,
                'valid_from' => $this->input->post('valid_from'),
                'valid_until' => $this->input->post('valid_until'),
                'is_active' => $this->input->post('is_active') ? true : false
            ];

            $result = $this->Coupon_model->update_coupon($id, $update_data);

            $this->output->set_output(json_encode([
                'success' => $result['success'],
                'message' => $result['success'] ? 'Cupom atualizado com sucesso!' : $result['message']
            ]));
        } else {
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => validation_errors()
            ]));
        }
    }

    /**
     * Validate coupon via AJAX
     */
    public function validate() {
        $code = $this->input->post('code');
        $subtotal = $this->input->post('subtotal');

        if (!$code || !$subtotal) {
            echo json_encode(['valid' => false, 'message' => 'Dados inválidos']);
            return;
        }

        $result = $this->Coupon_model->validate_coupon($code, $subtotal);

        if ($result['valid']) {
            $discount = $this->Coupon_model->calculate_discount($result['coupon'], $subtotal);
            echo json_encode([
                'valid' => true,
                'discount' => $discount,
                'discount_formatted' => 'R$ ' . number_format($discount, 2, ',', '.'),
                'coupon' => $result['coupon']
            ]);
        } else {
            echo json_encode($result);
        }
    }
}
