<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coupon_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all coupons with pagination
     */
    public function get_all_coupons($limit = 10, $offset = 0) {
        $this->db->limit($limit, $offset);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('coupons')->result();
    }

    /**
     * Get coupon by ID
     */
    public function get_coupon($id) {
        return $this->db->get_where('coupons', ['id' => $id])->row();
    }

    /**
     * Get coupon by code
     */
    public function get_coupon_by_code($code) {
        return $this->db->get_where('coupons', ['code' => $code])->row();
    }

    /**
     * Validate coupon for use
     */
    public function validate_coupon($code, $subtotal) {
        $coupon = $this->get_coupon_by_code($code);
        
        if (!$coupon) {
            return ['valid' => false, 'message' => 'Cupom não encontrado'];
        }

        if (!$coupon->is_active) {
            return ['valid' => false, 'message' => 'Cupom inativo'];
        }

        $today = date('Y-m-d');
        if ($today < $coupon->valid_from || $today > $coupon->valid_until) {
            return ['valid' => false, 'message' => 'Cupom fora do período de validade'];
        }

        if ($subtotal < $coupon->min_amount) {
            return ['valid' => false, 'message' => 'Valor mínimo não atingido. Mínimo: R$ ' . number_format($coupon->min_amount, 2, ',', '.')];
        }

        if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
            return ['valid' => false, 'message' => 'Cupom esgotado'];
        }

        return ['valid' => true, 'coupon' => $coupon];
    }

    /**
     * Calculate discount amount
     */
    public function calculate_discount($coupon, $subtotal) {
        if ($coupon->discount_type === 'percentage') {
            return ($subtotal * $coupon->discount_value) / 100;
        } else {
            return min($coupon->discount_value, $subtotal);
        }
    }

    /**
     * Create new coupon
     */
    public function create_coupon($data) {
        // Check if code already exists
        if ($this->get_coupon_by_code($data['code'])) {
            return ['success' => false, 'message' => 'Código do cupom já existe'];
        }

        $coupon_data = [
            'code' => strtoupper($data['code']),
            'discount_type' => $data['discount_type'],
            'discount_value' => $data['discount_value'],
            'min_amount' => $data['min_amount'] ?? 0,
            'max_uses' => $data['max_uses'] ?? null,
            'valid_from' => $data['valid_from'],
            'valid_until' => $data['valid_until'],
            'is_active' => $data['is_active'] ?? true
        ];

        if ($this->db->insert('coupons', $coupon_data)) {
            return ['success' => true, 'id' => $this->db->insert_id()];
        }

        return ['success' => false, 'message' => 'Erro ao criar cupom'];
    }

    /**
     * Update coupon
     */
    public function update_coupon($id, $data) {
        // Check if code already exists for other coupons
        $existing = $this->db->get_where('coupons', ['code' => $data['code'], 'id !=' => $id])->row();
        if ($existing) {
            return ['success' => false, 'message' => 'Código do cupom já existe'];
        }

        $coupon_data = [
            'code' => strtoupper($data['code']),
            'discount_type' => $data['discount_type'],
            'discount_value' => $data['discount_value'],
            'min_amount' => $data['min_amount'] ?? 0,
            'max_uses' => $data['max_uses'] ?? null,
            'valid_from' => $data['valid_from'],
            'valid_until' => $data['valid_until'],
            'is_active' => $data['is_active'] ?? true
        ];

        $this->db->where('id', $id);
        if ($this->db->update('coupons', $coupon_data)) {
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Erro ao atualizar cupom'];
    }

    /**
     * Delete coupon
     */
    public function delete_coupon($id) {
        $this->db->where('id', $id);
        return $this->db->delete('coupons');
    }

    /**
     * Increment coupon usage
     */
    public function increment_usage($code) {
        $this->db->where('code', $code);
        $this->db->set('used_count', 'used_count + 1', FALSE);
        return $this->db->update('coupons');
    }

    /**
     * Get total coupons count
     */
    public function get_total_coupons() {
        return $this->db->count_all('coupons');
    }

    /**
     * Get active coupons
     */
    public function get_active_coupons() {
        $today = date('Y-m-d');
        $this->db->where('is_active', true);
        $this->db->where('valid_from <=', $today);
        $this->db->where('valid_until >=', $today);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('coupons')->result();
    }
}