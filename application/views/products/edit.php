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
                            <a href="<?= base_url() ?>" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                        </div>

                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Dados do Produto</h5>
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
                                                    <button type="button" onclick="removeStock(this)" class="btn btn-danger btn-sm w-100" style="height: 38px;">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <button type="button" onclick="addStock()" class="btn btn-success btn-sm">
                                            <i class="bi bi-plus-circle"></i> Adicionar Variação
                                        </button>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="<?= base_url() ?>" class="btn btn-secondary">Cancelar</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle"></i> Atualizar Produto
                                        </button>
                                    </div>
                                </form>
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
                    <button type="button" onclick="removeStock(this)" class="btn btn-danger btn-sm w-100" style="height: 38px;">
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