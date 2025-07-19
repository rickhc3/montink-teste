<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Montink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://unpkg.com/imask"></script>
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
                                <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                                    <i class="bi bi-plus-circle"></i> Novo Produto
                                </button>
                            </div>
                        </div>

                        <?php if (empty($products)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-box h1 text-muted"></i>
                                <h3 class="mt-3">Nenhum produto cadastrado</h3>
                                <p class="text-muted">Comece criando seu primeiro produto!</p>
                                <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                                    <i class="bi bi-plus-circle"></i> Criar Produto
                                </button>
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
                                                        <button type="button" 
                                                                class="btn btn-outline-primary btn-sm" 
                                                                onclick="openEditModal(<?= $product->id ?>, '<?= htmlspecialchars($product->name) ?>', <?= $product->price ?>)"
                                                                title="Editar">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
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

    <!-- Toast para notificações -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong id="toast-title" class="me-auto">Notificação</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toast-message">
                Mensagem aqui
            </div>
        </div>
    </div>

    <!-- Modal para Comprar -->
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

    <!-- Modal para Criar Produto -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Criar Novo Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createProductForm" method="post" action="<?= base_url('products/store') ?>">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nome do Produto</label>
                                <input type="text" name="name" class="form-control" placeholder="Digite o nome do produto" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Preço</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" name="price" id="create-price" class="form-control" placeholder="0,00" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Variações</label>
                            <div id="create-variations-wrapper">
                                <div class="row mb-2">
                                    <div class="col-md-8">
                                        <input type="text" name="variations[0][name]" placeholder="Nome da variação (ex: Tamanho M)" class="form-control" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="variations[0][quantity]" placeholder="Estoque" min="0" class="form-control" required>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" onclick="removeCreateVariation(this)" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="addCreateVariation()" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-plus-circle"></i> Adicionar Variação
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Salvar Produto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Produto -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProductForm" method="post" action="<?= base_url('products/update') ?>">
                    <input type="hidden" name="id" id="edit-product-id">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nome do Produto</label>
                                <input type="text" name="name" id="edit-product-name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Preço</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" name="price" id="edit-price" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Estoque por Variação</label>
                            <div id="edit-stock-wrapper">
                                <!-- Variações serão carregadas dinamicamente -->
                            </div>
                            <button type="button" onclick="addEditStock()" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-plus-circle"></i> Adicionar Variação
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Atualizar Produto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentProductId = null;
        let currentProductPrice = 0;
        let createVariationIndex = 1;
        let editStockIndex = 0;

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

        // Função para abrir modal de criação
        function openCreateModal() {
            // Limpa o formulário
            document.getElementById('createProductForm').reset();
            document.getElementById('create-variations-wrapper').innerHTML = `
                <div class="row mb-2">
                    <div class="col-md-8">
                        <input type="text" name="variations[0][name]" placeholder="Nome da variação (ex: Tamanho M)" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="variations[0][quantity]" placeholder="Estoque" min="0" class="form-control" required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" onclick="removeCreateVariation(this)" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            createVariationIndex = 1;
            
            const modal = new bootstrap.Modal(document.getElementById('createModal'));
            modal.show();
            
            // Aplica máscara no campo de preço após o modal estar visível
            setTimeout(() => {
                const priceInput = document.getElementById('create-price');
                if (priceInput) {
                    IMask(priceInput, {
                        mask: Number,
                        scale: 2,
                        thousandsSeparator: '.',
                        radix: ',',
                        mapToRadix: ['.'],
                        normalizeZeros: true,
                        padFractionalZeros: false,
                        min: 0,
                        max: 999999.99,
                        parser: function (str) {
                            return str.replace(/\D/g, '');
                        },
                        formatter: function (str) {
                            return str.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    });
                }
            }, 100);
        }

        // Função para abrir modal de edição
        function openEditModal(productId, productName, productPrice) {
            currentProductId = productId;
            
            // Preenche dados básicos
            document.getElementById('edit-product-id').value = productId;
            document.getElementById('edit-product-name').value = productName;
            document.getElementById('edit-price').value = productPrice.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
            
            // Carrega estoque do produto
            fetch(`<?= base_url('products/get_stock/') ?>${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const wrapper = document.getElementById('edit-stock-wrapper');
                        wrapper.innerHTML = '';
                        editStockIndex = 0;
                        
                        data.stock.forEach(item => {
                            addEditStockItem(item.variation, item.quantity, item.id);
                        });
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar estoque:', error);
                });
            
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
            
            // Aplica máscara no campo de preço após o modal estar visível
            setTimeout(() => {
                const priceInput = document.getElementById('edit-price');
                if (priceInput) {
                    IMask(priceInput, {
                        mask: Number,
                        scale: 2,
                        thousandsSeparator: '.',
                        radix: ',',
                        mapToRadix: ['.'],
                        normalizeZeros: true,
                        padFractionalZeros: false,
                        min: 0,
                        max: 999999.99,
                        parser: function (str) {
                            return str.replace(/\D/g, '');
                        },
                        formatter: function (str) {
                            return str.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    });
                }
            }, 100);
        }

        // Funções para o modal de criação
        function addCreateVariation() {
            const wrapper = document.getElementById('create-variations-wrapper');
            const div = document.createElement('div');
            div.classList.add('row', 'mb-2');
            div.innerHTML = `
                <div class="col-md-8">
                    <input type="text" name="variations[${createVariationIndex}][name]" placeholder="Nome da variação (ex: Tamanho M)" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="variations[${createVariationIndex}][quantity]" placeholder="Estoque" min="0" class="form-control" required>
                </div>
                <div class="col-md-1">
                    <button type="button" onclick="removeCreateVariation(this)" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
            wrapper.appendChild(div);
            createVariationIndex++;
        }

        function removeCreateVariation(button) {
            button.closest('.row').remove();
        }

        // Funções para o modal de edição
        function addEditStock() {
            addEditStockItem('', 0, 'new_' + editStockIndex);
        }

        function addEditStockItem(variation, quantity, stockId) {
            const wrapper = document.getElementById('edit-stock-wrapper');
            const div = document.createElement('div');
            div.classList.add('row', 'mb-2');
            div.innerHTML = `
                <div class="col-md-6">
                    <input type="text" name="stock[${stockId}][variation]" value="${variation}" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <input type="number" name="stock[${stockId}][quantity]" value="${quantity}" min="0" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <button type="button" onclick="removeEditStock(this)" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
            wrapper.appendChild(div);
            editStockIndex++;
        }

        function removeEditStock(button) {
            button.closest('.row').remove();
        }

        // Processamento dos formulários
        document.getElementById('createProductForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Converte preço para formato adequado
            const priceInput = document.getElementById('create-price');
            const priceValue = priceInput.value;
            if (priceValue) {
                const numericValue = priceValue.replace(/\./g, '').replace(',', '.');
                priceInput.value = numericValue;
            }
            
            // Envia formulário via AJAX
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Sucesso', data.message, 'success');
                    // Fecha o modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createModal'));
                    modal.hide();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showToast('Erro', data.message || 'Erro ao criar produto', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro', 'Erro ao criar produto', 'error');
            });
        });

        document.getElementById('editProductForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Converte preço para formato adequado
            const priceInput = document.getElementById('edit-price');
            const priceValue = priceInput.value;
            if (priceValue) {
                const numericValue = priceValue.replace(/\./g, '').replace(',', '.');
                priceInput.value = numericValue;
            }
            
            // Envia formulário via AJAX
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Sucesso', data.message, 'success');
                    // Fecha o modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                    modal.hide();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showToast('Erro', data.message || 'Erro ao atualizar produto', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro', 'Erro ao atualizar produto', 'error');
            });
        });

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