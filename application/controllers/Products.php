<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }

    public function index() {
        // Busca todos os produtos
        $products = $this->db->order_by('created_at', 'DESC')->get('products')->result();
        
        // Para cada produto, busca o estoque
        foreach ($products as $product) {
            $product->stock = $this->db->where('product_id', $product->id)->get('stock')->result();
        }
        
        $data['products'] = $products;
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
        $stock_data = $this->input->post('stock');

        // Log para debug
        error_log("Update Product ID: " . $id);
        error_log("Name: " . $name);
        error_log("Price: " . $price);
        error_log("Stock Data: " . print_r($stock_data, true));

        // Atualiza o produto
        $this->db->where('id', $id)->update('products', [
            'name' => $name,
            'price' => $price
        ]);

        // Remove estoque antigo
        $this->db->where('product_id', $id)->delete('stock');

        // Adiciona novo estoque
        if ($stock_data) {
            foreach ($stock_data as $stock_item) {
                if (!empty($stock_item['variation']) && isset($stock_item['quantity'])) {
                    $insert_data = [
                        'product_id' => $id,
                        'variation' => $stock_item['variation'],
                        'quantity' => (int)$stock_item['quantity']
                    ];
                    
                    error_log("Inserting stock: " . print_r($insert_data, true));
                    $this->db->insert('stock', $insert_data);
                    
                    if ($this->db->affected_rows() > 0) {
                        error_log("Stock inserted successfully");
                    } else {
                        error_log("Error inserting stock: " . $this->db->error()['message']);
                    }
                }
            }
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

        if (!$stock) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Variação não encontrada']));
            return;
        }

        // Verifica se a quantidade solicitada está disponível
        if ($stock->quantity < $quantity) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                             'success' => false, 
                             'message' => "Estoque insuficiente. Disponível: {$stock->quantity}"
                         ]));
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
            $new_quantity = $cart[$item_key]['quantity'] + $quantity;
            
            // Verifica se a nova quantidade total não excede o estoque
            if ($new_quantity > $stock->quantity) {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode([
                                 'success' => false, 
                                 'message' => "Quantidade total excede o estoque. Disponível: {$stock->quantity}"
                             ]));
                return;
            }
            
            $cart[$item_key]['quantity'] = $new_quantity;
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

    public function remove_from_cart($item_key = null) {
        if (!$item_key) {
            redirect('products/cart');
        }
        
        $cart = $this->session->userdata('cart') ?: [];
        
        if (isset($cart[$item_key])) {
            unset($cart[$item_key]);
            $this->session->set_userdata('cart', $cart);
        }

        redirect('products/cart');
    }

    public function clear_cart() {
        $this->session->unset_userdata('cart');
        redirect('products/cart');
    }

    public function checkout() {
        $data['cart'] = $this->session->userdata('cart') ?: [];
        $this->load->view('products/checkout', $data);
    }

    public function finalize_order() {
        $cart = $this->session->userdata('cart') ?: [];
        
        if (empty($cart)) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Carrinho vazio']));
            return;
        }

        // Dados do pedido
        $order_data = [
            'total' => 0,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Calcula total e verifica estoque novamente
        foreach ($cart as $item) {
            $order_data['total'] += $item['price'] * $item['quantity'];
            
            // Verifica se ainda há estoque suficiente
            $stock = $this->db->where('product_id', $item['product_id'])
                              ->where('variation', $item['variation'])
                              ->get('stock')->row();
            
            if (!$stock || $stock->quantity < $item['quantity']) {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode([
                                 'success' => false, 
                                 'message' => "Estoque insuficiente para {$item['name']} - {$item['variation']}. Disponível: " . ($stock ? $stock->quantity : 0)
                             ]));
                return;
            }
        }

        // Inicia transação
        $this->db->trans_start();

        try {
            // Insere o pedido
            $this->db->insert('orders', $order_data);
            $order_id = $this->db->insert_id();

            // Atualiza estoque e insere itens do pedido
            foreach ($cart as $item) {
                // Diminui o estoque
                $this->db->where('product_id', $item['product_id'])
                         ->where('variation', $item['variation'])
                         ->set('quantity', 'quantity - ' . $item['quantity'], false)
                         ->update('stock');
                
                // Insere item do pedido (se tivesse tabela order_items)
                // $this->db->insert('order_items', [
                //     'order_id' => $order_id,
                //     'product_id' => $item['product_id'],
                //     'variation' => $item['variation'],
                //     'quantity' => $item['quantity'],
                //     'price' => $item['price']
                // ]);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => false, 'message' => 'Erro ao processar pedido']));
                return;
            }

            // Limpa o carrinho
            $this->session->unset_userdata('cart');

            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                             'success' => true, 
                             'message' => 'Pedido finalizado com sucesso!',
                             'order_id' => $order_id
                         ]));

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Erro ao processar pedido: ' . $e->getMessage()]));
        }
    }

    public function get_stock($product_id = null) {
        if (!$product_id) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'ID do produto é obrigatório']));
            return;
        }

        $stock = $this->db->where('product_id', $product_id)->get('stock')->result();
        
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode([
                         'success' => true,
                         'stock' => $stock
                     ]));
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
