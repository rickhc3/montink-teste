<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pedido - Montink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .checkout-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .checkout-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .form-section {
            padding: 30px;
        }
        .section-title {
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .order-summary {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .total-row {
            font-weight: 600;
            font-size: 1.1em;
            color: #333;
            border-top: 2px solid #667eea;
            padding-top: 15px;
            margin-top: 15px;
        }
        .btn-checkout {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 15px 30px;
            font-size: 1.1em;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .btn-back {
            background: #6c757d;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
        }
        .loading {
            display: none;
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <a href="<?= base_url('cart') ?>" class="btn-back">
            <i class="fas fa-arrow-left"></i> Voltar ao Carrinho
        </a>
        
        <div class="checkout-card">
            <div class="checkout-header">
                <h1><i class="fas fa-shopping-cart"></i> Finalizar Pedido</h1>
                <p class="mb-0">Preencha seus dados para concluir a compra</p>
            </div>
            
            <div class="row g-0">
                <div class="col-lg-8">
                    <div class="form-section">
                        <div id="alert-container"></div>
                        
                        <form id="checkout-form">
                            <h3 class="section-title"><i class="fas fa-user"></i> Dados Pessoais</h3>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="customer_name" class="form-label">Nome Completo *</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="customer_email" class="form-label">E-mail *</label>
                                    <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="customer_phone" class="form-label">Telefone *</label>
                                    <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="customer_cep" class="form-label">CEP *</label>
                                    <input type="text" class="form-control" id="customer_cep" name="customer_cep" required maxlength="9" placeholder="00000-000">
                                </div>
                            </div>
                            
                            <h3 class="section-title mt-4"><i class="fas fa-map-marker-alt"></i> Endereço de Entrega</h3>
                            
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="customer_address" class="form-label">Endereço *</label>
                                    <input type="text" class="form-control" id="customer_address" name="customer_address" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="customer_number" class="form-label">Número *</label>
                                    <input type="text" class="form-control" id="customer_number" name="customer_number" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="customer_complement" class="form-label">Complemento</label>
                                    <input type="text" class="form-control" id="customer_complement" name="customer_complement">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="customer_neighborhood" class="form-label">Bairro *</label>
                                    <input type="text" class="form-control" id="customer_neighborhood" name="customer_neighborhood" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="customer_city" class="form-label">Cidade *</label>
                                    <input type="text" class="form-control" id="customer_city" name="customer_city" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="customer_state" class="form-label">Estado *</label>
                                    <select class="form-control" id="customer_state" name="customer_state" required>
                                        <option value="">Selecione o estado</option>
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
                            
                            <h3 class="section-title mt-4"><i class="fas fa-percent"></i> Cupom de Desconto</h3>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="coupon_code" class="form-label">Código do Cupom</label>
                                    <input type="text" class="form-control" id="coupon_code" name="coupon_code" placeholder="Digite o código do cupom">
                                </div>
                                <div class="col-md-6 mb-3 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-primary" id="validate-coupon">
                                        <i class="fas fa-check"></i> Validar Cupom
                                    </button>
                                </div>
                            </div>
                            
                            <div id="coupon-result" class="mt-2"></div>
                        </form>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="form-section" style="background: #f8f9fa;">
                        <h3 class="section-title"><i class="fas fa-receipt"></i> Resumo do Pedido</h3>
                        
                        <div class="order-summary">
                            <div id="order-items">
                                <?php foreach ($cart as $item): ?>
                                <div class="order-item">
                                    <div>
                                        <strong><?= $item['name'] ?></strong><br>
                                        <small class="text-muted"><?= $item['variation'] ?> - Qtd: <?= $item['quantity'] ?></small>
                                    </div>
                                    <div class="text-end">
                                        <strong>R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></strong>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="order-item">
                                <span>Subtotal:</span>
                                <span id="subtotal">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                            </div>
                            
                            <div class="order-item" id="discount-row" style="display: none;">
                                <span>Desconto:</span>
                                <span id="discount" class="text-success">R$ 0,00</span>
                            </div>
                            
                            <div class="order-item">
                                <span>Frete:</span>
                                <span id="shipping">R$ <?= number_format($shipping, 2, ',', '.') ?></span>
                            </div>
                            
                            <div class="order-item total-row">
                                <span>Total:</span>
                                <span id="total">R$ <?= number_format($total, 2, ',', '.') ?></span>
                            </div>
                        </div>
                        
                        <button type="submit" form="checkout-form" class="btn-checkout mt-3">
                            <span class="normal-text">
                                <i class="fas fa-credit-card"></i> Finalizar Pedido
                            </span>
                            <span class="loading">
                                <i class="fas fa-spinner fa-spin"></i> Processando...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let appliedCoupon = null;
        let discountAmount = 0;
        
        // Máscara para CEP
        document.getElementById('customer_cep').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.substring(0, 5) + '-' + value.substring(5, 8);
            }
            e.target.value = value;
        });
        
        // Buscar endereço pelo CEP
        document.getElementById('customer_cep').addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('customer_address').value = data.logradouro || '';
                            document.getElementById('customer_neighborhood').value = data.bairro || '';
                            document.getElementById('customer_city').value = data.localidade || '';
                            document.getElementById('customer_state').value = data.uf || '';
                        }
                    })
                    .catch(error => console.error('Erro ao buscar CEP:', error));
            }
        });
        
        // Validar cupom
        document.getElementById('validate-coupon').addEventListener('click', function() {
            const couponCode = document.getElementById('coupon_code').value.trim();
            const subtotal = <?= $subtotal ?>;
            
            if (!couponCode) {
                showCouponResult('Por favor, digite um código de cupom.', 'danger');
                return;
            }
            
            fetch('<?= base_url('products/validate_coupon') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `code=${encodeURIComponent(couponCode)}&subtotal=${subtotal}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    appliedCoupon = data.coupon;
                    discountAmount = data.discount;
                    showCouponResult(`Cupom aplicado! Desconto: ${data.discount_formatted}`, 'success');
                    updateOrderSummary();
                } else {
                    showCouponResult(data.message, 'danger');
                    appliedCoupon = null;
                    discountAmount = 0;
                    updateOrderSummary();
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showCouponResult('Erro ao validar cupom. Tente novamente.', 'danger');
            });
        });
        
        function showCouponResult(message, type) {
            const resultDiv = document.getElementById('coupon-result');
            resultDiv.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
        }
        
        function updateOrderSummary() {
            const subtotal = <?= $subtotal ?>;
            const shipping = <?= $shipping ?>;
            const total = subtotal + shipping - discountAmount;
            
            if (discountAmount > 0) {
                document.getElementById('discount-row').style.display = 'flex';
                document.getElementById('discount').textContent = `- R$ ${discountAmount.toFixed(2).replace('.', ',')}`;
            } else {
                document.getElementById('discount-row').style.display = 'none';
            }
            
            document.getElementById('total').textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
        }
        
        // Submeter formulário
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            if (appliedCoupon) {
                formData.append('coupon_code', document.getElementById('coupon_code').value);
            }
            
            // Mostrar loading
            const btn = document.querySelector('.btn-checkout');
            btn.querySelector('.normal-text').style.display = 'none';
            btn.querySelector('.loading').style.display = 'inline';
            btn.disabled = true;
            
            fetch('<?= base_url('products/finalize_order') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Pedido finalizado com sucesso! Você receberá um e-mail de confirmação.', 'success');
                    setTimeout(() => {
                        window.location.href = '<?= base_url('products') ?>';
                    }, 2000);
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showAlert('Erro ao processar pedido. Tente novamente.', 'danger');
            })
            .finally(() => {
                // Esconder loading
                btn.querySelector('.normal-text').style.display = 'inline';
                btn.querySelector('.loading').style.display = 'none';
                btn.disabled = false;
            });
        });
        
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alert-container');
            alertContainer.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
            
            // Scroll para o topo
            document.querySelector('.checkout-container').scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</body>
</html>