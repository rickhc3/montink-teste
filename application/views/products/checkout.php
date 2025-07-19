<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - Montink</title>
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
                                <i class="bi bi-credit-card"></i> Finalizar Compra
                            </h1>
                            <a href="<?= base_url('products/cart') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Voltar ao Carrinho
                            </a>
                        </div>

                        <div class="row">
                            <!-- Formulário de Entrega -->
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="bi bi-truck"></i> Dados de Entrega</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold">CEP *</label>
                                                <div class="input-group">
                                                    <input type="text" id="cep" class="form-control" placeholder="00000-000" maxlength="9" required>
                                                    <button type="button" onclick="searchCep()" class="btn btn-outline-primary">
                                                        <i class="bi bi-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label fw-bold">Endereço *</label>
                                                <input type="text" id="street" class="form-control" placeholder="Rua, Avenida, etc." required>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-2">
                                                <label class="form-label fw-bold">Número *</label>
                                                <input type="text" id="number" class="form-control" placeholder="123" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold">Complemento</label>
                                                <input type="text" id="complement" class="form-control" placeholder="Apto, Casa, etc.">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Bairro *</label>
                                                <input type="text" id="neighborhood" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Cidade *</label>
                                                <input type="text" id="city" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Estado *</label>
                                                <select id="state" class="form-select" required>
                                                    <option value="">Selecione...</option>
                                                    <option value="AC">Acre</option>
                                                    <option value="AL">Alagoas</option>
                                                    <option value="AP">Amapá</option>
                                                    <option value="AM">Amazonas</option>
                                                    <option value="BA">Bahia</option>
                                                    <option value="CE">Ceará</option>
                                                    <option value="DF">Distrito Federal</option>
                                                    <option value="ES">Espírito Santo</option>
                                                    <option value="GO">Goiás</option>
                                                    <option value="MA">Maranhão</option>
                                                    <option value="MT">Mato Grosso</option>
                                                    <option value="MS">Mato Grosso do Sul</option>
                                                    <option value="MG">Minas Gerais</option>
                                                    <option value="PA">Pará</option>
                                                    <option value="PB">Paraíba</option>
                                                    <option value="PR">Paraná</option>
                                                    <option value="PE">Pernambuco</option>
                                                    <option value="PI">Piauí</option>
                                                    <option value="RJ">Rio de Janeiro</option>
                                                    <option value="RN">Rio Grande do Norte</option>
                                                    <option value="RS">Rio Grande do Sul</option>
                                                    <option value="RO">Rondônia</option>
                                                    <option value="RR">Roraima</option>
                                                    <option value="SC">Santa Catarina</option>
                                                    <option value="SP">São Paulo</option>
                                                    <option value="SE">Sergipe</option>
                                                    <option value="TO">Tocantins</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="bi bi-person"></i> Dados Pessoais</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Nome Completo *</label>
                                                <input type="text" id="name" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">E-mail *</label>
                                                <input type="email" id="email" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Telefone *</label>
                                                <input type="text" id="phone" class="form-control" placeholder="(11) 99999-9999" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">CPF *</label>
                                                <input type="text" id="cpf" class="form-control" placeholder="000.000.000-00" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Resumo do Pedido -->
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="bi bi-receipt"></i> Resumo do Pedido</h5>
                                    </div>
                                    <div class="card-body">
                                        <?php 
                                        $subtotal = 0;
                                        foreach ($cart as $item): 
                                            $subtotal += $item['price'] * $item['quantity'];
                                        ?>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span><?= htmlspecialchars($item['name']) ?> (<?= $item['variation'] ?>)</span>
                                                <span>R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></span>
                                            </div>
                                        <?php endforeach; ?>

                                        <hr>

                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <strong>R$ <?= number_format($subtotal, 2, ',', '.') ?></strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Frete:</span>
                                            <strong id="shipping-cost">R$ 15,00</strong>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="fw-bold">Total:</span>
                                            <strong class="text-success fs-5" id="total-cost">R$ <?= number_format($subtotal + 15, 2, ',', '.') ?></strong>
                                        </div>

                                        <div class="d-grid">
                                            <button type="button" onclick="finalizeOrder()" class="btn btn-success btn-lg">
                                                <i class="bi bi-check-circle"></i> Finalizar Pedido
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
        // Máscaras
        const cepInput = document.getElementById('cep');
        const phoneInput = document.getElementById('phone');
        const cpfInput = document.getElementById('cpf');

        const cepMask = IMask(cepInput, {
            mask: '00000-000'
        });

        const phoneMask = IMask(phoneInput, {
            mask: '(00) 00000-0000'
        });

        const cpfMask = IMask(cpfInput, {
            mask: '000.000.000-00'
        });

        function searchCep() {
            const cep = cepInput.value.replace(/\D/g, '');
            
            if (cep.length !== 8) {
                showToast('Erro', 'Digite um CEP válido', 'error');
                return;
            }

            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (data.erro) {
                        showToast('Erro', 'CEP não encontrado', 'error');
                        return;
                    }

                    document.getElementById('street').value = data.logradouro || '';
                    document.getElementById('neighborhood').value = data.bairro || '';
                    document.getElementById('city').value = data.localidade || '';
                    document.getElementById('state').value = data.uf || '';
                    
                    showToast('Sucesso', 'Endereço preenchido automaticamente!', 'success');
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showToast('Erro', 'Erro ao buscar CEP', 'error');
                });
        }

        function finalizeOrder() {
            // Validação básica
            const requiredFields = ['cep', 'street', 'number', 'neighborhood', 'city', 'state', 'name', 'email', 'phone', 'cpf'];
            let isValid = true;

            requiredFields.forEach(field => {
                const element = document.getElementById(field);
                if (!element.value.trim()) {
                    element.classList.add('is-invalid');
                    isValid = false;
                } else {
                    element.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                showToast('Atenção', 'Por favor, preencha todos os campos obrigatórios', 'warning');
                return;
            }

            // Confirma finalização
            if (!confirm('Confirmar finalização do pedido? O estoque será atualizado.')) {
                return;
            }

            // Finaliza o pedido via AJAX
            fetch('<?= base_url('products/finalize_order') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Sucesso', 'Pedido finalizado com sucesso! ID do pedido: ' + data.order_id, 'success');
                    setTimeout(() => {
                        window.location.href = '<?= base_url('products') ?>';
                    }, 2000);
                } else {
                    showToast('Erro', data.message || 'Erro ao finalizar pedido', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro', 'Erro ao finalizar pedido', 'error');
            });
        }

        // Busca CEP ao pressionar Enter
        cepInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchCep();
            }
        });
    </script>
</body>
</html> 