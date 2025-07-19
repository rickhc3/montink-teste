<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto - Montink</title>
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
                            <h1 class="card-title h2 mb-0">Editar Produto</h1>
                            <a href="<?= base_url() ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                        </div>

                        <div class="row">
                            <!-- Seção de Edição -->
                            <div class="col-lg-6">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Dados</h5>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" action="<?= base_url('products/update') ?>">
                                            <input type="hidden" name="id" value="<?= $product->id ?>">
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Nome do Produto</label>
                                                <input type="text" name="name" value="<?= $product->name ?>" class="form-control" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Preço</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">R$</span>
                                                    <input type="text" name="price" id="price" value="<?= number_format($product->price, 2, ',', '.') ?>" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Estoque por Variação</label>
                                                <div id="stock-wrapper">
                                                    <?php foreach ($stock as $item): ?>
                                                    <div class="row mb-2">
                                                        <div class="col-md-6">
                                                            <input type="text" name="stock[<?= $item->id ?>][variation]" value="<?= $item->variation ?>" class="form-control" readonly>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="number" name="stock[<?= $item->id ?>][quantity]" value="<?= $item->quantity ?>" min="0" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" onclick="removeStock(this)" class="btn btn-outline-danger btn-sm">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <button type="button" onclick="addStock()" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-plus-circle"></i> Adicionar Variação
                                                </button>
                                            </div>

                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Atualizar Produto</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Seção de Compra -->
                            <div class="col-lg-6">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0"><i class="bi bi-cart-plus"></i> Comprar Produto</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Variação</label>
                                            <select id="variation-select" class="form-select">
                                                <option value="">Selecione uma variação</option>
                                                <?php foreach ($stock as $item): ?>
                                                <option value="<?= $item->variation ?>" data-quantity="<?= $item->quantity ?>">
                                                    <?= $item->variation ?> (Estoque: <?= $item->quantity ?>)
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Quantidade</label>
                                            <input type="number" id="quantity" min="1" value="1" class="form-control">
                                            <div class="form-text">Máximo disponível: <span id="max-quantity">-</span></div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Preço Unitário</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" id="unit-price" value="<?= number_format($product->price, 2, ',', '.') ?>" class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Total</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" id="total-price" value="<?= number_format($product->price, 2, ',', '.') ?>" class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="d-grid">
                                            <button type="button" onclick="addToCart()" class="btn btn-success btn-lg">
                                                <i class="bi bi-cart-plus"></i> Adicionar ao Carrinho
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Máscara para o campo de preço
        const priceInput = document.getElementById('price');
        const priceMask = IMask(priceInput, {
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

        // Converter valor para formato adequado antes do envio
        document.querySelector('form').addEventListener('submit', function(e) {
            const priceValue = priceMask.value;
            if (priceValue) {
                const numericValue = priceValue.replace(/\./g, '').replace(',', '.');
                priceInput.value = numericValue;
            }
        });

        // Atualiza quantidade máxima quando variação é selecionada
        document.getElementById('variation-select').addEventListener('change', function() {
            const selectedOption = this.selectedOptions[0];
            const quantityInput = document.getElementById('quantity');
            const maxQuantitySpan = document.getElementById('max-quantity');
            
            if (selectedOption.value) {
                const availableQuantity = parseInt(selectedOption.dataset.quantity);
                maxQuantitySpan.textContent = availableQuantity;
                quantityInput.max = availableQuantity;
                quantityInput.value = Math.min(quantityInput.value, availableQuantity);
                updateTotal();
            } else {
                maxQuantitySpan.textContent = '-';
                quantityInput.max = '';
                quantityInput.value = 1;
                updateTotal();
            }
        });

        // Atualiza total quando quantidade muda
        document.getElementById('quantity').addEventListener('input', updateTotal);

        function updateTotal() {
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            const unitPrice = parseFloat(document.getElementById('unit-price').value.replace(/\./g, '').replace(',', '.')) || 0;
            const total = quantity * unitPrice;
            document.getElementById('total-price').value = total.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
        }

        function addToCart() {
            const variation = document.getElementById('variation-select').value;
            const quantity = parseInt(document.getElementById('quantity').value);

            if (!variation) {
                alert('Selecione uma variação');
                return;
            }

            if (!quantity || quantity < 1) {
                alert('Digite uma quantidade válida');
                return;
            }

            // Verifica estoque
            const selectedOption = document.getElementById('variation-select').selectedOptions[0];
            const availableQuantity = parseInt(selectedOption.dataset.quantity);

            if (quantity > availableQuantity) {
                alert(`Estoque insuficiente. Disponível: ${availableQuantity}`);
                return;
            }

            // Adiciona ao carrinho via AJAX
            fetch('<?= base_url('products/add_to_cart') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=<?= $product->id ?>&variation=${encodeURIComponent(variation)}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Produto adicionado ao carrinho!');
                    window.location.href = '<?= base_url('products/cart') ?>';
                } else {
                    alert(data.message || 'Erro ao adicionar ao carrinho');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao adicionar ao carrinho');
            });
        }

        let stockIndex = <?= count($stock) ?>;

        function addStock() {
            const wrapper = document.getElementById('stock-wrapper');
            const div = document.createElement('div');
            div.classList.add('row', 'mb-2');
            div.innerHTML = `
                <div class="col-md-6">
                    <input type="text" name="stock[new_${stockIndex}][variation]" placeholder="Nome da variação" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <input type="number" name="stock[new_${stockIndex}][quantity]" placeholder="Estoque" min="0" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <button type="button" onclick="removeStock(this)" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
            wrapper.appendChild(div);
            stockIndex++;
        }

        function removeStock(button) {
            button.closest('.row').remove();
        }
    </script>
</body>
</html> 