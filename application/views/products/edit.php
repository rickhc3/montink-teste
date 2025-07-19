<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto - Montink</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/imask"></script>
    <style>
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen p-4">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-xl p-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Editar Produto</h1>
                <a href="<?= base_url('products') ?>" class="text-blue-600 hover:text-blue-800 text-sm">← Voltar</a>
            </div>

            <!-- Formulário de Edição -->
            <form method="post" action="<?= base_url('products/update/' . $product->id) ?>" class="space-y-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nome do Produto</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($product->name) ?>" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Digite o nome do produto" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Preço</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-500">R$</span>
                            <input type="text" name="price" id="price" value="<?= number_format($product->price, 2, ',', '.') ?>" class="form-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0,00" required>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Variações</label>
                    <div id="variations-wrapper" class="space-y-3">
                        <?php foreach ($stock as $index => $item): ?>
                            <div class="flex gap-3">
                                <input type="text" name="variations[<?= $index ?>][name]" value="<?= htmlspecialchars($item->variation) ?>" placeholder="Nome da variação (ex: Tamanho M)" class="form-input flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <input type="number" name="variations[<?= $index ?>][quantity]" value="<?= $item->quantity ?>" placeholder="Estoque" min="0" class="form-input w-24 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <button type="button" onclick="removeVariation(this)" class="px-3 py-3 text-red-600 hover:text-red-800 border border-red-300 hover:border-red-400 rounded-lg transition duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" onclick="addVariation()" class="mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Adicionar Variação
                    </button>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition duration-200">
                        Atualizar Produto
                    </button>
                    <a href="<?= base_url('products') ?>" class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 focus:ring-4 focus:ring-gray-200 transition duration-200 text-center">
                        Cancelar
                    </a>
                </div>
            </form>

            <!-- Seção de Compra -->
            <div class="border-t pt-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Comprar Produto</h2>
                
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Seleção de Variação -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Variação</label>
                            <select id="variation-select" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Selecione uma variação</option>
                                <?php foreach ($stock as $item): ?>
                                    <option value="<?= htmlspecialchars($item->variation) ?>" data-quantity="<?= $item->quantity ?>">
                                        <?= htmlspecialchars($item->variation) ?> (Estoque: <?= $item->quantity ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Quantidade -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Quantidade</label>
                            <input type="number" id="quantity" min="1" value="1" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Botão Comprar -->
                        <div class="flex items-end">
                            <button onclick="addToCart()" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition duration-200">
                                Adicionar ao Carrinho
                            </button>
                        </div>
                    </div>

                    <!-- Informações do Produto -->
                    <div class="mt-6 p-4 bg-white rounded-lg border">
                        <h3 class="font-semibold text-gray-800 mb-2"><?= htmlspecialchars($product->name) ?></h3>
                        <p class="text-2xl font-bold text-green-600">R$ <?= number_format($product->price, 2, ',', '.') ?></p>
                        <p class="text-sm text-gray-600 mt-1">Preço unitário</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        let variationIndex = <?= count($stock) ?>;

        function addVariation() {
            const wrapper = document.getElementById('variations-wrapper');
            const div = document.createElement('div');
            div.classList.add('flex', 'gap-3');
            div.innerHTML = `
                <input type="text" name="variations[${variationIndex}][name]" placeholder="Nome da variação (ex: Tamanho M)" class="form-input flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                <input type="number" name="variations[${variationIndex}][quantity]" placeholder="Estoque" min="0" class="form-input w-24 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                <button type="button" onclick="removeVariation(this)" class="px-3 py-3 text-red-600 hover:text-red-800 border border-red-300 hover:border-red-400 rounded-lg transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            `;
            wrapper.appendChild(div);
            variationIndex++;
        }

        function removeVariation(button) {
            button.parentElement.remove();
        }

        function addToCart() {
            const variation = document.getElementById('variation-select').value;
            const quantity = document.getElementById('quantity').value;

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
                    // Redireciona para o carrinho
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
    </script>
</body>
</html> 