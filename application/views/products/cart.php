<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho - Montink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://unpkg.com/imask"></script>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="card-title h2 mb-0">
                                <i class="bi bi-cart3 text-primary"></i> Carrinho de Compras
                            </h1>
                            <a href="<?= base_url('products') ?>" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left"></i> Continuar Comprando
                            </a>
                        </div>

                        <?php if (empty($cart)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-cart-x h1 text-muted"></i>
                                <h3 class="mt-3">Seu carrinho está vazio</h3>
                                <p class="text-muted">Adicione produtos para começar suas compras!</p>
                                <a href="<?= base_url('products') ?>" class="btn btn-primary">
                                    <i class="bi bi-shop"></i> Ver Produtos
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <!-- Lista de Produtos -->
                                <div class="col-lg-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">Produtos no Carrinho</h5>
                                        </div>
                                        <div class="card-body">
                                            <?php 
                                            $subtotal = 0;
                                            foreach ($cart as $key => $item): 
                                                $subtotal += $item['price'] * $item['quantity'];
                                            ?>
                                                <div class="row align-items-center mb-3 p-3 border rounded">
                                                    <div class="col-md-6">
                                                        <h6 class="mb-1"><?= htmlspecialchars($item['name']) ?></h6>
                                                        <small class="text-muted">Variação: <?= htmlspecialchars($item['variation']) ?></small>
                                                    </div>
                                                    <div class="col-md-2 text-center">
                                                        <span class="badge bg-secondary">Qtd: <?= $item['quantity'] ?></span>
                                                    </div>
                                                    <div class="col-md-2 text-end">
                                                        <strong class="text-success">R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></strong>
                                                    </div>
                                                    <div class="col-md-2 text-end">
                                                        <a href="<?= base_url('products/remove_from_cart/' . $key) ?>" 
                                                           class="btn btn-outline-danger btn-sm"
                                                           onclick="return confirm('Remover este item?')">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Resumo e Frete -->
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0">Resumo do Pedido</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">CEP para Frete</label>
                                                <div class="input-group">
                                                    <input type="text" id="cep" class="form-control" placeholder="00000-000" maxlength="9">
                                                    <button type="button" onclick="calculateShipping()" class="btn btn-outline-primary">
                                                        <i class="bi bi-search"></i>
                                                    </button>
                                                </div>
                                                <div id="address-info" class="mt-2" style="display: none;">
                                                    <small class="text-muted" id="address-text"></small>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Subtotal:</span>
                                                <strong>R$ <?= number_format($subtotal, 2, ',', '.') ?></strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Frete:</span>
                                                <strong id="shipping-cost">-</strong>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between mb-3">
                                                <span class="fw-bold">Total:</span>
                                                <strong class="text-success fs-5" id="total-cost">R$ <?= number_format($subtotal, 2, ',', '.') ?></strong>
                                            </div>

                                            <div class="d-grid gap-2">
                                                <a href="<?= base_url('products/checkout') ?>" class="btn btn-success btn-lg">
                                                    <i class="bi bi-credit-card"></i> Finalizar Compra
                                                </a>
                                                <a href="<?= base_url('products/clear_cart') ?>" 
                                                   class="btn btn-outline-danger"
                                                   onclick="return confirm('Limpar todo o carrinho?')">
                                                    <i class="bi bi-trash"></i> Limpar Carrinho
                                                </a>
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
            
            // Define cores baseadas no tipo
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
                showToast('Erro', 'Digite um CEP válido', 'error');
                return;
            }

            // Busca endereço via ViaCEP
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (data.erro) {
                        showToast('Erro', 'CEP não encontrado', 'error');
                        return;
                    }

                    // Mostra endereço
                    document.getElementById('address-text').textContent = 
                        `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
                    document.getElementById('address-info').style.display = 'block';

                    // Calcula frete baseado no subtotal
                    const subtotal = <?= $subtotal ?>;
                    let shippingCost = 0;

                    // Regras de frete:
                    // Entre R$52,00 e R$166,59: R$15,00
                    // Maior que R$200,00: Grátis
                    // Outros valores: R$20,00
                    if (subtotal >= 200.00) {
                        shippingCost = 0; // Grátis
                    } else if (subtotal >= 52.00 && subtotal <= 166.59) {
                        shippingCost = 15.00;
                    } else {
                        shippingCost = 20.00;
                    }

                    const total = subtotal + shippingCost;

                    document.getElementById('shipping-cost').textContent = 
                        shippingCost === 0 ? 'Grátis' : `R$ ${shippingCost.toFixed(2).replace('.', ',')}`;
                    document.getElementById('total-cost').textContent = 
                        `R$ ${total.toFixed(2).replace('.', ',')}`;
                    
                    showToast('Sucesso', 'Frete calculado com sucesso!', 'success');
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showToast('Erro', 'Erro ao calcular frete', 'error');
                });
        }

        // Calcula frete ao pressionar Enter no CEP
        cepInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                calculateShipping();
            }
        });
    </script>
</body>
</html> 