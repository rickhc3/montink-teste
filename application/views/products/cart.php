<?php $this->load->view('layouts/header'); ?>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --light-gray: #f8f9fa;
            --medium-gray: #6c757d;
            --border-color: #dee2e6;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 1.5rem;
        }

        .page-title {
            font-weight: 300;
            font-size: 1.8rem;
            margin: 0;
        }

        .page-title i {
            margin-right: 0.5rem;
            opacity: 0.9;
        }


        .empty-cart {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--medium-gray);
        }

        .empty-cart i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .product-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 15px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .product-item {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-name {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .product-variation {
            color: var(--medium-gray);
            font-size: 0.9rem;
        }

        .quantity-badge {
            background: linear-gradient(135deg, var(--accent-color), #2980b9);
            color: white;
            border-radius: 20px;
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .price-text {
            font-weight: 700;
            color: var(--success-color);
            font-size: 1.1rem;
        }

        .remove-btn {
            background: var(--danger-color);
            border: none;
            color: white;
            border-radius: 4px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }

        .summary-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 15px;
            overflow: hidden;
            position: sticky;
            top: 2rem;
        }

        .summary-header {
            background: linear-gradient(135deg, var(--light-gray), #e9ecef);
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-body {
            padding: 1rem;
        }

        .cep-input-group {
            position: relative;
            margin-bottom: 1rem;
        }

        .cep-input {
            border-radius: 4px;
            border: 1px solid var(--border-color);
            padding: 0.5rem 0.75rem;
            transition: all 0.3s ease;
        }

        .cep-input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .cep-btn {
            border-radius: 4px;
            background: var(--accent-color);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .cep-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .address-info {
            background: var(--light-gray);
            border-radius: 10px;
            padding: 0.75rem;
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: var(--medium-gray);
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            padding: 0.25rem 0;
        }

        .summary-total {
            border-top: 2px solid var(--border-color);
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .total-amount {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--success-color);
        }

        .checkout-btn {
            background: var(--success-color);
            border: none;
            color: white;
            border-radius: 4px;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 0.75rem;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.3);
        }

        .clear-cart-btn {
            background: transparent;
            border: 2px solid var(--danger-color);
            color: var(--danger-color);
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .clear-cart-btn:hover {
            background: var(--danger-color);
            color: white;
            transform: translateY(-1px);
        }

        .section-header {
            background: var(--light-gray);
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .main-container {
                margin: 1rem;
                border-radius: 15px;
            }

            .page-header {
                padding: 1.5rem;
                border-radius: 15px 15px 0 0;
            }

            .page-title {
                font-size: 1.8rem;
            }

            .product-item {
                padding: 1rem;
            }
        }
    </style>
    </head>
    <body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="main-container">
                <div class="page-header">
                    <h1 class="page-title">
                        <i class="bi bi-cart3"></i> Carrinho de Compras
                    </h1>
                </div>

                <div class="p-4">
                    <?php if (empty($cart)): ?>
                        <div class="empty-cart">
                            <i class="bi bi-cart-x"></i>
                            <h3 class="mt-3 mb-3">Seu carrinho está vazio</h3>
                            <p class="mb-4">Descubra nossos produtos incríveis e comece suas compras!</p>
                            <a href="<?= base_url('products') ?>" class="btn btn-primary"
                               style="border-radius: 25px; padding: 0.75rem 1.5rem; font-size: 0.95rem; font-weight: 500;">
                                <i class="bi bi-shop me-2"></i>Explorar Produtos
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <!-- Lista de Produtos -->
                            <div class="col-lg-8 mb-4">
                                <div class="product-card">
                                    <div class="section-header">
                                        <i class="bi bi-bag-check me-2"></i>Produtos Selecionados
                                    </div>
                                    <div class="card-body p-0">
                                        <?php
                                        $subtotal = 0;
                                        foreach ($cart as $key => $item):
                                            $subtotal += $item['price'] * $item['quantity'];
                                            ?>
                                            <div class="product-item">
                                                <div class="row align-items-center">
                                                    <div class="col-md-6">
                                                        <h6 class="product-name"><?= htmlspecialchars($item['name']) ?></h6>
                                                        <div class="product-variation">
                                                            <i class="bi bi-tag me-1"></i><?= htmlspecialchars($item['variation']) ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 text-center">
                                                            <span class="quantity-badge">
                                                                <i class="bi bi-box me-1"></i><?= $item['quantity'] ?>
                                                            </span>
                                                    </div>
                                                    <div class="col-md-3 text-end">
                                                        <div class="price-text">
                                                            R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></div>
                                                    </div>
                                                    <div class="col-md-1 text-end">
                                                        <button type="button"
                                                                class="remove-btn"
                                                                onclick="removeItem('<?= $key ?>')"
                                                                title="Remover item">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Resumo e Frete -->
                            <div class="col-lg-4">
                                <div class="summary-card">
                                    <div class="summary-header">
                                        <i class="bi bi-calculator me-2"></i>Resumo do Pedido
                                    </div>
                                    <div class="summary-body">
                                        <div class="mb-4">
                                            <label class="form-label fw-bold mb-3">
                                                <i class="bi bi-geo-alt me-2"></i>Calcular Frete
                                            </label>
                                            <div class="cep-input-group">
                                                <div class="input-group">
                                                    <input type="text" id="cep" class="form-control cep-input"
                                                           placeholder="Digite seu CEP" maxlength="9">
                                                    <button type="button" onclick="calculateShipping()"
                                                            class="btn cep-btn">
                                                        <i class="bi bi-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div id="address-info" class="address-info" style="display: none;">
                                                <i class="bi bi-house me-2"></i>
                                                <span id="address-text"></span>
                                            </div>
                                        </div>

                                        <div class="summary-line">
                                            <span>Subtotal:</span>
                                            <strong>R$ <?= number_format($subtotal, 2, ',', '.') ?></strong>
                                        </div>
                                        <div class="summary-line">
                                            <span>Frete:</span>
                                            <strong id="shipping-cost">A calcular</strong>
                                        </div>

                                        <!-- Seção de Cupom -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold mb-3">
                                                <i class="bi bi-tag me-2"></i>Cupom de Desconto
                                            </label>
                                            <div class="input-group">
                                                <input type="text" id="coupon-code" class="form-control"
                                                       placeholder="Digite o código do cupom"
                                                       style="text-transform: uppercase;">
                                                <button type="button" onclick="applyCoupon()"
                                                        class="btn btn-outline-primary">
                                                    <i class="bi bi-check"></i> Aplicar
                                                </button>
                                            </div>
                                            <div id="coupon-error" class="text-danger small mt-1"
                                                 style="display: none;"></div>
                                            <div id="coupon-success" class="alert alert-success mt-2"
                                                 style="display: none;">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong id="applied-coupon-code"></strong><br>
                                                        <small id="applied-coupon-discount"></small>
                                                    </div>
                                                    <button type="button" onclick="removeCoupon()"
                                                            class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="summary-line" id="coupon-discount-line" style="display: none;">
                                            <span>Desconto:</span>
                                            <strong class="text-success" id="coupon-discount-amount">- R$ 0,00</strong>
                                        </div>

                                        <div class="summary-total">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fw-bold">Total:</span>
                                                <span class="total-amount"
                                                      id="total-cost">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <button type="button" onclick="finalizeOrder()" class="checkout-btn">
                                                <i class="bi bi-credit-card me-2"></i>Finalizar Compra
                                            </button>
                                            <button type="button" onclick="clearCart()" class="clear-cart-btn">
                                                <i class="bi bi-trash me-2"></i>Limpar Carrinho
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <div class="modal-header"
                 style="background: linear-gradient(135deg, #2c3e50, #34495e); color: white; border-radius: 15px 15px 0 0; border: none;">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Ação</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <div class="text-center mb-3">
                    <i class="bi bi-question-circle-fill" style="font-size: 3rem; color: #f39c12;"></i>
                </div>
                <p id="confirmModalMessage" class="text-center mb-0" style="font-size: 1.1rem; color: #2c3e50;">Tem
                    certeza que deseja realizar esta ação?</p>
            </div>
            <div class="modal-footer" style="border: none; padding: 1rem 2rem 2rem;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 25px; padding: 0.5rem 1.5rem;">Cancelar
                </button>
                <button type="button" class="btn btn-danger" id="confirmModalConfirm"
                        style="border-radius: 25px; padding: 0.5rem 1.5rem;">Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Checkout -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <div class="modal-header"
                 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0; border: none;">
                <h5 class="modal-title" id="checkoutModalLabel"><i class="bi bi-credit-card me-2"></i>Finalizar Pedido
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <div id="checkout-alert-container"></div>

                <form id="checkout-form">
                    <div class="row">
                        <div class="col-lg-8">
                            <h4 class="mb-3"
                                style="color: #2c3e50; border-bottom: 2px solid #667eea; padding-bottom: 10px;"><i
                                    class="bi bi-person me-2"></i>Dados Pessoais</h4>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="checkout_customer_name" class="form-label">Nome Completo *</label>
                                    <input type="text" class="form-control" id="checkout_customer_name"
                                           name="customer_name" required
                                           style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px 15px;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="checkout_customer_email" class="form-label">E-mail *</label>
                                    <input type="email" class="form-control" id="checkout_customer_email"
                                           name="customer_email" required
                                           style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px 15px;">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="checkout_customer_phone" class="form-label">Telefone *</label>
                                    <input type="tel" class="form-control" id="checkout_customer_phone"
                                           name="customer_phone" required
                                           style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px 15px;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="checkout_customer_cep" class="form-label">CEP *</label>
                                    <input type="text" class="form-control" id="checkout_customer_cep"
                                           name="customer_cep" required maxlength="9" placeholder="00000-000"
                                           style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px 15px;">
                                </div>
                            </div>

                            <h4 class="mb-3 mt-4"
                                style="color: #2c3e50; border-bottom: 2px solid #667eea; padding-bottom: 10px;"><i
                                    class="bi bi-geo-alt me-2"></i>Endereço de Entrega</h4>

                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="checkout_customer_address" class="form-label">Endereço *</label>
                                    <input type="text" class="form-control" id="checkout_customer_address"
                                           name="customer_address" required
                                           style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px 15px;">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="checkout_customer_number" class="form-label">Número *</label>
                                    <input type="text" class="form-control" id="checkout_customer_number"
                                           name="customer_number" required
                                           style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px 15px;">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="checkout_customer_complement" class="form-label">Complemento</label>
                                    <input type="text" class="form-control" id="checkout_customer_complement"
                                           name="customer_complement"
                                           style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px 15px;">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="checkout_customer_neighborhood" class="form-label">Bairro *</label>
                                    <input type="text" class="form-control" id="checkout_customer_neighborhood"
                                           name="customer_neighborhood" required
                                           style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px 15px;">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="checkout_customer_city" class="form-label">Cidade *</label>
                                    <input type="text" class="form-control" id="checkout_customer_city"
                                           name="customer_city" required
                                           style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px 15px;">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="checkout_customer_state" class="form-label">Estado *</label>
                                    <select class="form-control" id="checkout_customer_state" name="customer_state"
                                            required
                                            style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px 15px;">
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


                        </div>

                        <div class="col-lg-4">
                            <div
                                style="background: #f8f9fa; border-radius: 10px; padding: 20px; position: sticky; top: 20px;">
                                <h4 class="mb-3"
                                    style="color: #2c3e50; border-bottom: 2px solid #667eea; padding-bottom: 10px;"><i
                                        class="bi bi-receipt me-2"></i>Resumo do Pedido</h4>

                                <div id="checkout-order-items">
                                    <?php foreach ($cart as $item): ?>
                                        <div
                                            class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                            <div>
                                                <strong><?= $item['name'] ?></strong><br>
                                                <small class="text-muted"><?= $item['variation'] ?> -
                                                    Qtd: <?= $item['quantity'] ?></small>
                                            </div>
                                            <div class="text-end">
                                                <strong>R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></strong>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="d-flex justify-content-between py-2">
                                    <span>Subtotal:</span>
                                    <span id="checkout-subtotal">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                                </div>

                                <div class="d-flex justify-content-between py-2" id="checkout-discount-row"
                                     style="display: none !important;">
                                    <span>Desconto:</span>
                                    <span id="checkout-discount" class="text-success">R$ 0,00</span>
                                </div>

                                <div class="d-flex justify-content-between py-2">
                                    <span>Frete:</span>
                                    <span id="checkout-shipping">R$ <?= number_format(20.00, 2, ',', '.') ?></span>
                                </div>

                                <div class="d-flex justify-content-between py-2 border-top pt-3 mt-2"
                                     style="font-weight: 600; font-size: 1.1em; color: #333;">
                                    <span>Total:</span>
                                    <span
                                        id="checkout-total">R$ <?= number_format($subtotal + 20.00, 2, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border: none; padding: 1rem 2rem 2rem;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 25px; padding: 0.75rem 1.5rem;">Cancelar
                </button>
                <button type="submit" form="checkout-form" class="btn" id="checkout-submit-btn"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 25px; padding: 0.75rem 2rem; color: white; font-weight: 600;">
                        <span class="checkout-normal-text">
                            <i class="bi bi-credit-card me-2"></i>Finalizar Pedido
                        </span>
                    <span class="checkout-loading" style="display: none;">
                            <i class="bi bi-hourglass-split me-2"></i>Processando...
                        </span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Função para mostrar toast
    function showToast(title, message, type = 'info') {
        const toastContainer = document.getElementById('toast-container');
        const toastId = 'toast-' + Date.now();

        const bgColor = {
            'success': 'bg-success',
            'error': 'bg-danger',
            'warning': 'bg-warning',
            'info': 'bg-info'
        }[type] || 'bg-info';

        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `toast align-items-center text-white ${bgColor} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}</strong><br>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;

        toastContainer.appendChild(toast);

        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 5000
        });

        bsToast.show();

        toast.addEventListener('hidden.bs.toast', function () {
            toast.remove();
        });
    }

    function showConfirmModal(title, message, onConfirm) {
        const modal = document.getElementById('confirmModal');
        const modalTitle = document.getElementById('confirmModalLabel');
        const modalMessage = document.getElementById('confirmModalMessage');
        const confirmBtn = document.getElementById('confirmModalConfirm');

        modalTitle.textContent = title;
        modalMessage.textContent = message;

        // Remove event listeners anteriores
        const newConfirmBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

        // Adiciona novo event listener
        newConfirmBtn.addEventListener('click', function () {
            const bsModal = bootstrap.Modal.getInstance(modal);
            bsModal.hide();
            onConfirm();
        });

        const bsModal = new bootstrap.Modal(modal);

        // Remove aria-hidden quando o modal for mostrado para evitar conflitos de acessibilidade
        modal.addEventListener('shown.bs.modal', function () {
            modal.removeAttribute('aria-hidden');
        });

        // Restaura aria-hidden quando o modal for escondido
        modal.addEventListener('hidden.bs.modal', function () {
            modal.setAttribute('aria-hidden', 'true');
        });

        bsModal.show();
    }

    // Máscara para CEP
    const cepInput = document.getElementById('cep');
    if (cepInput) {
        const cepMask = IMask(cepInput, {
            mask: '00000-000'
        });
    }

    // Variável global para armazenar dados de endereço
    window.lastAddressData = null;

    function calculateShipping() {
        const cep = cepInput.value.replace(/\D/g, '');

        if (cep.length !== 8) {
            showToast('Erro', 'Digite um CEP válido com 8 dígitos', 'error');
            return;
        }


        document.getElementById('shipping-cost').innerHTML = '<i class="bi bi-hourglass-split"></i> Calculando...';

        const subtotal = <?= isset($subtotal) ? $subtotal : 0 ?>;

        // Primeiro busca dados do CEP
        fetch('<?= base_url("products/calculate_shipping") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                cep: cep,
                subtotal: subtotal
            })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Resposta não é JSON válido');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Armazena dados de endereço globalmente
                    if (data.cep_data) {
                        window.lastAddressData = {
                            cep: cep,
                            street: data.cep_data.logradouro,
                            neighborhood: data.cep_data.bairro,
                            city: data.cep_data.localidade,
                            state: data.cep_data.uf
                        };

                        document.getElementById('address-text').textContent =
                            `${data.cep_data.logradouro}, ${data.cep_data.bairro}, ${data.cep_data.localidade} - ${data.cep_data.uf}`;
                        document.getElementById('address-info').style.display = 'block';
                    }

                    // Armazenar dados de frete na sessão
                    return fetch('<?= base_url("products/store_shipping_data") ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: `cep=${encodeURIComponent(cep)}&subtotal=${subtotal}`
                    });
                } else {
                    throw new Error('CEP não encontrado');
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {

                    document.getElementById('shipping-cost').innerHTML =
                        data.shipping_cost === 0 ? '<span class="text-success"><i class="bi bi-gift"></i> Grátis</span>' : `R$ ${data.shipping_cost.toFixed(2).replace('.', ',')}`;

                    updateTotal();
                    showToast('Sucesso', 'Frete calculado com sucesso!', 'success');
                } else {
                    throw new Error('Erro ao armazenar dados de frete');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro', 'Erro ao calcular frete. Tente novamente.', 'error');
                document.getElementById('shipping-cost').textContent = 'A calcular';
            });
    }

    let appliedCoupon = null;
    let couponDiscount = 0;

    function applyCoupon() {
        const couponCode = document.getElementById('coupon-code').value.trim();

        if (!couponCode) {
            showToast('Atenção', 'Digite um código de cupom', 'warning');
            return;
        }

        const subtotal = <?= isset($subtotal) ? $subtotal : 0 ?>;

        fetch('<?= base_url("products/store_coupon_data") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `coupon_code=${encodeURIComponent(couponCode)}&subtotal=${subtotal}`
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Resposta não é JSON válido');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.valid) {
                    appliedCoupon = data.coupon;
                    couponDiscount = data.discount;

                    document.getElementById('coupon-error').style.display = 'none';
                    document.getElementById('coupon-success').style.display = 'block';
                    document.getElementById('applied-coupon-code').textContent = couponCode;
                    document.getElementById('applied-coupon-discount').textContent = `Desconto: ${data.discount_formatted}`;
                    document.getElementById('coupon-discount-amount').textContent = `- ${data.discount_formatted}`;
                    document.getElementById('coupon-discount-line').style.display = 'flex';

                    updateTotal();
                    showToast('Sucesso', 'Cupom aplicado com sucesso!', 'success');
                } else if (data.success && !data.valid) {
                    document.getElementById('coupon-success').style.display = 'none';
                    document.getElementById('coupon-error').style.display = 'block';
                    document.getElementById('coupon-error').textContent = data.message;
                } else {
                    throw new Error('Erro ao processar cupom');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro', 'Erro ao validar cupom', 'error');
            });
    }

    function removeCoupon() {
        fetch('<?= base_url("products/remove_coupon") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    appliedCoupon = null;
                    couponDiscount = 0;

                    document.getElementById('coupon-code').value = '';
                    document.getElementById('coupon-error').style.display = 'none';
                    document.getElementById('coupon-success').style.display = 'none';
                    document.getElementById('coupon-discount-line').style.display = 'none';

                    updateTotal();
                    showToast('Sucesso', 'Cupom removido', 'success');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro', 'Erro ao remover cupom', 'error');
            });
    }

    function updateTotal() {
        const subtotal = <?= isset($subtotal) ? $subtotal : 0 ?>;
        const shippingText = document.getElementById('shipping-cost').textContent;
        let shipping = 0;

        if (shippingText !== 'A calcular' && !shippingText.includes('Grátis')) {
            shipping = parseFloat(shippingText.replace('R$ ', '').replace(',', '.'));
        }

        const total = subtotal + shipping - couponDiscount;
        document.getElementById('total-cost').textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
    }

    function loadCheckoutData() {
        return fetch('<?= base_url("products/get_checkout_data") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualizar valores no modal
                    document.getElementById('checkout-subtotal').textContent = data.subtotal_formatted;
                    document.getElementById('checkout-shipping').textContent = data.shipping_formatted;
                    document.getElementById('checkout-discount').textContent = data.discount_formatted;
                    document.getElementById('checkout-total').textContent = data.total_formatted;

                    // Mostrar/ocultar linha de desconto
                    const discountLine = document.getElementById('checkout-discount-row');
                    if (data.coupon_discount > 0) {
                        discountLine.style.display = 'flex';
                        discountLine.style.removeProperty('display');
                    } else {
                        discountLine.style.display = 'none';
                    }

                    return data;
                } else {
                    throw new Error(data.message || 'Erro ao carregar dados do checkout');
                }
            });
    }

    function finalizeOrder() {
        // Verificar se há itens no carrinho
        const cart = <?= json_encode($cart ?? []) ?>;
        if (!cart || cart.length === 0) {
            showToast('Atenção', 'Seu carrinho está vazio', 'warning');
            return;
        }

        // Limpar campos do modal primeiro
        document.getElementById('checkout_customer_cep').value = '';
        document.getElementById('checkout_customer_address').value = '';
        document.getElementById('checkout_customer_neighborhood').value = '';
        document.getElementById('checkout_customer_city').value = '';
        document.getElementById('checkout_customer_state').value = '';
        document.getElementById('checkout_customer_number').value = '';
        document.getElementById('checkout_customer_complement').value = '';

        // Carregar dados do checkout da sessão
        loadCheckoutData()
            .then(checkoutData => {
                // Verificar se há dados de endereço preenchidos fora do modal
                const cepField = document.getElementById('cep');
                const addressInfo = document.getElementById('address-info');
                let hasAddressData = false;

                if (cepField && cepField.value && addressInfo && addressInfo.style.display !== 'none') {

                    document.getElementById('checkout_customer_cep').value = cepField.value;

                    // Verificar se há dados de endereço armazenados globalmente
                    if (window.lastAddressData) {
                        document.getElementById('checkout_customer_address').value = window.lastAddressData.street || '';
                        document.getElementById('checkout_customer_neighborhood').value = window.lastAddressData.neighborhood || '';
                        document.getElementById('checkout_customer_city').value = window.lastAddressData.city || '';
                        document.getElementById('checkout_customer_state').value = window.lastAddressData.state || '';
                        hasAddressData = true;
                    } else {
                        // Tentar extrair do texto visível como fallback
                        const addressText = addressInfo.textContent.trim();
                        const lines = addressText.split('\n').map(line => line.trim()).filter(line => line);

                        if (lines.length >= 3) {
                            document.getElementById('checkout_customer_address').value = lines[0] || '';
                            document.getElementById('checkout_customer_neighborhood').value = lines[1] || '';

                            const cityState = lines[2].split(' - ');
                            if (cityState.length === 2) {
                                document.getElementById('checkout_customer_city').value = cityState[0] || '';
                                document.getElementById('checkout_customer_state').value = cityState[1] || '';
                            }
                            hasAddressData = true;
                        }
                    }
                }

                // Abrir o modal de checkout
                const checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
                checkoutModal.show();

                // Se não há dados de endereço, focar no campo CEP e mostrar alerta
                if (!hasAddressData) {
                    setTimeout(() => {
                        document.getElementById('checkout_customer_cep').focus();
                        showCheckoutAlert('Por favor, preencha o CEP para calcular o frete e continuar com o pedido.', 'info');
                    }, 500);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro', 'Erro ao carregar dados do checkout', 'error');
            });
    }

    function removeItem(itemKey) {
        showConfirmModal(
            'Confirmar Remoção',
            'Tem certeza que deseja remover este item do carrinho?',
            () => {
                fetch('<?= base_url("products/remove_from_cart/") ?>' + itemKey, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new Error('Resposta não é JSON válido');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showToast('Sucesso', data.message, 'success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showToast('Erro', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        showToast('Erro', 'Erro ao remover item. Tente novamente.', 'error');
                    });
            }
        );
    }

    function clearCart() {
        showConfirmModal(
            'Limpar Carrinho',
            'Tem certeza que deseja remover todos os itens do carrinho?',
            () => {
                fetch('<?= base_url("products/clear_cart") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new Error('Resposta não é JSON válido');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showToast('Sucesso', data.message, 'success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showToast('Erro', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        showToast('Erro', 'Erro ao limpar carrinho. Tente novamente.', 'error');
                    });
            }
        );
    }

    // Calcula frete ao pressionar Enter no CEP
    if (cepInput) {
        cepInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                calculateShipping();
            }
        });
    }

    // Aplica cupom ao pressionar Enter
    const couponCodeInput = document.getElementById('coupon-code');
    if (couponCodeInput) {
        couponCodeInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                applyCoupon();
            }
        });
    }

    // Aplicar máscara de CEP no modal de checkout
    document.getElementById('checkout_customer_cep').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 5) {
            value = value.substring(0, 5) + '-' + value.substring(5, 8);
        }
        e.target.value = value;

        // Buscar endereço automaticamente quando CEP estiver completo
        if (value.length === 9) {
            searchAddressByCheckoutCep(value);
        }
    });

    // Função para buscar endereço via ViaCEP no modal de checkout
    function searchAddressByCheckoutCep(cep) {
        const cleanCep = cep.replace(/\D/g, '');

        if (cleanCep.length !== 8) {
            return;
        }

        fetch(`https://viacep.com.br/ws/${cleanCep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    document.getElementById('checkout_customer_address').value = data.logradouro || '';
                    document.getElementById('checkout_customer_neighborhood').value = data.bairro || '';
                    document.getElementById('checkout_customer_city').value = data.localidade || '';
                    document.getElementById('checkout_customer_state').value = data.uf || '';

                    // Focar no campo número
                    document.getElementById('checkout_customer_number').focus();
                }
            })
            .catch(error => {
                console.error('Erro ao buscar CEP:', error);
            });
    }


    // Função para exibir alertas no modal de checkout
    function showCheckoutAlert(message, type) {
        const alertContainer = document.getElementById('checkout-alert-container');
        alertContainer.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
    }

    // Submissão do formulário de checkout
    document.getElementById('checkout-form').addEventListener('submit', function (e) {
        e.preventDefault();

        // Validar se o CEP foi preenchido e o endereço está completo
        const cep = document.getElementById('checkout_customer_cep').value.trim();
        const address = document.getElementById('checkout_customer_address').value.trim();
        const neighborhood = document.getElementById('checkout_customer_neighborhood').value.trim();
        const city = document.getElementById('checkout_customer_city').value.trim();
        const state = document.getElementById('checkout_customer_state').value.trim();

        if (!cep || cep.length !== 9) {
            showCheckoutAlert('Por favor, preencha um CEP válido.', 'warning');
            document.getElementById('checkout_customer_cep').focus();
            return;
        }

        if (!address || !neighborhood || !city || !state) {
            showCheckoutAlert('Por favor, preencha o CEP para buscar o endereço automaticamente.', 'warning');
            document.getElementById('checkout_customer_cep').focus();
            return;
        }

        const submitBtn = document.getElementById('checkout-submit-btn');
        const normalText = submitBtn.querySelector('.checkout-normal-text');
        const loadingText = submitBtn.querySelector('.checkout-loading');

        // Mostrar estado de carregamento
        normalText.style.display = 'none';
        loadingText.style.display = 'inline';
        submitBtn.disabled = true;

        const formData = new FormData(this);

        fetch('<?= base_url("products/finalize_order") ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showCheckoutAlert('Pedido finalizado com sucesso! Você receberá um e-mail de confirmação.', 'success');

                    setTimeout(() => {
                        window.location.href = '<?= base_url() ?>';
                    }, 2000);
                } else {
                    showCheckoutAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showCheckoutAlert('Erro ao processar pedido. Tente novamente.', 'danger');
            })
            .finally(() => {
                // Restaurar estado do botão
                normalText.style.display = 'inline';
                loadingText.style.display = 'none';
                submitBtn.disabled = false;
            });
    });

    // Função para carregar dados da sessão na inicialização
    function loadSessionData() {
        fetch('<?= base_url("products/get_checkout_data") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualizar dados de frete se existirem
                    if (data.shipping_data && data.shipping_data.cep) {
                        const cepField = document.getElementById('cep');
                        if (cepField) {
                            cepField.value = data.shipping_data.cep;

                            // Mostrar informações de endereço
                            const addressInfo = document.getElementById('address-info');
                            if (addressInfo && data.shipping_data.address) {
                                addressInfo.innerHTML = `
                                    <strong>Endereço:</strong><br>
                                    ${data.shipping_data.address}<br>
                                    ${data.shipping_data.neighborhood}<br>
                                    ${data.shipping_data.city} - ${data.shipping_data.state}
                                `;
                                addressInfo.style.display = 'block';
                            }

                            // Atualizar custo de frete
                            const shippingCost = document.getElementById('shipping-cost');
                            if (shippingCost) {
                                shippingCost.textContent = data.shipping_formatted;
                            }
                        }
                    }

                    // Atualizar dados de cupom se existirem
                    if (data.coupon_data && data.coupon_data.code) {
                        appliedCoupon = data.coupon_data;
                        couponDiscount = data.coupon_discount;

                        const couponCodeField = document.getElementById('coupon-code');
                        if (couponCodeField) {
                            couponCodeField.value = data.coupon_data.code;
                        }

                        // Mostrar cupom aplicado
                        document.getElementById('coupon-error').style.display = 'none';
                        document.getElementById('coupon-success').style.display = 'block';
                        document.getElementById('applied-coupon-code').textContent = data.coupon_data.code;
                        document.getElementById('applied-coupon-discount').textContent = `Desconto: ${data.discount_formatted}`;
                        document.getElementById('coupon-discount-amount').textContent = `- ${data.discount_formatted}`;
                        document.getElementById('coupon-discount-line').style.display = 'flex';
                    }


                    updateTotal();
                }
            })
            .catch(error => {
                console.error('Erro ao carregar dados da sessão:', error);
            });
    }

    // Animação suave ao carregar
    document.addEventListener('DOMContentLoaded', function () {
        const elements = document.querySelectorAll('.product-item');
        elements.forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            setTimeout(() => {
                el.style.transition = 'all 0.5s ease';
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // Carregar dados da sessão
        loadSessionData();
    });
</script>

<?php $this->load->view('layouts/footer'); ?>
