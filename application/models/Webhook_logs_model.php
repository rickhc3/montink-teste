<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webhook_logs_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Log webhook request
     */
    public function log_webhook($order_id, $old_status, $new_status, $webhook_data, $success = true, $error_message = null) {
        $log_data = [
            'order_id' => $order_id,
            'old_status' => $old_status,
            'new_status' => $new_status,
            'webhook_data' => is_array($webhook_data) ? json_encode($webhook_data) : $webhook_data,
            'success' => $success,
            'error_message' => $error_message
        ];

        // Disable foreign key checks temporarily for logging failed webhooks
        if (!$success && $order_id) {
            // Check if order exists before inserting
            $order_exists = $this->db->where('id', $order_id)->count_all_results('orders');
            if (!$order_exists) {
                // Set order_id to NULL for non-existent orders to avoid FK constraint
                $log_data['order_id'] = null;
                $log_data['error_message'] = ($error_message ? $error_message . ' ' : '') . "(Order ID {$order_id} not found)";
            }
        }

        return $this->db->insert('webhook_logs', $log_data);
    }

    /**
     * Get webhook logs for an order
     */
    public function get_logs_by_order($order_id) {
        $this->db->where('order_id', $order_id);
        $this->db->order_by('processed_at', 'DESC');
        return $this->db->get('webhook_logs')->result_array();
    }

    /**
     * Get all webhook logs with pagination
     */
    public function get_all_logs($limit = 50, $offset = 0) {
        $this->db->order_by('processed_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get('webhook_logs')->result_array();
    }

    /**
     * Get failed webhook logs
     */
    public function get_failed_logs($limit = 50, $offset = 0) {
        $this->db->where('success', false);
        $this->db->order_by('processed_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get('webhook_logs')->result_array();
    }

    /**
     * Count total webhook logs
     */
    public function count_logs() {
        return $this->db->count_all('webhook_logs');
    }

    /**
     * Count failed webhook logs
     */
    public function count_failed_logs() {
        $this->db->where('success', false);
        return $this->db->count_all_results('webhook_logs');
    }

    /**
     * Delete old logs (older than specified days)
     */
    public function cleanup_old_logs($days = 30) {
        $this->db->where('processed_at <', date('Y-m-d H:i:s', strtotime("-{$days} days")));
        return $this->db->delete('webhook_logs');
    }
}