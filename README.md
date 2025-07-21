# 🛍️ Montink - Sistema de E-commerce

Sistema de e-commerce desenvolvido em **CodeIgniter 3** com **Vue.js** e **Bootstrap 5**.

## ✨ Funcionalidades

- **Produtos**: CRUD completo com variações e controle de estoque
- **Carrinho**: Cálculo automático de frete baseado no subtotal
- **Cupons**: Sistema com validação de regras e valor mínimo
- **CEP**: Integração com ViaCEP para preenchimento automático
- **E-mails**: Confirmação automática de pedidos
- **Webhook**: Atualizações de status em tempo real

## 🛠️ Tecnologias

- **Backend**: PHP 7.4+, CodeIgniter 3, MySQL 8.0
- **Frontend**: Vue.js 3, Bootstrap 5, IMask
- **Infraestrutura**: Docker, Docker Compose, Mailpit

## 🗄️ Banco de Dados

### Tabelas Principais

#### `products`
```sql
id (INT, PK, AUTO_INCREMENT)
name (VARCHAR(255))
price (DECIMAL(10,2))
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

#### `stock`
```sql
id (INT, PK, AUTO_INCREMENT)
product_id (INT, FK)
variation (VARCHAR(255))
quantity (INT)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

#### `coupons`
```sql
id (INT, PK, AUTO_INCREMENT)
code (VARCHAR(50), UNIQUE)
discount_type (ENUM: 'percentage', 'fixed')
discount_value (DECIMAL(10,2))
min_amount (DECIMAL(10,2))
max_uses (INT)
used_count (INT, DEFAULT 0)
valid_from (DATE)
valid_until (DATE)
is_active (BOOLEAN, DEFAULT 1)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

#### `orders`
```sql
id (INT, PK, AUTO_INCREMENT)
customer_name (VARCHAR(255))
customer_email (VARCHAR(255))
customer_phone (VARCHAR(20))
shipping_address (TEXT)
shipping_city (VARCHAR(100))
shipping_state (VARCHAR(50))
shipping_zipcode (VARCHAR(10))
subtotal (DECIMAL(10,2))
shipping_cost (DECIMAL(10,2))
discount_amount (DECIMAL(10,2))
total (DECIMAL(10,2))
coupon_code (VARCHAR(50))
status (ENUM: 'pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled')
created_at (TIMESTAMP)
```

### Tabelas Auxiliares

#### `order_items`
```sql
id (INT, PK, AUTO_INCREMENT)
order_id (INT, FK)
product_id (INT, FK)
variation (VARCHAR(255))
quantity (INT)
price (DECIMAL(10,2))
created_at (TIMESTAMP)
```

#### `order_status_history`
```sql
id (INT, PK, AUTO_INCREMENT)
order_id (INT, FK)
status (VARCHAR(50))
changed_at (TIMESTAMP)
```

#### `webhook_logs`
```sql
id (INT, PK, AUTO_INCREMENT)
order_id (INT)
status (VARCHAR(50))
payload (TEXT)
response (TEXT)
created_at (TIMESTAMP)
```

## 🐳 Instalação

```bash
git clone <url-do-repositorio>
cd montink
cp .env.example .env
docker-compose up -d
```

**Acesso**: http://localhost:8080  
**E-mails**: http://localhost:8025

## 📖 Como Usar

1. **Produtos**: Acesse `/` para ver catálogo, clique "Novo Produto" para adicionar
2. **Cupons**: Acesse `/coupons` para gerenciar cupons de desconto
3. **Compra**: Adicione ao carrinho → Informe CEP → Aplique cupom → Finalize
4. **Webhook**: `POST /webhook/order_status` com `{"order_id": 123, "status": "shipped"}`

## 🔌 API Principais

- `POST /products/add_to_cart` - Adicionar ao carrinho
- `GET /cart` - Visualizar carrinho  
- `POST /coupons/validate` - Validar cupom
- `POST /products/calculate_shipping` - Calcular frete
- `POST /webhook/order_status` - Webhook de status

## 📁 Estrutura Detalhada

```
montink/
├── application/
│   ├── controllers/
│   │   ├── Products.php      # Gerenciamento de produtos e carrinho
│   │   ├── Coupons.php       # CRUD de cupons de desconto
│   │   ├── Webhook.php       # Processamento de webhooks

│   ├── models/
│   │   ├── Order_model.php   # Pedidos, itens e histórico
│   │   └── Coupon_model.php  # Cupons e validações
│   ├── views/
│   │   ├── products/
│   │   │   ├── index.php     # Catálogo de produtos
│   │   │   ├── create.php    # Formulário de criação
│   │   │   ├── edit.php      # Formulário de edição
│   │   │   ├── cart.php      # Carrinho de compras
│   │   │   └── checkout.php  # Finalização de compra
│   │   ├── coupons/
│   │   │   ├── index.php     # Lista de cupons
│   │   │   ├── create.php    # Criar cupom
│   │   │   └── edit.php      # Editar cupom
│   │   ├── emails/
│   │   │   └── order_confirmation.php  # Template de confirmação
│   │   ├── components/
│   │   │   ├── ProductForm.php         # Formulário de produto
│   │   │   ├── ProductModal.php        # Modal de produto
│   │   │   ├── ProductsTable.php       # Tabela de produtos
│   │   │   └── register_components.php # Registro Vue.js
│   │   ├── layouts/
│   │   │   ├── header.php    # Cabeçalho comum
│   │   │   └── footer.php    # Rodapé comum
│   │   └── errors/           # Páginas de erro
│   └── config/
│       ├── database.php      # Configuração do banco
│       ├── routes.php        # Rotas da aplicação
│       └── config.php        # Configurações gerais
├── docker/
│   └── mysql/
│       └── init.sql          # Schema e dados iniciais
├── docker-compose.yml        # Orquestração de containers
├── Dockerfile               # Imagem da aplicação
└── README.md               # Documentação
```

## 📧 Sistema de E-mails

### Template de Confirmação
- **Arquivo**: `application/views/emails/order_confirmation.php`
- **Funcionalidades**:
  - Design responsivo com HTML/CSS inline
  - Detalhes completos do pedido (produtos, quantidades, preços)
  - Informações do cliente e endereço de entrega
  - Resumo financeiro (subtotal, frete, desconto, total)
  - Suporte a imagens incorporadas
  - Layout profissional com cores e tipografia moderna

### Configuração SMTP
- **Servidor**: Mailpit (desenvolvimento)
- **Host**: `mailpit` (container Docker)
- **Porta**: `1025`
- **Interface Web**: http://localhost:8025
- **Encoding**: UTF-8 com suporte a caracteres especiais

### Envio Automático
- **Trigger**: Finalização de pedido
- **Dados**: Informações completas do pedido e cliente
- **Logs**: Registro de envios no sistema
- **Fallback**: Tratamento de erros de envio

---

**Sistema de e-commerce completo com CodeIgniter 3, Vue.js e Bootstrap 5**