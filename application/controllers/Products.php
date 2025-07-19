<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }

    public function index() {
        $data['products'] = $this->db->query("
            SELECT p.*, 
                   GROUP_CONCAT(CONCAT(s.variation, ': ', s.quantity) SEPARATOR ', ') as variations
            FROM products p
            LEFT JOIN stock s ON p.id = s.product_id
            GROUP BY p.id
            ORDER BY p.created_at DESC
        ")->result();
        
        $this->load->view('products/index', $data);
    }

    public function create() {
        $this->load->view('products/create');
    }

    public function edit($id = null) {
        if (!$id) {
            redirect('products');
        }

        $data['product'] = $this->db->where('id', $id)->get('products')->row();
        $data['stock'] = $this->db->where('product_id', $id)->get('stock')->result();
        
        if (!$data['product']) {
            redirect('products');
        }

        $this->load->view('products/edit', $data);
    }

    public function update($id = null) {
        if (!$id) {
            redirect('products');
        }

        $name = $this->input->post('name');
        $price = $this->input->post('price');
        $variations = $this->input->post('variations');

        // Atualiza o produto
        $this->db->where('id', $id)->update('products', [
            'name' => $name,
            'price' => $price
        ]);

        // Remove estoque antigo
        $this->db->where('product_id', $id)->delete('stock');

        // Adiciona novo estoque
        foreach ($variations as $v) {
            $this->db->insert('stock', [
                'product_id' => $id,
                'variation' => $v['name'],
                'quantity' => $v['quantity']
            ]);
        }

        redirect('products');
    }

    public function store() {
        $name = $this->input->post('name');
        $price = $this->input->post('price');
        $variations = $this->input->post('variations');

        $this->db->insert('products', [
            'name' => $name,
            'price' => $price,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $product_id = $this->db->insert_id();

        foreach ($variations as $v) {
            $this->db->insert('stock', [
                'product_id' => $product_id,
                'variation' => $v['name'],
                'quantity' => $v['quantity']
            ]);
        }

        redirect('products');
    }

    public function add_to_cart() {
        $product_id = $this->input->post('product_id');
        $variation = $this->input->post('variation');
        $quantity = (int)$this->input->post('quantity');

        // Verifica se há estoque
        $stock = $this->db->where('product_id', $product_id)
                          ->where('variation', $variation)
                          ->get('stock')->row();

        if (!$stock || $stock->quantity < $quantity) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Estoque insuficiente']));
            return;
        }

        // Busca dados do produto
        $product = $this->db->where('id', $product_id)->get('products')->row();

        // Inicializa carrinho se não existir
        if (!$this->session->userdata('cart')) {
            $this->session->set_userdata('cart', []);
        }

        $cart = $this->session->userdata('cart');
        $item_key = $product_id . '_' . $variation;

        if (isset($cart[$item_key])) {
            $cart[$item_key]['quantity'] += $quantity;
        } else {
            $cart[$item_key] = [
                'product_id' => $product_id,
                'name' => $product->name,
                'price' => $product->price,
                'variation' => $variation,
                'quantity' => $quantity
            ];
        }

        $this->session->set_userdata('cart', $cart);

        $this->output->set_content_type('application/json')
                     ->set_output(json_encode(['success' => true, 'message' => 'Produto adicionado ao carrinho']));
    }

    public function cart() {
        $data['cart'] = $this->session->userdata('cart') ?: [];
        $this->load->view('products/cart', $data);
    }

    public function remove_from_cart() {
        $item_key = $this->input->post('item_key');
        $cart = $this->session->userdata('cart') ?: [];
        
        if (isset($cart[$item_key])) {
            unset($cart[$item_key]);
            $this->session->set_userdata('cart', $cart);
        }

        redirect('products/cart');
    }

    public function checkout() {
        $data['cart'] = $this->session->userdata('cart') ?: [];
        $this->load->view('products/checkout', $data);
    }

    public function calculate_shipping() {
        $cep = $this->input->post('cep');
        $subtotal = (float)$this->input->post('subtotal');

        // Calcula frete baseado no subtotal
        if ($subtotal >= 200.00) {
            $shipping = 0.00; // Frete grátis
        } elseif ($subtotal >= 52.00 && $subtotal <= 166.59) {
            $shipping = 15.00;
        } else {
            $shipping = 20.00;
        }

        // Busca dados do CEP
        $cep_data = $this->get_cep_data($cep);

        $this->output->set_content_type('application/json')
                     ->set_output(json_encode([
                         'success' => true,
                         'shipping' => $shipping,
                         'total' => $subtotal + $shipping,
                         'cep_data' => $cep_data
                     ]));
    }

    private function get_cep_data($cep) {
        $cep = preg_replace('/[^0-9]/', '', $cep);
        
        if (strlen($cep) !== 8) {
            return null;
        }

        $url = "https://viacep.com.br/ws/{$cep}/json/";
        $response = file_get_contents($url);
        
        if ($response === false) {
            return null;
        }

        $data = json_decode($response, true);
        
        if (isset($data['erro']) && $data['erro']) {
            return null;
        }

        return $data;
    }
}
