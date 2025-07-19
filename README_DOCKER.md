# CodeIgniter 3 com Docker e Variáveis de Ambiente

Este projeto está configurado para usar CodeIgniter 3 com Docker e variáveis de ambiente para configuração do banco de dados.

## Configuração das Variáveis de Ambiente

### Arquivo .env
O projeto usa o arquivo `.env` para configurar as variáveis de ambiente. Este arquivo já está configurado com as seguintes variáveis:

```env
# Configurações do Ambiente
CI_ENV=development

# Configurações do Banco de Dados
DB_HOST=db
DB_USERNAME=user
DB_PASSWORD=password
DB_DATABASE=montink

# Configurações da Aplicação
APP_ENV=development
APP_DEBUG=true
```

### Docker Compose
O `docker-compose.yml` está configurado para passar as variáveis de ambiente para o container da aplicação:

```yaml
app:
    environment:
        - CI_ENV=development
        - DB_HOST=db
        - DB_USERNAME=user
        - DB_PASSWORD=password
        - DB_DATABASE=montink
```

## Como Executar

1. **Construir e iniciar os containers:**
   ```bash
   docker-compose up --build
   ```

2. **Acessar a aplicação:**
   - Aplicação: http://localhost:8080
   - PHPMyAdmin: http://localhost:8081

3. **Parar os containers:**
   ```bash
   docker-compose down
   ```

## Configuração do Banco de Dados

O arquivo `application/config/database.php` está configurado para usar as variáveis de ambiente:

```php
$db['default'] = array(
    'hostname' => getenv('DB_HOST') ?: 'localhost',
    'username' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'database' => getenv('DB_DATABASE') ?: 'montink',
    // ... outras configurações
);
```

## Estrutura dos Containers

- **app**: Container da aplicação PHP/Apache
- **db**: Container MySQL 5.7
- **phpmyadmin**: Interface web para gerenciar o banco de dados

## Solução de Problemas

### Variáveis de ambiente não carregadas
Se as variáveis de ambiente não estiverem sendo carregadas:

1. Verifique se o arquivo `.env` existe na raiz do projeto
2. Certifique-se de que o `docker-compose.yml` tem a seção `environment` configurada
3. Reconstrua os containers: `docker-compose up --build`

### Erro de conexão com o banco
Se houver erro de conexão com o banco de dados:

1. Verifique se o container `db` está rodando: `docker-compose ps`
2. Verifique os logs: `docker-compose logs db`
3. Certifique-se de que as credenciais no `.env` correspondem às do `docker-compose.yml`

### Erro de sessão (mkdir(): Invalid path)
Se aparecer erro relacionado a sessões:

1. O problema foi corrigido configurando o diretório de sessões em `/tmp/ci_sessions`
2. O Dockerfile foi atualizado para criar o diretório com permissões corretas
3. Reconstrua os containers: `docker-compose up --build`

## Desenvolvimento

Para desenvolvimento local sem Docker, você pode:

1. Copiar o arquivo `.env.example` para `.env`
2. Ajustar as configurações do banco de dados para seu ambiente local
3. Executar `php -S localhost:8000` na raiz do projeto

## Notas Importantes

- O arquivo `.env` está no `.gitignore` por segurança
- Use o arquivo `.env.example` como template para novos ambientes
- As variáveis de ambiente são carregadas automaticamente pelo `index.php` usando o `vlucas/phpdotenv`

## Correções Aplicadas

### Problema: Função base_url() não definida
**Solução:** Adicionado o helper `url` ao autoload do CodeIgniter:

```php
// application/config/autoload.php
$autoload['helper'] = array('url', 'form');
```

### Problema: base_url não configurada
**Solução:** Configurada a base_url para o ambiente Docker:

```php
// application/config/config.php
$config['base_url'] = 'http://localhost:8080/';
```

### Helpers Carregados
- `url`: Para funções como `base_url()`, `site_url()`, etc.
- `form`: Para funções de formulário do CodeIgniter

## Interface do Usuário

### Tailwind CSS
A aplicação usa o Tailwind CSS via CDN para uma interface moderna e responsiva:

```html
<script src="https://cdn.tailwindcss.com"></script>
```

### IMask
Para máscaras de entrada, a aplicação usa a biblioteca IMask:

```html
<script src="https://unpkg.com/imask"></script>
```

**Máscara de Preço:**
- Formato brasileiro: R$ 1.234,56
- Separação de milhares com ponto
- Vírgula como separador decimal
- Conversão automática para formato numérico no envio

**Máscaras Adicionais:**
- **CEP**: 00000-000 (formato automático)
- **CPF**: 000.000.000-00 (formato automático)
- **Telefone**: (00) 00000-0000 (formato automático)

### Sistema de Frete
- **Acima de R$ 200,00**: Frete grátis
- **Entre R$ 52,00 e R$ 166,59**: R$ 15,00
- **Outros valores**: R$ 20,00

### Integração ViaCEP
- Busca automática de endereços por CEP
- Autopreenchimento de campos de endereço
- Validação de CEP existente

### Páginas Disponíveis
- **Listagem de Produtos**: `/products` - Visualiza todos os produtos cadastrados
- **Criar Produto**: `/products/create` - Formulário para adicionar novos produtos
- **Editar/Comprar Produto**: `/products/edit/{id}` - Edita produto e permite compra
- **Carrinho de Compras**: `/products/cart` - Gerencia itens no carrinho
- **Checkout**: `/products/checkout` - Finalização da compra com dados de entrega

### Funcionalidades
- ✅ Formulário responsivo com validação
- ✅ Layout em grid para melhor organização
- ✅ Máscara de preço em formato brasileiro (R$ 1.234,56)
- ✅ Adição/remoção dinâmica de variações de produto
- ✅ Interface moderna com gradientes e sombras
- ✅ Tabela de listagem com hover effects
- ✅ Botões de ação com confirmação
- ✅ Formatação de preços em Real (R$)
- ✅ Data formatada em português
- ✅ **Sistema de Carrinho**: Gerenciamento de sessão para carrinho de compras
- ✅ **Controle de Estoque**: Verificação automática de disponibilidade
- ✅ **Cálculo de Frete**: Baseado no valor do pedido (R$ 15, R$ 20 ou grátis)
- ✅ **Validação de CEP**: Integração com ViaCEP para autopreenchimento
- ✅ **Máscaras de Entrada**: CEP, CPF e telefone formatados automaticamente
- ✅ **Edição de Produtos**: Atualização de dados e estoque na mesma tela 