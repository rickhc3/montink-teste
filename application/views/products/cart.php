<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho - Montink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://unpkg.com/imask"></script>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --light-gray: #f8f9fa;
            --medium-gray: #6c757d;
            --border-color: #dee2e6;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 1.5rem;
        }
        
        .page-title {
            font-weight: 300;
            font-size: 1.8rem;
            margin: 0;
        }
        
        .page-title i {
            margin-right: 0.5rem;
            opacity: 0.9;
        }
        
        .continue-shopping {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }
        
        .continue-shopping:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
        }
        
        .empty-cart {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--medium-gray);
        }
        
        .empty-cart i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .product-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 15px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .product-item {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .product-item:last-child {
            border-bottom: none;
        }
        
        .product-name {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }
        
        .product-variation {
            color: var(--medium-gray);
            font-size: 0.9rem;
        }
        
        .quantity-badge {
            background: linear-gradient(135deg, var(--accent-color), #2980b9);
            color: white;
            border-radius: 20px;
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .price-text {
            font-weight: 700;
            color: var(--success-color);
            font-size: 1.1rem;
        }
        
        .remove-btn {
            background: var(--danger-color);
            border: none;
            color: white;
            border-radius: 4px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .remove-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }
        
        .summary-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 15px;
            overflow: hidden;
            position: sticky;
            top: 2rem;
        }
        
        .summary-header {
            background: linear-gradient(135deg, var(--light-gray), #e9ecef);
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .summary-body {
            padding: 1rem;
        }
        
        .cep-input-group {
            position: relative;
            margin-bottom: 1rem;
        }
        
        .cep-input {
            border-radius: 4px;
            border: 1px solid var(--border-color);
            padding: 0.5rem 0.75rem;
            transition: all 0.3s ease;
        }
        
        .cep-input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .cep-btn {
            border-radius: 4px;
            background: var(--accent-color);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .cep-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .address-info {
            background: var(--light-gray);
            border-radius: 10px;
            padding: 0.75rem;
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: var(--medium-gray);
        }
        
        .summary-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            padding: 0.25rem 0;
        }
        
        .summary-total {
            border-top: 2px solid var(--border-color);
            padding-top: 1rem;
            margin-top: 1rem;
        }
        
        .total-amount {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--success-color);
        }
        
        .checkout-btn {
            background: var(--success-color);
            border: none;
            color: white;
            border-radius: 4px;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 0.75rem;
        }
        
        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.3);
        }
        
        .clear-cart-btn {
            background: transparent;
            border: 2px solid var(--danger-color);
            color: var(--danger-color);
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .clear-cart-btn:hover {
            background: var(--danger-color);
            color: white;
            transform: translateY(-1px);
        }
        
        .section-header {
            background: var(--light-gray);
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--primary-color);
        }
        
        @media (max-width: 768px) {
            .main-container {
                margin: 1rem;
                border-radius: 15px;
            }
            
            .page-header {
                padding: 1.5rem;
                border-radius: 15px 15px 0 0;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
            
            .product-item {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="main-container">
                    <div class="page-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="page-title">
                                <i class="bi bi-cart3"></i> Carrinho de Compras
                            </h1>
                            <a href="<?= base_url('products') ?>" class="continue-shopping">
                                <i class="bi bi-arrow-left"></i> Continuar Comprando
                            </a>
                        </div>
                    </div>

                    <div class="p-4">
                        <?php if (empty($cart)): ?>
                            <div class="empty-cart">
                                <i class="bi bi-cart-x"></i>
                                <h3 class="mt-3 mb-3">Seu carrinho está vazio</h3>
                                <p class="mb-4">Descubra nossos produtos incríveis e comece suas compras!</p>
                                <a href="<?= base_url('products') ?>" class="btn btn-primary btn-lg" style="border-radius: 50px; padding: 1rem 2rem;">
                                    <i class="bi bi-shop"></i> Explorar Produtos
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <!-- Lista de Produtos -->
                                <div class="col-lg-8 mb-4">
                                    <div class="product-card">
                                        <div class="section-header">
                                            <i class="bi bi-bag-check me-2"></i>Produtos Selecionados
                                        </div>
                                        <div class="card-body p-0">
                                            <?php 
                                            $subtotal = 0;
                                            foreach ($cart as $key => $item): 
                                                $subtotal += $item['price'] * $item['quantity'];
                                            ?>
                                                <div class="product-item">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-6">
                                                            <h6 class="product-name"><?= htmlspecialchars($item['name']) ?></h6>
                                                            <div class="product-variation">
                                                                <i class="bi bi-tag me-1"></i><?= htmlspecialchars($item['variation']) ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 text-center">
                                                            <span class="quantity-badge">
                                                                <i class="bi bi-box me-1"></i><?= $item['quantity'] ?>
                                                            </span>
                                                        </div>
                                                        <div class="col-md-3 text-end">
                                                            <div class="price-text">R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></div>
                                                        </div>
                                                        <div class="col-md-1 text-end">
                                                            <a href="<?= base_url('products/remove_from_cart/' . $key) ?>" 
                                                               class="remove-btn"
                                                               onclick="return confirm('Remover este item do carrinho?')"
                                                               title="Remover item">
                                                                <i class="bi bi-trash"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Resumo e Frete -->
                                <div class="col-lg-4">
                                    <div class="summary-card">
                                        <div class="summary-header">
                                            <i class="bi bi-calculator me-2"></i>Resumo do Pedido
                                        </div>
                                        <div class="summary-body">
                                            <div class="mb-4">
                                                <label class="form-label fw-bold mb-3">
                                                    <i class="bi bi-geo-alt me-2"></i>Calcular Frete
                                                </label>
                                                <div class="cep-input-group">
                                                    <div class="input-group">
                                                        <input type="text" id="cep" class="form-control cep-input" placeholder="Digite seu CEP" maxlength="9">
                                                        <button type="button" onclick="calculateShipping()" class="btn cep-btn">
                                                            <i class="bi bi-search"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="address-info" class="address-info" style="display: none;">
                                                    <i class="bi bi-house me-2"></i>
                                                    <span id="address-text"></span>
                                                </div>
                                            </div>

                                            <div class="summary-line">
                                                <span>Subtotal:</span>
                                                <strong>R$ <?= number_format($subtotal, 2, ',', '.') ?></strong>
                                            </div>
                                            <div class="summary-line">
                                                <span>Frete:</span>
                                                <strong id="shipping-cost">A calcular</strong>
                                            </div>
                                            
                                            <div class="summary-total">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold">Total:</span>
                                                    <span class="total-amount" id="total-cost">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <button type="button" onclick="finalizeOrder()" class="checkout-btn">
                                                    <i class="bi bi-credit-card me-2"></i>Finalizar Compra
                                                </button>
                                                <button type="button" onclick="clearCart()" class="clear-cart-btn">
                                                    <i class="bi bi-trash me-2"></i>Limpar Carrinho
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="toast-title">Notificação</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toast-message">
                Mensagem do toast
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para mostrar toast
        function showToast(title, message, type = 'info') {
            const toast = document.getElementById('toast');
            const toastTitle = document.getElementById('toast-title');
            const toastMessage = document.getElementById('toast-message');
            
            const colors = {
                'success': 'text-success',
                'error': 'text-danger',
                'warning': 'text-warning',
                'info': 'text-info'
            };
            
            toastTitle.textContent = title;
            toastTitle.className = `me-auto ${colors[type] || colors.info}`;
            toastMessage.textContent = message;
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }

        // Máscara para CEP
        const cepInput = document.getElementById('cep');
        const cepMask = IMask(cepInput, {
            mask: '00000-000'
        });

        function calculateShipping() {
            const cep = cepInput.value.replace(/\D/g, '');
            
            if (cep.length !== 8) {
                showToast('Erro', 'Digite um CEP válido com 8 dígitos', 'error');
                return;
            }

            // Mostra loading
            document.getElementById('shipping-cost').innerHTML = '<i class="bi bi-hourglass-split"></i> Calculando...';

            const subtotal = <?= $subtotal ?>;
            
            // Chama o backend para calcular frete
            fetch('<?= base_url("products/calculate_shipping") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    cep: cep,
                    subtotal: subtotal
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostra endereço se disponível
                    if (data.cep_data) {
                        document.getElementById('address-text').textContent = 
                            `${data.cep_data.logradouro}, ${data.cep_data.bairro}, ${data.cep_data.localidade} - ${data.cep_data.uf}`;
                        document.getElementById('address-info').style.display = 'block';
                    }

                    // Atualiza valores
                    document.getElementById('shipping-cost').innerHTML = 
                        data.shipping === 0 ? '<span class="text-success"><i class="bi bi-gift"></i> Grátis</span>' : `R$ ${data.shipping.toFixed(2).replace('.', ',')}`;
                    document.getElementById('total-cost').textContent = 
                        `R$ ${data.total.toFixed(2).replace('.', ',')}`;
                    
                    showToast('Sucesso', 'Frete calculado com sucesso!', 'success');
                } else {
                    showToast('Erro', 'CEP não encontrado', 'error');
                    document.getElementById('shipping-cost').textContent = 'A calcular';
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro', 'Erro ao calcular frete. Tente novamente.', 'error');
                document.getElementById('shipping-cost').textContent = 'A calcular';
            });
        }

        function finalizeOrder() {
            if (!confirm('Confirma a finalização do pedido?')) {
                return;
            }

            fetch('<?= base_url("products/finalize_order") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Sucesso', data.message, 'success');
                    setTimeout(() => {
                        window.location.href = '<?= base_url("products") ?>';
                    }, 2000);
                } else {
                    showToast('Erro', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro', 'Erro ao finalizar pedido. Tente novamente.', 'error');
            });
        }

        function clearCart() {
            if (!confirm('Tem certeza que deseja limpar todo o carrinho?')) {
                return;
            }

            fetch('<?= base_url("products/clear_cart") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.ok) {
                    showToast('Sucesso', 'Carrinho limpo com sucesso!', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showToast('Erro', 'Erro ao limpar carrinho', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro', 'Erro ao limpar carrinho. Tente novamente.', 'error');
            });
        }

        // Calcula frete ao pressionar Enter no CEP
        cepInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                calculateShipping();
            }
        });

        // Animação suave ao carregar
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.product-item');
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    el.style.transition = 'all 0.5s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>