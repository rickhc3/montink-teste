<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
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
            
            // Debug: log dos dados do produto
            log_message('debug', 'Produto ID: ' . $product->id . ', Nome: ' . $product->name . ', Stock: ' . json_encode($stock_items));
            
            // Verifica se o ID está presente
            if (!isset($product->id) || empty($product->id)) {
                log_message('error', 'ERRO: Produto sem ID - ' . json_encode($product));
            }
        }
        
        $data['products'] = $products;
        $this->load->view('products/index', $data);
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

        echo "<h3>Debug do Produto ID: $product_id</h3>";
        
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
        echo "console.log('Stock data:', stock);";
        echo "const total = stock.reduce((sum, item) => sum + parseInt(item.quantity), 0);";
        echo "console.log('Total calculado:', total);";
        echo "</script>";
        
        // Debug completo do produto com estoque
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
}
