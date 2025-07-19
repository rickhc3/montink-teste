<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Montink</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen p-4">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-xl p-8">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Finalizar Compra</h1>
                <a href="<?= base_url('products/cart') ?>" class="text-blue-600 hover:text-blue-800 text-sm">← Voltar ao Carrinho</a>
            </div>

            <?php if (empty($cart)): ?>
                <div class="text-center py-12">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Carrinho vazio</h3>
                    <p class="text-gray-500 mb-6">Adicione produtos ao carrinho para continuar.</p>
                    <a href="<?= base_url('products') ?>" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                        Ver Produtos
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Formulário de Entrega -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-6">Dados de Entrega</h2>
                        
                        <form id="checkout-form" class="space-y-6">
                            <!-- CEP -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">CEP *</label>
                                <div class="flex gap-2">
                                    <input type="text" id="cep" name="cep" placeholder="00000-000" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" maxlength="9" required>
                                    <button type="button" onclick="searchCep()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition duration-200">
                                        Buscar
                                    </button>
                                </div>
                                <div id="cep-info" class="mt-2 text-sm text-gray-600"></div>
                            </div>

                            <!-- Endereço -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rua *</label>
                                    <input type="text" id="street" name="street" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Número *</label>
                                    <input type="text" id="number" name="number" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Complemento</label>
                                    <input type="text" id="complement" name="complement" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bairro *</label>
                                    <input type="text" id="neighborhood" name="neighborhood" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cidade *</label>
                                    <input type="text" id="city" name="city" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Estado *</label>
                                    <input type="text" id="state" name="state" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">País *</label>
                                    <input type="text" id="country" name="country" value="Brasil" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                            </div>

                            <!-- Dados Pessoais -->
                            <div class="border-t pt-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Dados Pessoais</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nome Completo *</label>
                                        <input type="text" id="name" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">E-mail *</label>
                                        <input type="email" id="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Telefone *</label>
                                        <input type="tel" id="phone" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">CPF *</label>
                                        <input type="text" id="cpf" name="cpf" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Resumo do Pedido -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-6">Resumo do Pedido</h2>
                        
                        <div class="bg-gray-50 rounded-lg p-6 border">
                            <!-- Lista de Produtos -->
                            <div class="space-y-3 mb-6">
                                <?php 
                                $subtotal = 0;
                                foreach ($cart as $item): 
                                    $item_total = $item['price'] * $item['quantity'];
                                    $subtotal += $item_total;
                                ?>
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-medium"><?= htmlspecialchars($item['name']) ?></p>
                                            <p class="text-sm text-gray-600"><?= htmlspecialchars($item['variation']) ?> - Qtd: <?= $item['quantity'] ?></p>
                                        </div>
                                        <p class="font-semibold">R$ <?= number_format($item_total, 2, ',', '.') ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <hr class="border-gray-300 mb-4">

                            <!-- Cálculos -->
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="font-semibold">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Frete:</span>
                                    <span class="font-semibold" id="shipping-cost">R$ 20,00</span>
                                </div>
                                <hr class="border-gray-300">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total:</span>
                                    <span id="total-cost">R$ <?= number_format($subtotal + 20, 2, ',', '.') ?></span>
                                </div>
                            </div>

                            <!-- Informações de Frete -->
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                                <h4 class="font-semibold text-blue-800 mb-2">Informações de Frete</h4>
                                <ul class="text-sm text-blue-700 space-y-1">
                                    <li>• Acima de R$ 200,00: <strong>Frete Grátis</strong></li>
                                    <li>• Entre R$ 52,00 e R$ 166,59: <strong>R$ 15,00</strong></li>
                                    <li>• Outros valores: <strong>R$ 20,00</strong></li>
                                </ul>
                            </div>

                            <!-- Botão Finalizar -->
                            <button onclick="finalizeOrder()" class="w-full mt-6 bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition duration-200">
                                Finalizar Pedido
                            </button>
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

        // Máscara para CPF
        const cpfInput = document.getElementById('cpf');
        if (cpfInput) {
            cpfInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 9) {
                    value = value.substring(0, 3) + '.' + value.substring(3, 6) + '.' + value.substring(6, 9) + '-' + value.substring(9, 11);
                } else if (value.length > 6) {
                    value = value.substring(0, 3) + '.' + value.substring(3, 6) + '.' + value.substring(6, 9);
                } else if (value.length > 3) {
                    value = value.substring(0, 3) + '.' + value.substring(3, 6);
                }
                e.target.value = value;
            });
        }

        // Máscara para Telefone
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 10) {
                    value = value.substring(0, 2) + ' ' + value.substring(2, 7) + '-' + value.substring(7, 11);
                } else if (value.length > 6) {
                    value = value.substring(0, 2) + ' ' + value.substring(2, 7) + '-' + value.substring(7);
                } else if (value.length > 2) {
                    value = value.substring(0, 2) + ' ' + value.substring(2);
                }
                e.target.value = value;
            });
        }

        function searchCep() {
            const cep = document.getElementById('cep').value.replace(/\D/g, '');
            
            if (cep.length !== 8) {
                alert('Digite um CEP válido');
                return;
            }

            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (data.erro) {
                        document.getElementById('cep-info').innerHTML = '<span class="text-red-600">CEP não encontrado</span>';
                    } else {
                        document.getElementById('street').value = data.logradouro || '';
                        document.getElementById('neighborhood').value = data.bairro || '';
                        document.getElementById('city').value = data.localidade || '';
                        document.getElementById('state').value = data.uf || '';
                        
                        document.getElementById('cep-info').innerHTML = `
                            <span class="text-green-600">
                                <strong>${data.localidade} - ${data.uf}</strong><br>
                                ${data.logradouro ? data.logradouro + ', ' : ''}${data.bairro}
                            </span>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar CEP:', error);
                    document.getElementById('cep-info').innerHTML = '<span class="text-red-600">Erro ao buscar CEP</span>';
                });
        }

        function finalizeOrder() {
            // Validação básica
            const requiredFields = ['cep', 'street', 'number', 'neighborhood', 'city', 'state', 'name', 'email', 'phone', 'cpf'];
            let isValid = true;

            requiredFields.forEach(field => {
                const element = document.getElementById(field);
                if (!element.value.trim()) {
                    element.classList.add('border-red-500');
                    isValid = false;
                } else {
                    element.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                alert('Por favor, preencha todos os campos obrigatórios');
                return;
            }

            // Aqui você implementaria a lógica para salvar o pedido
            alert('Pedido finalizado com sucesso! Em uma implementação real, aqui seria processado o pagamento e salvo o pedido no banco de dados.');
            
            // Limpa o carrinho e redireciona
            window.location.href = '<?= base_url('products') ?>';
        }

        // Calcula frete automaticamente quando o CEP é preenchido
        cepInput.addEventListener('blur', function() {
            if (this.value.replace(/\D/g, '').length === 8) {
                searchCep();
            }
        });
    </script>
</body>
</html> 