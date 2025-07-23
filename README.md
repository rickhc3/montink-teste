# 🛍️ Montink

Projeto desenvolvido em **CodeIgniter 3** com **Vue.js** e **Bootstrap 5**.

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

## 🐳 Instalação e Configuração

### Opção 1: Docker (Recomendado)

#### Pré-requisitos
- Docker
- Docker Compose

#### Passos para instalação

1. **Clone o repositório**
```bash
git clone <url-do-repositorio>
cd montink
```

2. **Configure o ambiente**
```bash
cp .env.example .env
# Edite o .env conforme necessário
```

3. **Suba os containers**
```bash
docker-compose up -d
```

4. **Aguarde a inicialização**
```bash
# Verifique se todos os containers estão rodando
docker-compose ps

# Acompanhe os logs se necessário
docker-compose logs -f
```

#### Serviços disponíveis
- **Aplicação**: http://localhost:8080
- **PHPMyAdmin**: http://localhost:8081
- **Mailpit (E-mails)**: http://localhost:8025
- **MySQL**: localhost:3306

#### Comandos úteis

```bash
# Parar os containers
docker-compose down

# Parar e remover volumes (limpar banco)
docker-compose down -v

# Reconstruir containers
docker-compose up -d --build

# Ver logs de um serviço específico
docker-compose logs app
docker-compose logs mysql

# Acessar container da aplicação
docker-compose exec app bash

# Acessar MySQL
docker-compose exec mysql mysql -u root -p
```

### Opção 2: Instalação Manual

#### Pré-requisitos
- PHP 7.4+
- MySQL 8.0+ ou SQLite
- Composer
- Servidor web (Apache/Nginx)

#### Configuração do banco de dados

**Para MySQL:**
```bash
# 1. Crie o banco de dados
mysql -u root -p
CREATE DATABASE montink;
USE montink;

# 2. Execute o script de inicialização
source docker/mysql/init.sql;
```

**Para SQLite:**
```bash
# 1. Crie o banco SQLite
sqlite3 database/montink.db < init_sqlite.sql

# 2. Configure as permissões
chmod 664 database/montink.db
chmod 775 database/
```

#### Configuração da aplicação

1. **Configure o banco no CodeIgniter**
```php
// application/config/database.php

// Para MySQL
$db['default'] = array(
    'dsn' => '',
    'hostname' => 'localhost',
    'username' => 'seu_usuario',
    'password' => 'sua_senha',
    'database' => 'montink',
    'dbdriver' => 'mysqli',
    // ... outras configurações
);

// Para SQLite
$db['default'] = array(
    'dsn' => '',
    'hostname' => '',
    'username' => '',
    'password' => '',
    'database' => FCPATH . 'database/montink.db',
    'dbdriver' => 'sqlite3',
    // ... outras configurações
);
```

2. **Configure o servidor web**
```bash
# Para desenvolvimento com PHP built-in
php -S localhost:8000

# Para Apache, configure o DocumentRoot para a pasta do projeto
# Para Nginx, configure o root e try_files adequadamente
```

3. **Configure permissões**
```bash
chmod -R 755 application/cache/
chmod -R 755 application/logs/
```

#### Scripts de banco disponíveis

- **`docker/mysql/init.sql`**: Schema completo para MySQL com dados de exemplo
- **`init_sqlite.sql`**: Schema completo para SQLite com dados de exemplo

#### Dados de exemplo incluídos

**Produtos:**
- Camiseta Básica (R$ 29,90)
- Calça Jeans (R$ 89,90)
- Tênis Esportivo (R$ 159,90)
- Jaqueta de Couro (R$ 299,90)
- Vestido Floral (R$ 79,90)

**Cupons:**
- `BEMVINDO10`: 10% de desconto
- `ECONOMIZE20`: R$ 20,00 de desconto
- `PRIMEIRO15`: 15% de desconto
- `MEGA30`: 30% de desconto
- `FRETE5`: R$ 5,00 de desconto

### Configurações avançadas

**Variáveis de ambiente (.env):**
```bash
# Banco de dados
DB_HOST=mysql
DB_NAME=montink
DB_USER=root
DB_PASS=root123

# E-mail
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_FROM=noreply@montink.com

# Aplicação
APP_URL=http://localhost:8080
APP_ENV=development
```

**Configuração de produção:**
```bash
# 1. Altere as senhas padrão
# 2. Configure SSL/HTTPS
# 3. Ajuste limites de memória PHP
# 4. Configure backup automático
# 5. Monitore logs de erro
```

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

### Templates Disponíveis

#### 1. E-mail de Confirmação de Pedido
- **Arquivo**: `application/views/emails/order_confirmation.php`
- **Trigger**: Enviado automaticamente quando um pedido é finalizado
- **Funcionalidades**:
  - Design responsivo com HTML/CSS inline
  - Detalhes completos do pedido (produtos, quantidades, preços)
  - Informações do cliente e endereço de entrega
  - Resumo financeiro (subtotal, frete, desconto, total)
  - Layout profissional com cores e tipografia moderna

#### 2. E-mail de Atualização de Status
- **Arquivo**: `application/views/emails/order_status_update.php`
- **Trigger**: Enviado via webhook quando status do pedido é atualizado
- **Status suportados**: shipped, delivered
- **Funcionalidades**:
  - Notificação de mudança de status
  - Informações de rastreamento (quando aplicável)
  - Layout consistente com template de confirmação

#### 3. E-mail de Cancelamento
- **Arquivo**: `application/views/emails/order_cancellation.php`
- **Trigger**: Enviado via webhook quando pedido é cancelado
- **Funcionalidades**:
  - Notificação de cancelamento
  - Motivo do cancelamento (quando fornecido)
  - Informações sobre reembolso
  - Suporte ao cliente

### Configuração SMTP
- **Template Engine**: PHP com HTML/CSS inline
- **Servidor**: Mailpit (desenvolvimento)
- **Host**: `mailpit` (container Docker)
- **Porta**: `1025`
- **Interface Web**: http://localhost:8025
- **Encoding**: UTF-8 com suporte completo a caracteres especiais
- **Design**: Responsivo e compatível com principais clientes de e-mail

### Screenshots dos E-mails
Para visualizar os templates de e-mail em funcionamento, consulte a documentação visual em:

📁 **[docs/email-screenshots/README.md](docs/email-screenshots/README.md)**

Esta documentação apresenta:
- Screenshots de todos os templates de e-mail (confirmação, envio, entrega, cancelamento)
- Características e funcionalidades de cada template
- Informações técnicas sobre o sistema de e-mails
- Localização dos arquivos de template

### Envio Automático
- **Trigger**: Finalização de pedido
- **Dados**: Informações completas do pedido e cliente
- **Logs**: Registro de envios no sistema
- **Fallback**: Tratamento de erros de envio



---