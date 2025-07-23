<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação do Pedido #<?= $order_id ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .header .order-number {
            font-size: 18px;
            margin-top: 10px;
            opacity: 0.9;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .order-details {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .order-details h3 {
            margin-top: 0;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
        }
        .customer-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .info-item {
            background-color: white;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #007bff;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            font-size: 16px;
            color: #2c3e50;
            margin-top: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .items-table th {
            background-color: #495057;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .items-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .price {
            font-weight: 600;
            color: #28a745;
        }
        .total-section {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }
        .total-row.final {
            border-top: 2px solid #007bff;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 18px;
            font-weight: 700;
            color: #2c3e50;
        }
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .footer p {
            margin: 5px 0;
        }
        .contact-info {
            margin-top: 20px;
            font-size: 14px;
            opacity: 0.8;
        }
        @media (max-width: 600px) {
            .customer-info {
                grid-template-columns: 1fr;
            }
            .items-table {
                font-size: 14px;
            }
            .items-table th,
            .items-table td {
                padding: 10px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎉 Pedido Confirmado!</h1>
            <div class="order-number">Pedido #<?= $order_id ?></div>
        </div>
        
        <div class="content">
            <div class="greeting">
                Olá, <strong><?= $order->customer_name ?></strong>!
            </div>
            
            <p>Obrigado por escolher a <strong>Montink</strong>! Seu pedido foi confirmado e está sendo processado.</p>
            
            <div class="order-details">
                <h3>📋 Detalhes do Pedido</h3>
                
                <div class="customer-info">
                    <div class="info-item">
                        <div class="info-label">Nome</div>
                        <div class="info-value"><?= $order->customer_name ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">E-mail</div>
                        <div class="info-value"><?= $order->customer_email ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Telefone</div>
                        <div class="info-value"><?= $order->customer_phone ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">CEP</div>
                        <div class="info-value"><?= $order->customer_cep ?></div>
                    </div>
                </div>
                
                <?php if (!empty($order->customer_address)): ?>
                <div class="info-item" style="grid-column: 1 / -1; margin-top: 15px;">
                    <div class="info-label">Endereço de Entrega</div>
                    <div class="info-value">
                        <?= $order->customer_address ?>
                        <?php if (!empty($order->customer_number)): ?>, <?= $order->customer_number ?><?php endif; ?>
                        <?php if (!empty($order->customer_complement)): ?>, <?= $order->customer_complement ?><?php endif; ?><br>
                        <?php if (!empty($order->customer_neighborhood)): ?><?= $order->customer_neighborhood ?>, <?php endif; ?>
                        <?= $order->customer_city ?> - <?= $order->customer_state ?><br>
                        CEP: <?= $order->customer_cep ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="order-details">
                <h3>🛍️ Itens do Pedido</h3>
                
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Variação</th>
                            <th>Qtd</th>
                            <th>Preço Unit.</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td><strong><?= $item['name'] ?></strong></td>
                            <td><?= $item['variation'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td class="price">R$ <?= number_format($item['price'], 2, ',', '.') ?></td>
                            <td class="price">R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span class="price">R$ <?= number_format($order->subtotal, 2, ',', '.') ?></span>
                </div>
                
                <?php if (isset($order->discount) && $order->discount > 0): ?>
                <div class="total-row">
                    <span>Desconto<?php if (isset($order->coupon_code) && !empty($order->coupon_code)): ?> (Cupom: <?= $order->coupon_code ?>)<?php endif; ?>:</span>
                    <span class="price">- R$ <?= number_format($order->discount, 2, ',', '.') ?></span>
                </div>
                <?php endif; ?>
                
                <div class="total-row">
                    <span>Frete:</span>
                    <span class="price">
                        <?php if (isset($order->shipping) && $order->shipping == 0): ?>
                            <span style="color: #28a745;">GRÁTIS</span>
                        <?php else: ?>
                            R$ <?= number_format($order->shipping ?? 0, 2, ',', '.') ?>
                        <?php endif; ?>
                    </span>
                </div>
                
                <div class="total-row final">
                    <span>Total:</span>
                    <span>R$ <?= number_format($order->total, 2, ',', '.') ?></span>
                </div>
            </div>
            
            <p style="margin-top: 30px; padding: 20px; background-color: #e3f2fd; border-radius: 8px; border-left: 4px solid #2196f3;">
                <strong>📦 Próximos passos:</strong><br>
                Seu pedido será processado em até 1 dia útil. Você receberá um e-mail com o código de rastreamento assim que o produto for enviado.
            </p>
        </div>
        
        <div class="footer">
            <p><strong>Montink</strong></p>
            <p>Obrigado por confiar em nossos produtos!</p>
            
            <div class="contact-info">
                <p>📧 contato@montink.com | 📱 (11) 99999-9999</p>
                <p>🌐 www.montink.com</p>
            </div>
        </div>
    </div>
</body>
</html>