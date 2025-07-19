<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Produto - Montink</title>
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
                            <h1 class="card-title h2 mb-0">Criar Produto</h1>
                            <a href="<?= base_url() ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                        </div>

                        <form method="post" action="<?= base_url('products/store') ?>">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nome do Produto</label>
                                    <input type="text" name="name" class="form-control form-control-lg" placeholder="Digite o nome do produto" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Preço</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" name="price" id="price" class="form-control" placeholder="0,00" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Variações</label>
                                <div id="variations-wrapper">
                                    <div class="row mb-2">
                                        <div class="col-md-8">
                                            <input type="text" name="variations[0][name]" placeholder="Nome da variação (ex: Tamanho M)" class="form-control" required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" name="variations[0][quantity]" placeholder="Estoque" min="0" class="form-control" required>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" onclick="removeVariation(this)" class="btn btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" onclick="addVariation()" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Adicionar Variação
                                </button>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end pt-3">
                                <a href="<?= base_url() ?>" class="btn btn-secondary btn-lg px-4">Cancelar</a>
                                <button type="submit" class="btn btn-primary btn-lg px-4">Salvar Produto</button>
                            </div>
                        </form>
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
                // Converter de "1.234,56" para "1234.56" para o PHP
                const numericValue = priceValue.replace(/\./g, '').replace(',', '.');
                priceInput.value = numericValue;
            }
        });

        let variationIndex = 1;

        function addVariation() {
            const wrapper = document.getElementById('variations-wrapper');
            const div = document.createElement('div');
            div.classList.add('row', 'mb-2');
            div.innerHTML = `
                <div class="col-md-8">
                    <input type="text" name="variations[${variationIndex}][name]" placeholder="Nome da variação (ex: Tamanho M)" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="variations[${variationIndex}][quantity]" placeholder="Estoque" min="0" class="form-control" required>
                </div>
                <div class="col-md-1">
                    <button type="button" onclick="removeVariation(this)" class="btn btn-outline-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
            wrapper.appendChild(div);
            variationIndex++;
        }

        function removeVariation(button) {
            button.closest('.row').remove();
        }
    </script>
</body>
</html>
