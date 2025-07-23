<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

    public $db;
    public $session;
    public $email;
    public $Coupon_model;
    public $Order_model;
    public $load;
    public $input;
    public $output;
    public $form_validation;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(['session', 'email']);
        $this->load->model(['Coupon_model', 'Order_model']);
        $this->load->helper(['url', 'form']);
    }
    
    /**
     * Lê dados JSON do corpo da requisição ou fallback para form-data
     */
    private function get_request_data() {
        $input = json_decode(trim(file_get_contents('php://input')), true);
        
        if (is_array($input)) {
            return $input;
        } else {
            // Fallback para form-data
            return $this->input->post();
        }
    }

    public function index() {
        // Busca todos os produtos
        $products = $this->db->order_by('created_at', 'DESC')->get('products')->result();
        
        // Para cada produto, busca o estoque
        foreach ($products as $product) {
            $stock_items = $this->db->where('product_id', $product->id)->get('stock')->result();
            
            // Converte as quantidades para números inteiros
            foreach ($stock_items as $stock) {
                $stock->quantity = (int)$stock->quantity;
            }
            
            $product->stock = $stock_items;
            

            
            // Verifica se o ID está presente
            if (!isset($product->id) || empty($product->id)) {
                log_message('error', 'ERRO: Produto sem ID - ' . json_encode($product));
            }
        }
        
        $data['products'] = $products;
        $this->load->view('layouts/base', [
            'title' => 'Catálogo de Produtos',
            'content' => $this->load->view('products/content', $data, true)
        ]);
    }

    public function get_products() {
        // Busca todos os produtos
        $products = $this->db->order_by('created_at', 'DESC')->get('products')->result();
        
        // Para cada produto, busca o estoque
        foreach ($products as $product) {
            $stock_items = $this->db->where('product_id', $product->id)->get('stock')->result();
            
            // Converte as quantidades para números inteiros
            foreach ($stock_items as $stock) {
                $stock->quantity = (int)$stock->quantity;
            }
            
            $product->stock = $stock_items;
        }
        
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode([
                         'success' => true,
                         'products' => $products
                     ]));
    }

    public function test() {
        $this->load->view('products/test');
    }

    public function create() {
        $this->load->view('products/create');
    }

    public function edit($id = null) {
        if (!$id) {
            redirect('products');
        }

        $data['product'] = $this->db->where('id', $id)->get('products')->row();
        $stock_items = $this->db->where('product_id', $id)->get('stock')->result();
        
        // Converte as quantidades para números inteiros
        foreach ($stock_items as $stock) {
            $stock->quantity = (int)$stock->quantity;
        }
        
        $data['stock'] = $stock_items;
        
        if (!$data['product']) {
            redirect('products');
        }

        $this->load->view('products/edit', $data);
    }

    public function update() {
        $input = $this->get_request_data();
        
        $id = $input['id'] ?? null;
        $name = $input['name'] ?? null;
        $price = $input['price'] ?? null;
        $stock_data = $input['stock'] ?? [];
        
        if (!$id) {
            if ($this->input->get_request_header('X-Requested-With') === 'XMLHttpRequest') {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => false, 'message' => 'ID do produto é obrigatório']));
            } else {
                redirect('products');
            }
            return;
        }

        try {
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
                        
                        $this->db->insert('stock', $insert_data);
                    }
                }
            }

            // Verifica se é uma requisição AJAX
            if ($this->input->get_request_header('X-Requested-With') === 'XMLHttpRequest') {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => true, 'message' => 'Produto atualizado com sucesso']));
            } else {
                redirect('products');
            }
        } catch (Exception $e) {
            if ($this->input->get_request_header('X-Requested-With') === 'XMLHttpRequest') {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => false, 'message' => 'Erro ao atualizar produto: ' . $e->getMessage()]));
            } else {
                redirect('products');
            }
        }
    }

    public function store() {
        $input = $this->get_request_data();
        
        $name = $input['name'] ?? null;
        $price = $input['price'] ?? null;
        $variations = $input['variations'] ?? [];

        try {
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

            // Verifica se é uma requisição AJAX
            if ($this->input->get_request_header('X-Requested-With') === 'XMLHttpRequest') {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => true, 'message' => 'Produto criado com sucesso']));
            } else {
                redirect('products');
            }
        } catch (Exception $e) {
            if ($this->input->get_request_header('X-Requested-With') === 'XMLHttpRequest') {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => false, 'message' => 'Erro ao criar produto: ' . $e->getMessage()]));
            } else {
                redirect('products');
            }
        }
    }

    public function add_to_cart() {
        $input = $this->get_request_data();
        
        $product_id = $input['product_id'] ?? null;
        $variation = $input['variation'] ?? null;
        $quantity = (int)($input['quantity'] ?? 0);

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
        $quantity_to_add = $quantity;

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

        // Reduzir estoque em tempo real
        $new_stock_quantity = $stock->quantity - $quantity_to_add;
        $this->db->where('product_id', $product_id)
                 ->where('variation', $variation)
                 ->update('stock', ['quantity' => $new_stock_quantity]);

        $this->session->set_userdata('cart', $cart);

        $this->output->set_content_type('application/json')
                     ->set_output(json_encode([
                         'success' => true, 
                         'message' => 'Produto adicionado ao carrinho',
                         'new_stock' => $new_stock_quantity
                     ]));
    }

    public function cart() {
        $data['cart'] = $this->session->userdata('cart') ?: [];
        $this->load->view('products/cart', $data);
    }

    public function remove_from_cart($item_key = null) {
        if (!$item_key) {
            if ($this->input->get_request_header('X-Requested-With') === 'XMLHttpRequest') {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => false, 'message' => 'Item não especificado']));
                return;
            }
            redirect('cart');
        }
        
        // Decodifica a chave do item que pode vir codificada da URL
        $item_key = urldecode($item_key);
        
        $cart = $this->session->userdata('cart') ?: [];
        
        if (isset($cart[$item_key])) {
            $item = $cart[$item_key];
            
            // Devolver estoque antes de remover do carrinho
            $this->increase_stock($item['product_id'], $item['variation'], $item['quantity']);
            
            unset($cart[$item_key]);
            $this->session->set_userdata('cart', $cart);
            
            if ($this->input->get_request_header('X-Requested-With') === 'XMLHttpRequest') {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => true, 'message' => 'Item removido com sucesso']));
                return;
            }
        } else {
            if ($this->input->get_request_header('X-Requested-With') === 'XMLHttpRequest') {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => false, 'message' => 'Item não encontrado']));
                return;
            }
        }

        redirect('cart');
    }

    public function clear_cart() {
        $cart = $this->session->userdata('cart') ?: [];
        
        // Devolver estoque de todos os itens antes de limpar o carrinho
        foreach ($cart as $item) {
            $this->increase_stock($item['product_id'], $item['variation'], $item['quantity']);
        }
        
        $this->session->unset_userdata('cart');
        
        // Verifica se é uma requisição AJAX
        if ($this->input->get_request_header('X-Requested-With') === 'XMLHttpRequest') {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => true, 'message' => 'Carrinho limpo com sucesso']));
        } else {
            redirect('cart');
        }
    }

    public function clear_sessions() {
        // Limpa todas as sessões do CodeIgniter
        $this->session->sess_destroy();
        
        // Verifica se é uma requisição AJAX
        if ($this->input->get_request_header('X-Requested-With') === 'XMLHttpRequest') {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => true, 'message' => 'Sessões limpas com sucesso']));
        } else {
            redirect('products');
        }
    }

    public function checkout() {
        $cart = $this->session->userdata('cart');
        
        if (empty($cart)) {
            redirect('cart');
        }
        

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $shipping = 20.00; // Valor fixo de frete por enquanto
        $total = $subtotal + $shipping;
        
        $data['cart'] = $cart;
        $data['subtotal'] = $subtotal;
        $data['shipping'] = $shipping;
        $data['total'] = $total;
        
        $this->load->view('products/checkout', $data);
    }
    
    public function finalize_order() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        // Validação dos dados
        $this->form_validation->set_rules('customer_name', 'Nome', 'required|trim');
        $this->form_validation->set_rules('customer_email', 'E-mail', 'required|valid_email|trim');
        $this->form_validation->set_rules('customer_phone', 'Telefone', 'required|trim');
        $this->form_validation->set_rules('customer_cep', 'CEP', 'required|trim');
        $this->form_validation->set_rules('customer_address', 'Endereço', 'required|trim');
        $this->form_validation->set_rules('customer_number', 'Número', 'required|trim');
        $this->form_validation->set_rules('customer_neighborhood', 'Bairro', 'required|trim');
        $this->form_validation->set_rules('customer_city', 'Cidade', 'required|trim');
        $this->form_validation->set_rules('customer_state', 'Estado', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                             'success' => false,
                             'message' => validation_errors()
                         ]));
            return;
        }
        
        $cart = $this->session->userdata('cart');
        
        if (empty($cart)) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                             'success' => false,
                             'message' => 'Carrinho vazio'
                         ]));
            return;
        }
        

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // Obter valores da sessão (mais seguro)
        $shipping = $this->session->userdata('shipping_cost') ?? $this->calculate_shipping_cost($subtotal);
        $discount = $this->session->userdata('coupon_discount') ?? 0;
        $coupon_code = $this->session->userdata('coupon_code') ?? '';
        
        // Verificar se o cupom ainda é válido com o subtotal atual
        if ($coupon_code && $this->session->userdata('coupon_subtotal') != $subtotal) {
            $coupon_result = $this->validate_coupon_internal($coupon_code, $subtotal);
            if ($coupon_result['valid']) {
                $discount = $coupon_result['discount'];
                // Atualizar sessão com novo desconto
                $this->session->set_userdata([
                    'coupon_discount' => $discount,
                    'coupon_subtotal' => $subtotal
                ]);
            } else {
                // Cupom não é mais válido
                $discount = 0;
                $coupon_code = '';
                $this->session->unset_userdata(['coupon_code', 'coupon_discount', 'coupon_subtotal']);
            }
        }
        
        $total = $subtotal + $shipping - $discount;
        
        // Dados do cliente
        $customer_data = [
            'name' => $this->input->post('customer_name'),
            'email' => $this->input->post('customer_email'),
            'phone' => $this->input->post('customer_phone'),
            'cep' => $this->input->post('customer_cep'),
            'address' => $this->input->post('customer_address'),
            'number' => $this->input->post('customer_number'),
            'complement' => $this->input->post('customer_complement'),
            'neighborhood' => $this->input->post('customer_neighborhood'),
            'city' => $this->input->post('customer_city'),
            'state' => $this->input->post('customer_state')
        ];
        
        // Dados do pedido
        $order_data = [
            'customer_name' => $customer_data['name'],
            'customer_email' => $customer_data['email'],
            'customer_phone' => $customer_data['phone'],
            'customer_cep' => $customer_data['cep'],
            'customer_address' => $customer_data['address'],
            'customer_number' => $customer_data['number'],
            'customer_complement' => $customer_data['complement'],
            'customer_neighborhood' => $customer_data['neighborhood'],
            'customer_city' => $customer_data['city'],
            'customer_state' => $customer_data['state'],
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => $discount,
            'total' => $total,
            'coupon_code' => $coupon_code,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        try {
            // Preparar dados do pedido para o banco
            $order_db_data = [
                'customer_name' => $customer_data['name'],
                'customer_email' => $customer_data['email'],
                'customer_phone' => $customer_data['phone'],
                'customer_document' => $this->input->post('customer_document'),
                'shipping_address' => $customer_data['address'],
                'shipping_number' => $customer_data['number'],
                'shipping_complement' => $customer_data['complement'],
                'shipping_neighborhood' => $customer_data['neighborhood'],
                'shipping_city' => $customer_data['city'],
                'shipping_state' => $customer_data['state'],
                'shipping_zipcode' => $customer_data['cep'],
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'discount_amount' => $discount,
                'total' => $total,
                'coupon_code' => $coupon_code
            ];
            
            // Preparar itens do carrinho para o banco
            $cart_items_db = [];
            foreach ($cart as $item) {
                $cart_items_db[] = [
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'], // Corrigido: usar 'name' ao invés de 'product_name'
                    'variation' => $item['variation'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price']
                ];
            }
            
            // Salvar pedido no banco de dados
            $result = $this->Order_model->create_order($order_db_data, $cart_items_db);
            
            if (!$result['success']) {
                throw new Exception($result['message']);
            }
            
            $order_id = $result['order_id'];
            $order_data['id'] = $order_id;
            
            // Enviar e-mail de confirmação
            $email_sent = $this->send_order_confirmation_email($order_data, $cart);
            
            // Limpar carrinho e dados de checkout
            $this->session->unset_userdata([
                'cart',
                'shipping_cep',
                'shipping_cost',
                'shipping_subtotal',
                'coupon_code',
                'coupon_discount',
                'coupon_subtotal'
            ]);
            
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                             'success' => true,
                             'message' => 'Pedido finalizado com sucesso!',
                             'order_id' => $order_id,
                             'email_sent' => $email_sent
                         ]));
            
        } catch (Exception $e) {
            log_message('error', 'Erro ao processar checkout: ' . $e->getMessage());
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                             'success' => false,
                             'message' => 'Erro interno do servidor. Tente novamente.'
                         ]));
        }
    }
        
    public function checkout_process() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        // Validação dos dados
        $this->form_validation->set_rules('customer_name', 'Nome', 'required|trim');
        $this->form_validation->set_rules('customer_email', 'E-mail', 'required|valid_email|trim');
        $this->form_validation->set_rules('customer_phone', 'Telefone', 'required|trim');
        $this->form_validation->set_rules('customer_cep', 'CEP', 'required|trim');
        $this->form_validation->set_rules('customer_address', 'Endereço', 'required|trim');
        $this->form_validation->set_rules('customer_number', 'Número', 'required|trim');
        $this->form_validation->set_rules('customer_neighborhood', 'Bairro', 'required|trim');
        $this->form_validation->set_rules('customer_city', 'Cidade', 'required|trim');
        $this->form_validation->set_rules('customer_state', 'Estado', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                             'success' => false,
                             'message' => validation_errors()
                         ]));
            return;
        }
        
        $cart = $this->session->userdata('cart');
        
        if (empty($cart)) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                             'success' => false,
                             'message' => 'Carrinho vazio'
                         ]));
            return;
        }
        
        
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // Obter valores da sessão (mais seguro)
        $shipping = $this->session->userdata('shipping_cost') ?? $this->calculate_shipping_cost($subtotal);
        $discount = $this->session->userdata('coupon_discount') ?? 0;
        $coupon_code = $this->session->userdata('coupon_code') ?? '';
        
        // Verificar se o cupom ainda é válido com o subtotal atual
        if ($coupon_code && $this->session->userdata('coupon_subtotal') != $subtotal) {
            $coupon_result = $this->validate_coupon_internal($coupon_code, $subtotal);
            if ($coupon_result['valid']) {
                $discount = $coupon_result['discount'];
                // Atualizar sessão com novo desconto
                $this->session->set_userdata([
                    'coupon_discount' => $discount,
                    'coupon_subtotal' => $subtotal
                ]);
            } else {
                // Cupom não é mais válido
                $discount = 0;
                $coupon_code = '';
                $this->session->unset_userdata(['coupon_code', 'coupon_discount', 'coupon_subtotal']);
            }
        }
        
        $total = $subtotal + $shipping - $discount;
        
        // Dados do cliente
        $customer_data = [
            'name' => $this->input->post('customer_name'),
            'email' => $this->input->post('customer_email'),
            'phone' => $this->input->post('customer_phone'),
            'cep' => $this->input->post('customer_cep'),
            'address' => $this->input->post('customer_address'),
            'number' => $this->input->post('customer_number'),
            'complement' => $this->input->post('customer_complement'),
            'neighborhood' => $this->input->post('customer_neighborhood'),
            'city' => $this->input->post('customer_city'),
            'state' => $this->input->post('customer_state')
        ];
        
        // Dados do pedido
        $order_data = [
            'customer_name' => $customer_data['name'],
            'customer_email' => $customer_data['email'],
            'customer_phone' => $customer_data['phone'],
            'customer_cep' => $customer_data['cep'],
            'customer_address' => $customer_data['address'],
            'customer_number' => $customer_data['number'],
            'customer_complement' => $customer_data['complement'],
            'customer_neighborhood' => $customer_data['neighborhood'],
            'customer_city' => $customer_data['city'],
            'customer_state' => $customer_data['state'],
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => $discount,
            'total' => $total,
            'coupon_code' => $coupon_code,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        try {
            // Preparar dados do pedido para o banco
            $order_db_data = [
                'customer_name' => $customer_data['name'],
                'customer_email' => $customer_data['email'],
                'customer_phone' => $customer_data['phone'],
                'customer_document' => $this->input->post('customer_document'),
                'shipping_address' => $customer_data['address'],
                'shipping_number' => $customer_data['number'],
                'shipping_complement' => $customer_data['complement'],
                'shipping_neighborhood' => $customer_data['neighborhood'],
                'shipping_city' => $customer_data['city'],
                'shipping_state' => $customer_data['state'],
                'shipping_zipcode' => $customer_data['cep'],
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'discount_amount' => $discount,
                'total' => $total,
                'coupon_code' => $coupon_code
            ];
            
            // Preparar itens do carrinho para o banco
            $cart_items_db = [];
            foreach ($cart as $item) {
                $cart_items_db[] = [
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'], // Corrigido: usar 'name' ao invés de 'product_name'
                    'variation' => $item['variation'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price']
                ];
            }
            
            // Salvar pedido no banco de dados
            $result = $this->Order_model->create_order($order_db_data, $cart_items_db);
            
            if (!$result['success']) {
                throw new Exception($result['message']);
            }
            
            $order_id = $result['order_id'];
            $order_data['id'] = $order_id;
            
            // Enviar e-mail de confirmação
            $email_sent = $this->send_order_confirmation_email($order_data, $cart);
            
            // Limpar carrinho e dados de checkout
            $this->session->unset_userdata([
                'cart',
                'shipping_cep',
                'shipping_cost',
                'shipping_subtotal',
                'coupon_code',
                'coupon_discount',
                'coupon_subtotal'
            ]);
            
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                             'success' => true,
                             'message' => 'Pedido finalizado com sucesso!',
                             'order_id' => $order_id,
                             'email_sent' => $email_sent
                         ]));
            
        } catch (Exception $e) {
            log_message('error', 'Erro ao processar checkout: ' . $e->getMessage());
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                             'success' => false,
                             'message' => 'Erro interno do servidor. Tente novamente.'
                         ]));
        }
    }



    /**
     * Calcular custo do frete
     */
    private function calculate_shipping_cost($subtotal) {
        if ($subtotal >= 200.00) {
            return 0.00; // Frete grátis
        } elseif ($subtotal >= 52.00 && $subtotal <= 166.59) {
            return 15.00;
        } else {
            return 20.00;
        }
    }

    /**
     * Armazenar dados de frete na sessão
     */
    public function store_shipping_data() {
        $cep = $this->input->post('cep');
        $subtotal = $this->input->post('subtotal');
        
        if (!$cep || !$subtotal) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Dados inválidos']));
            return;
        }
        
        $shipping_cost = $this->calculate_shipping_cost($subtotal);
        
        // Armazenar na sessão
        $this->session->set_userdata([
            'shipping_cep' => $cep,
            'shipping_cost' => $shipping_cost,
            'shipping_subtotal' => $subtotal
        ]);
        
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode([
                         'success' => true,
                         'shipping_cost' => $shipping_cost
                     ]));
    }
    
    /**
     * Armazenar dados de cupom na sessão
     */
    public function store_coupon_data() {
        $coupon_code = $this->input->post('coupon_code');
        $subtotal = $this->input->post('subtotal');
        
        if (!$coupon_code || !$subtotal) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Dados inválidos']));
            return;
        }
        
        $coupon_result = $this->validate_coupon_internal($coupon_code, $subtotal);
        
        if ($coupon_result['valid']) {
            // Armazenar na sessão
            $this->session->set_userdata([
                'coupon_code' => $coupon_code,
                'coupon_discount' => $coupon_result['discount'],
                'coupon_subtotal' => $subtotal
            ]);
            
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                             'success' => true,
                             'valid' => true,
                             'discount' => $coupon_result['discount'],
                             'discount_formatted' => 'R$ ' . number_format($coupon_result['discount'], 2, ',', '.'),
                             'coupon' => $coupon_result['coupon']
                         ]));
        } else {
            // Limpar cupom da sessão se inválido
            $this->session->unset_userdata(['coupon_code', 'coupon_discount', 'coupon_subtotal']);
            
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode([
                             'success' => true,
                             'valid' => false,
                             'message' => $coupon_result['message']
                         ]));
        }
    }
    
    /**
     * Remover cupom da sessão
     */
    public function remove_coupon() {
        $this->session->unset_userdata(['coupon_code', 'coupon_discount', 'coupon_subtotal']);
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode(['success' => true]));
    }
    
    /**
     * Obter dados de checkout da sessão
     */
    public function get_checkout_data() {
        $cart = $this->session->userdata('cart') ?? [];
        
        if (empty($cart)) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Carrinho vazio']));
            return;
        }
        
        
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // Obter dados da sessão
        $shipping_cost = $this->session->userdata('shipping_cost') ?? $this->calculate_shipping_cost($subtotal);
        $coupon_discount = $this->session->userdata('coupon_discount') ?? 0;
        $coupon_code = $this->session->userdata('coupon_code') ?? '';
        
        // Verificar se o cupom ainda é válido com o subtotal atual
        if ($coupon_code && $this->session->userdata('coupon_subtotal') != $subtotal) {
            $coupon_result = $this->validate_coupon_internal($coupon_code, $subtotal);
            if ($coupon_result['valid']) {
                $coupon_discount = $coupon_result['discount'];
                $this->session->set_userdata([
                    'coupon_discount' => $coupon_discount,
                    'coupon_subtotal' => $subtotal
                ]);
            } else {
                // Cupom não é mais válido, remover da sessão
                $this->session->unset_userdata(['coupon_code', 'coupon_discount', 'coupon_subtotal']);
                $coupon_discount = 0;
                $coupon_code = '';
            }
        }
        
        $total = $subtotal + $shipping_cost - $coupon_discount;
        
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode([
                         'success' => true,
                         'subtotal' => $subtotal,
                         'shipping_cost' => $shipping_cost,
                         'coupon_discount' => $coupon_discount,
                         'coupon_code' => $coupon_code,
                         'total' => $total,
                         'subtotal_formatted' => 'R$ ' . number_format($subtotal, 2, ',', '.'),
                         'shipping_formatted' => $shipping_cost == 0 ? 'Grátis' : 'R$ ' . number_format($shipping_cost, 2, ',', '.'),
                         'discount_formatted' => 'R$ ' . number_format($coupon_discount, 2, ',', '.'),
                         'total_formatted' => 'R$ ' . number_format($total, 2, ',', '.')
                     ]));
    }

    /**
     * Enviar e-mail de confirmação do pedido
     */
    private function send_order_confirmation_email($order_data, $cart_items) {
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
            
            // Preparar dados para o template
            $order = (object) [
                'id' => $order_data['id'] ?? 'N/A',
                'customer_name' => $order_data['customer_name'],
                'customer_email' => $order_data['customer_email'],
                'customer_phone' => $order_data['customer_phone'],
                'customer_cep' => $order_data['customer_cep'],
                'customer_address' => $order_data['customer_address'],
                'customer_number' => $order_data['customer_number'],
                'customer_complement' => $order_data['customer_complement'] ?? '',
                'customer_neighborhood' => $order_data['customer_neighborhood'],
                'customer_city' => $order_data['customer_city'],
                'customer_state' => $order_data['customer_state'],
                'subtotal' => $order_data['subtotal'],
                'shipping' => $order_data['shipping_cost'] ?? $order_data['shipping'] ?? 0,
                'discount' => $order_data['discount_amount'] ?? $order_data['discount'] ?? 0,
                'total' => $order_data['total'],
                'created_at' => $order_data['created_at'] ?? date('Y-m-d H:i:s')
            ];
            
            // Preparar itens com estrutura correta
            $items = [];
            foreach ($cart_items as $item) {
                $items[] = [
                    'name' => $item['product_name'] ?? $item['name'],
                    'variation' => $item['variation'] ?? '',
                    'quantity' => $item['quantity'],
                    'price' => $item['unit_price'] ?? $item['price'],
                    'total' => ($item['unit_price'] ?? $item['price']) * $item['quantity']
                ];
            }
            
            $message = $this->load->view('emails/order_confirmation', [
                'order_id' => $order_data['id'],
                'order' => $order,
                'items' => $items
            ], true);

            $this->email->clear();
            $this->email->from('noreply@montink.com', 'Montink');
            $this->email->to($order_data['customer_email']);
            $this->email->subject('Confirmação do Pedido #' . $order_data['id']);
            $this->email->message($message);
            
            $result = $this->email->send();
            
            if ($result) {
                log_message('info', 'E-mail de confirmação enviado para: ' . $order_data['customer_email']);
            } else {
                // Falha ao enviar e-mail de confirmação
            }
            
            return $result;
        } catch (Exception $e) {
            log_message('error', 'Erro de email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Validar cupom via AJAX
     */
    public function validate_coupon() {
        $code = $this->input->post('code');
        $subtotal = $this->input->post('subtotal');

        if (!$code || !$subtotal) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['valid' => false, 'message' => 'Dados inválidos']));
            return;
        }

        $result = $this->validate_coupon_internal($code, $subtotal);
        
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode($result));
    }
    
    /**
     * Método interno para validar cupom
     */
    private function validate_coupon_internal($coupon_code, $subtotal) {
        // Usar o modelo de cupom para validação real
        $result = $this->Coupon_model->validate_coupon($coupon_code, $subtotal);
        
        if (!$result['valid']) {
            return [
                'valid' => false,
                'message' => $result['message']
            ];
        }
        
        $coupon = $result['coupon'];
        $discount = $this->Coupon_model->calculate_discount($coupon, $subtotal);
        
        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount' => $discount,
            'discount_formatted' => 'R$ ' . number_format($discount, 2, ',', '.')
        ];
    }

    public function get_stock($product_id = null) {
        if (!$product_id) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'ID do produto é obrigatório']));
            return;
        }

        $stock_items = $this->db->where('product_id', $product_id)->get('stock')->result();
        
        // Converte as quantidades para números inteiros
        foreach ($stock_items as $stock) {
            $stock->quantity = (int)$stock->quantity;
        }
        
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode([
                         'success' => true,
                         'stock' => $stock_items
                     ]));
    }

    public function debug_stock($product_id = null) {
        if (!$product_id) {
            echo "ID do produto é obrigatório";
            return;
        }

        
        
        // Busca o produto
        $product = $this->db->where('id', $product_id)->get('products')->row();
        if ($product) {
            echo "<p><strong>Produto:</strong> {$product->name}</p>";
        }
        
        // Busca o estoque
        $stock_items = $this->db->where('product_id', $product_id)->get('stock')->result();
        echo "<h4>Estoque:</h4>";
        echo "<ul>";
        $total = 0;
        foreach ($stock_items as $stock) {
            $quantity = (int)$stock->quantity;
            $total += $quantity;
            echo "<li>Variação: {$stock->variation} - Quantidade: {$stock->quantity} (tipo: " . gettype($stock->quantity) . ")</li>";
        }
        echo "</ul>";
        echo "<p><strong>Total calculado:</strong> $total</p>";
        
        // Testa a função JavaScript
        echo "<h4>Teste JavaScript:</h4>";
        echo "<script>";
        echo "const stock = " . json_encode($stock_items) . ";";
        // Stock data loaded
        echo "const total = stock.reduce((sum, item) => sum + parseInt(item.quantity), 0);";
        
        echo "</script>";
        
        
        $product_with_stock = $product;
        $product_with_stock->stock = $stock_items;
        echo "<h4>Dados completos do produto (JSON):</h4>";
        echo "<pre>" . json_encode($product_with_stock, JSON_PRETTY_PRINT) . "</pre>";
    }

    public function delete($id = null) {
        if (!$id) {
            redirect('products');
        }

        // Remove o estoque primeiro (chave estrangeira)
        $this->db->where('product_id', $id)->delete('stock');
        
        // Remove o produto
        $this->db->where('id', $id)->delete('products');

        redirect('products');
    }

    public function calculate_shipping() {
        $input = $this->get_request_data();
        
        $cep = $input['cep'] ?? null;
        $subtotal = (float)($input['subtotal'] ?? 0);

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
    
    /**
     * Reduzir estoque dos produtos vendidos
     */
    private function reduce_stock($cart_items) {
        foreach ($cart_items as $item) {
            $product_id = $item['product_id'];
            $variation = $item['variation'];
            $quantity_sold = $item['quantity'];
            
            // Buscar o item de estoque específico
            $stock_item = $this->db->where('product_id', $product_id)
                                  ->where('variation', $variation)
                                  ->get('stock')
                                  ->row();
            
            if ($stock_item) {
                $new_quantity = max(0, $stock_item->quantity - $quantity_sold);
                
                // Atualizar o estoque
                $this->db->where('product_id', $product_id)
                         ->where('variation', $variation)
                         ->update('stock', ['quantity' => $new_quantity]);
                
                log_message('info', "Estoque reduzido - Produto: {$product_id}, Variação: {$variation}, Quantidade vendida: {$quantity_sold}, Novo estoque: {$new_quantity}");
            } else {
                log_message('error', "Item de estoque não encontrado - Produto: {$product_id}, Variação: {$variation}");
            }
        }
    }
    
    /**
     * Aumenta o estoque de um produto específico
     */
    private function increase_stock($product_id, $variation, $quantity) {
        // Buscar o item de estoque específico
        $stock_item = $this->db->where('product_id', $product_id)
                              ->where('variation', $variation)
                              ->get('stock')
                              ->row();
        
        if ($stock_item) {
            $new_quantity = $stock_item->quantity + $quantity;
            
            // Atualizar o estoque
            $this->db->where('product_id', $product_id)
                     ->where('variation', $variation)
                     ->update('stock', ['quantity' => $new_quantity]);
            
            log_message('info', "Estoque aumentado - Produto: {$product_id}, Variação: {$variation}, Quantidade devolvida: {$quantity}, Novo estoque: {$new_quantity}");
        } else {
            log_message('error', "Item de estoque não encontrado para devolução - Produto: {$product_id}, Variação: {$variation}");
        }
    }
}
