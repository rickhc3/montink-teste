<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho - Montink</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen p-4">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-xl p-8">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Carrinho de Compras</h1>
                <a href="<?= base_url('products') ?>" class="text-blue-600 hover:text-blue-800 text-sm">← Continuar Comprando</a>
            </div>

            <?php if (empty($cart)): ?>
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Seu carrinho está vazio</h3>
                    <p class="text-gray-500 mb-6">Adicione produtos para começar suas compras.</p>
                    <a href="<?= base_url('products') ?>" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                        Ver Produtos
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Lista de Produtos -->
                    <div class="lg:col-span-2">
                        <div class="space-y-4">
                            <?php 
                            $subtotal = 0;
                            foreach ($cart as $item_key => $item): 
                                $item_total = $item['price'] * $item['quantity'];
                                $subtotal += $item_total;
                            ?>
                                <div class="bg-gray-50 rounded-lg p-6 border">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-800"><?= htmlspecialchars($item['name']) ?></h3>
                                            <p class="text-sm text-gray-600">Variação: <?= htmlspecialchars($item['variation']) ?></p>
                                            <p class="text-sm text-gray-600">Quantidade: <?= $item['quantity'] ?></p>
                                            <p class="text-lg font-bold text-green-600 mt-2">R$ <?= number_format($item_total, 2, ',', '.') ?></p>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <div class="text-right">
                                                <p class="text-sm text-gray-600">R$ <?= number_format($item['price'], 2, ',', '.') ?> cada</p>
                                            </div>
                                            <form method="post" action="<?= base_url('products/remove_from_cart') ?>" class="inline">
                                                <input type="hidden" name="item_key" value="<?= $item_key ?>">
                                                <button type="submit" class="text-red-600 hover:text-red-800 p-2" onclick="return confirm('Remover este item do carrinho?')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Resumo do Pedido -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 rounded-lg p-6 border sticky top-4">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">Resumo do Pedido</h2>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="font-semibold">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Frete:</span>
                                    <span class="font-semibold" id="shipping-cost">-</span>
                                </div>
                                <hr class="border-gray-300">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total:</span>
                                    <span id="total-cost">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                                </div>
                            </div>

                            <!-- Cálculo de Frete -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">CEP para cálculo do frete</label>
                                <div class="flex gap-2">
                                    <input type="text" id="cep" placeholder="00000-000" class="flex-1 px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent" maxlength="9">
                                    <button onclick="calculateShipping()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition duration-200">
                                        Calcular
                                    </button>
                                </div>
                                <div id="cep-info" class="mt-2 text-sm text-gray-600 hidden"></div>
                            </div>

                            <a href="<?= base_url('products/checkout') ?>" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition duration-200 text-center block">
                                Finalizar Compra
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Máscara para CEP
        const cepInput = document.getElementById('cep');
        if (cepInput) {
            cepInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 5) {
                    value = value.substring(0, 5) + '-' + value.substring(5, 8);
                }
                e.target.value = value;
            });
        }

        function calculateShipping() {
            const cep = document.getElementById('cep').value.replace(/\D/g, '');
            const subtotal = <?= $subtotal ?>;

            if (cep.length !== 8) {
                alert('Digite um CEP válido');
                return;
            }

            // Calcula frete baseado no subtotal
            let shipping = 20.00; // Padrão
            if (subtotal >= 200.00) {
                shipping = 0.00; // Frete grátis
            } else if (subtotal >= 52.00 && subtotal <= 166.59) {
                shipping = 15.00;
            }

            // Busca dados do CEP
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (data.erro) {
                        document.getElementById('cep-info').innerHTML = 'CEP não encontrado';
                        document.getElementById('cep-info').classList.remove('hidden');
                    } else {
                        document.getElementById('cep-info').innerHTML = `
                            <strong>${data.localidade} - ${data.uf}</strong><br>
                            ${data.logradouro ? data.logradouro + ', ' : ''}${data.bairro}
                        `;
                        document.getElementById('cep-info').classList.remove('hidden');
                    }

                    // Atualiza valores
                    document.getElementById('shipping-cost').textContent = shipping === 0 ? 'Grátis' : `R$ ${shipping.toFixed(2).replace('.', ',')}`;
                    document.getElementById('total-cost').textContent = `R$ ${(subtotal + shipping).toFixed(2).replace('.', ',')}`;
                })
                .catch(error => {
                    console.error('Erro ao buscar CEP:', error);
                    document.getElementById('cep-info').innerHTML = 'Erro ao buscar CEP';
                    document.getElementById('cep-info').classList.remove('hidden');
                });
        }
    </script>
</body>
</html> 