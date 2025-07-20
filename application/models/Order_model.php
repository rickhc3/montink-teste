<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Create new order
     */
    public function create_order($order_data, $cart_items) {
        $this->db->trans_start();

        // Insert order
        $order_insert_data = [
            'customer_name' => $order_data['customer_name'],
            'customer_email' => $order_data['customer_email'],
            'customer_phone' => $order_data['customer_phone'] ?? null,
            'shipping_address' => $order_data['shipping_address'],
            'shipping_city' => $order_data['shipping_city'],
            'shipping_state' => $order_data['shipping_state'],
            'shipping_zipcode' => $order_data['shipping_zipcode'],
            'subtotal' => $order_data['subtotal'],
            'shipping_cost' => $order_data['shipping_cost'] ?? 0,
            'discount_amount' => $order_data['discount_amount'] ?? 0,
            'total' => $order_data['total'],
            'coupon_code' => $order_data['coupon_code'] ?? null,
            'status' => 'pending'
        ];

        $this->db->insert('orders', $order_insert_data);
        $order_id = $this->db->insert_id();

        // Insert order items
        foreach ($cart_items as $item) {
            $item_data = [
                'order_id' => $order_id,
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'variation' => $item['variation'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price']
            ];
            $this->db->insert('order_items', $item_data);

            // Update stock
            if ($item['variation']) {
                $this->db->where('product_id', $item['product_id']);
                $this->db->where('variation', $item['variation']);
                $this->db->set('quantity', 'quantity - ' . $item['quantity'], FALSE);
                $this->db->update('stock');
            }
        }

        // Add status history
        $this->add_status_history($order_id, null, 'pending', 'system', 'Pedido criado');

        // Increment coupon usage if used
        if (!empty($order_data['coupon_code'])) {
            $this->load->model('Coupon_model');
            $this->Coupon_model->increment_usage($order_data['coupon_code']);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ['success' => false, 'message' => 'Erro ao criar pedido'];
        }

        return ['success' => true, 'order_id' => $order_id];
    }

    /**
     * Get order by ID with items
     */
    public function get_order($id) {
        $order = $this->db->get_where('orders', ['id' => $id])->row();
        if ($order) {
            $order->items = $this->get_order_items($id);
        }
        return $order;
    }

    /**
     * Get order items
     */
    public function get_order_items($order_id) {
        return $this->db->get_where('order_items', ['order_id' => $order_id])->result();
    }

    /**
     * Update order status
     */
    public function update_status($order_id, $new_status, $changed_by = 'system', $notes = null) {
        $order = $this->get_order($order_id);
        if (!$order) {
            return ['success' => false, 'message' => 'Pedido não encontrado'];
        }

        $old_status = $order->status;
        
        $this->db->trans_start();

        // Update order status
        $this->db->where('id', $order_id);
        $this->db->update('orders', ['status' => $new_status]);

        // Add status history
        $this->add_status_history($order_id, $old_status, $new_status, $changed_by, $notes);

        // If cancelled, restore stock
        if ($new_status === 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->variation) {
                    $this->db->where('product_id', $item->product_id);
                    $this->db->where('variation', $item->variation);
                    $this->db->set('quantity', 'quantity + ' . $item->quantity, FALSE);
                    $this->db->update('stock');
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ['success' => false, 'message' => 'Erro ao atualizar status'];
        }

        return ['success' => true];
    }

    /**
     * Delete order (for cancelled orders)
     */
    public function delete_order($order_id) {
        $order = $this->get_order($order_id);
        if (!$order) {
            return ['success' => false, 'message' => 'Pedido não encontrado'];
        }

        $this->db->trans_start();

        // Restore stock if not already cancelled
        if ($order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->variation) {
                    $this->db->where('product_id', $item->product_id);
                    $this->db->where('variation', $item->variation);
                    $this->db->set('quantity', 'quantity + ' . $item->quantity, FALSE);
                    $this->db->update('stock');
                }
            }
        }

        // Delete order (cascade will handle items and history)
        $this->db->where('id', $order_id);
        $this->db->delete('orders');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ['success' => false, 'message' => 'Erro ao deletar pedido'];
        }

        return ['success' => true];
    }

    /**
     * Add status history entry
     */
    private function add_status_history($order_id, $old_status, $new_status, $changed_by, $notes) {
        $history_data = [
            'order_id' => $order_id,
            'old_status' => $old_status,
            'new_status' => $new_status,
            'changed_by' => $changed_by,
            'notes' => $notes
        ];
        $this->db->insert('order_status_history', $history_data);
    }

    /**
     * Get order status history
     */
    public function get_status_history($order_id) {
        $this->db->where('order_id', $order_id);
        $this->db->order_by('created_at', 'ASC');
        return $this->db->get('order_status_history')->result();
    }

    /**
     * Get all orders with pagination
     */
    public function get_all_orders($limit = 10, $offset = 0) {
        $this->db->limit($limit, $offset);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('orders')->result();
    }

    /**
     * Get total orders count
     */
    public function get_total_orders() {
        return $this->db->count_all('orders');
    }

    /**
     * Get orders by status
     */
    public function get_orders_by_status($status) {
        $this->db->where('status', $status);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('orders')->result();
    }
}