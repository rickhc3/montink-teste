<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Montink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="card-title h2 mb-0">Produtos</h1>
                            <div class="d-flex gap-2">
                                <a href="<?= base_url('products/cart') ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-cart3"></i> Carrinho
                                </a>
                                <a href="<?= base_url('products/create') ?>" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Novo Produto
                                </a>
                            </div>
                        </div>

                        <?php if (empty($products)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-box h1 text-muted"></i>
                                <h3 class="mt-3">Nenhum produto cadastrado</h3>
                                <p class="text-muted">Comece criando seu primeiro produto!</p>
                                <a href="<?= base_url('products/create') ?>" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Criar Produto
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>Preço</th>
                                            <th>Estoque Total</th>
                                            <th>Variações</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $product): ?>
                                            <tr>
                                                <td><span class="badge bg-secondary"><?= $product->id ?></span></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($product->name) ?></strong>
                                                </td>
                                                <td>
                                                    <span class="text-success fw-bold">R$ <?= number_format($product->price, 2, ',', '.') ?></span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $totalStock = 0;
                                                    foreach ($product->stock as $stock) {
                                                        $totalStock += $stock->quantity;
                                                    }
                                                    ?>
                                                    <span class="badge bg-info"><?= $totalStock ?> unidades</span>
                                                </td>
                                                <td>
                                                    <?php foreach ($product->stock as $stock): ?>
                                                        <span class="badge bg-light text-dark me-1">
                                                            <?= htmlspecialchars($stock->variation) ?>: <?= $stock->quantity ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="<?= base_url('products/edit/' . $product->id) ?>" 
                                                           class="btn btn-outline-primary btn-sm" 
                                                           title="Editar">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-outline-success btn-sm" 
                                                                onclick="openBuyModal(<?= $product->id ?>, '<?= htmlspecialchars($product->name) ?>', <?= $product->price ?>)"
                                                                title="Comprar">
                                                            <i class="bi bi-cart-plus"></i>
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-outline-danger btn-sm" 
                                                                onclick="deleteProduct(<?= $product->id ?>)"
                                                                title="Excluir">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
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

    <!-- Modal de Compra -->
    <div class="modal fade" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="buyModalLabel">Adicionar ao Carrinho</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Produto</label>
                        <input type="text" id="modal-product-name" class="form-control" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Variação</label>
                        <select id="modal-variation-select" class="form-select">
                            <option value="">Selecione uma variação</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Quantidade</label>
                        <input type="number" id="modal-quantity" min="1" value="1" class="form-control">
                        <div class="form-text">Máximo disponível: <span id="modal-max-quantity">-</span></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Preço Unitário</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="text" id="modal-unit-price" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Total</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="text" id="modal-total-price" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" onclick="addToCartFromModal()">
                        <i class="bi bi-cart-plus"></i> Adicionar ao Carrinho
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentProductId = null;
        let currentProductPrice = 0;

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

        function deleteProduct(id) {
            if (confirm('Tem certeza que deseja excluir este produto?')) {
                window.location.href = '<?= base_url('products/delete/') ?>' + id;
            }
        }

        function openBuyModal(productId, productName, productPrice) {
            currentProductId = productId;
            currentProductPrice = productPrice;
            
            // Preenche dados do produto
            document.getElementById('modal-product-name').value = productName;
            document.getElementById('modal-unit-price').value = productPrice.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
            document.getElementById('modal-total-price').value = productPrice.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
            
            // Limpa seleções anteriores
            document.getElementById('modal-variation-select').innerHTML = '<option value="">Selecione uma variação</option>';
            document.getElementById('modal-quantity').value = 1;
            document.getElementById('modal-max-quantity').textContent = '-';
            
            // Carrega variações do produto
            fetch(`<?= base_url('products/get_stock/') ?>${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const select = document.getElementById('modal-variation-select');
                        data.stock.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.variation;
                            option.textContent = `${item.variation} (Estoque: ${item.quantity})`;
                            option.dataset.quantity = item.quantity;
                            select.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar estoque:', error);
                });
            
            // Abre o modal
            const modal = new bootstrap.Modal(document.getElementById('buyModal'));
            modal.show();
        }

        // Atualiza quantidade máxima quando variação é selecionada
        document.getElementById('modal-variation-select').addEventListener('change', function() {
            const selectedOption = this.selectedOptions[0];
            const quantityInput = document.getElementById('modal-quantity');
            const maxQuantitySpan = document.getElementById('modal-max-quantity');
            
            if (selectedOption.value) {
                const availableQuantity = parseInt(selectedOption.dataset.quantity);
                maxQuantitySpan.textContent = availableQuantity;
                quantityInput.max = availableQuantity;
                quantityInput.value = Math.min(quantityInput.value, availableQuantity);
                updateModalTotal();
            } else {
                maxQuantitySpan.textContent = '-';
                quantityInput.max = '';
                quantityInput.value = 1;
                updateModalTotal();
            }
        });

        // Atualiza total quando quantidade muda
        document.getElementById('modal-quantity').addEventListener('input', updateModalTotal);

        function updateModalTotal() {
            const quantity = parseInt(document.getElementById('modal-quantity').value) || 0;
            const total = quantity * currentProductPrice;
            document.getElementById('modal-total-price').value = total.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
        }

        function addToCartFromModal() {
            const variation = document.getElementById('modal-variation-select').value;
            const quantity = parseInt(document.getElementById('modal-quantity').value);

            if (!variation) {
                showToast('Atenção', 'Selecione uma variação', 'warning');
                return;
            }

            if (!quantity || quantity < 1) {
                showToast('Atenção', 'Digite uma quantidade válida', 'warning');
                return;
            }

            // Verifica estoque
            const selectedOption = document.getElementById('modal-variation-select').selectedOptions[0];
            const availableQuantity = parseInt(selectedOption.dataset.quantity);

            if (quantity > availableQuantity) {
                showToast('Erro', `Estoque insuficiente. Disponível: ${availableQuantity}`, 'error');
                return;
            }

            // Adiciona ao carrinho via AJAX
            fetch('<?= base_url('products/add_to_cart') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${currentProductId}&variation=${encodeURIComponent(variation)}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Sucesso', 'Produto adicionado ao carrinho!', 'success');
                    // Fecha o modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('buyModal'));
                    modal.hide();
                    // Opcional: redireciona para o carrinho
                    // window.location.href = '<?= base_url('products/cart') ?>';
                } else {
                    showToast('Erro', data.message || 'Erro ao adicionar ao carrinho', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro', 'Erro ao adicionar ao carrinho', 'error');
            });
        }
    </script>
</body>
</html> 