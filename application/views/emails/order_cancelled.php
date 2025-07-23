<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Cancelado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #dc3545;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .order-info {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
        .alert {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #721c24;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>❌ Pedido Cancelado</h1>
    </div>
    
    <div class="content">
        <h2>Olá, <?= htmlspecialchars($order->customer_name) ?>!</h2>
        
        <div class="alert">
            <h4 style="margin: 0 0 10px 0;">Seu pedido foi cancelado</h4>
            <p style="margin: 0;">Informamos que seu pedido foi cancelado conforme solicitado ou devido a questões técnicas.</p>
        </div>
        
        <div class="order-info">
            <h3>Detalhes do Pedido Cancelado</h3>
            <p><strong>Número do Pedido:</strong> #<?= $order->id ?></p>
            <p><strong>Data do Pedido:</strong> <?= date('d/m/Y H:i', strtotime($order->created_at)) ?></p>
            <p><strong>Valor Total:</strong> R$ <?= number_format($order->total, 2, ',', '.') ?></p>
            
            <?php if (!empty($order->coupon_code)): ?>
            <p><strong>Cupom Utilizado:</strong> <?= htmlspecialchars($order->coupon_code) ?></p>
            <?php endif; ?>
        </div>
        
        <div style="background-color: #d1ecf1; border: 1px solid #bee5eb; border-radius: 8px; padding: 15px; margin: 20px 0;">
            <h4 style="color: #0c5460; margin: 0 0 10px 0;">💰 Informações sobre Reembolso</h4>
            <p style="color: #0c5460; margin: 0;">Se você efetuou o pagamento, o reembolso será processado automaticamente em até 5 dias úteis, dependendo da forma de pagamento utilizada.</p>
        </div>
        
        <p>Se você não solicitou o cancelamento ou tem dúvidas sobre este processo, entre em contato conosco imediatamente.</p>
        
        <p>Lamentamos qualquer inconveniente causado e esperamos atendê-lo novamente em breve.</p>
        
        <p>Atenciosamente,<br>
        <strong>Equipe Montink</strong></p>
    </div>
    
    <div class="footer">
        <p>Este é um e-mail automático, por favor não responda.</p>
        <p>Se precisar de ajuda, entre em contato através do nosso suporte.</p>
        <p>© <?= date('Y') ?> Montink. Todos os direitos reservados.</p>
    </div>
</body>
</html>