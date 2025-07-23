<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualização do Pedido</title>
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
            background-color: #007bff;
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
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        .status-pending { background-color: #ffc107; color: #212529; }
        .status-confirmed { background-color: #28a745; color: white; }
        .status-processing { background-color: #17a2b8; color: white; }
        .status-shipped { background-color: #007bff; color: white; }
        .status-delivered { background-color: #28a745; color: white; }
        .order-info {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
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
        <h1>📦 Atualização do Seu Pedido</h1>
    </div>
    
    <div class="content">
        <h2>Olá, <?= htmlspecialchars($order->customer_name) ?>!</h2>
        
        <p><?= htmlspecialchars($status_message) ?></p>
        
        <div class="order-info">
            <h3>Detalhes do Pedido</h3>
            <p><strong>Número do Pedido:</strong> #<?= $order->id ?></p>
            <p><strong>Status Atual:</strong> 
                <span class="status-badge status-<?= $new_status ?>"><?= ucfirst($new_status) ?></span>
            </p>
            <p><strong>Total:</strong> R$ <?= number_format($order->total, 2, ',', '.') ?></p>
            <p><strong>Data do Pedido:</strong> <?= date('d/m/Y H:i', strtotime($order->created_at)) ?></p>
        </div>
        
        <?php if ($new_status === 'shipped'): ?>
        <div style="background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 15px; margin: 20px 0;">
            <h4 style="color: #155724; margin: 0 0 10px 0;">🚚 Seu pedido está a caminho!</h4>
            <p style="color: #155724; margin: 0;">Acompanhe a entrega através do código de rastreamento que será enviado em breve.</p>
        </div>
        <?php elseif ($new_status === 'delivered'): ?>
        <div style="background-color: #d1ecf1; border: 1px solid #bee5eb; border-radius: 8px; padding: 15px; margin: 20px 0;">
            <h4 style="color: #0c5460; margin: 0 0 10px 0;">✅ Pedido entregue com sucesso!</h4>
            <p style="color: #0c5460; margin: 0;">Esperamos que você esteja satisfeito com sua compra. Obrigado por escolher a Montink!</p>
        </div>
        <?php endif; ?>
        
        <p>Se você tiver alguma dúvida sobre seu pedido, entre em contato conosco.</p>
        
        <p>Atenciosamente,<br>
        <strong>Equipe Montink</strong></p>
    </div>
    
    <div class="footer">
        <p>Este é um e-mail automático, por favor não responda.</p>
        <p>© <?= date('Y') ?> Montink. Todos os direitos reservados.</p>
    </div>
</body>
</html>