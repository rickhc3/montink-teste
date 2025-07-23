<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Carrinho - Montink' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://unpkg.com/imask"></script>
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --light-gray: #f8f9fa;
            --medium-gray: #6c757d;
            --border-color: #dee2e6;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
        }
        
        .navbar-brand {
            color: var(--primary-color) !important;
        }
        
        .btn-primary {
            background: var(--accent-color);
            border: 1px solid var(--accent-color);
        }
        
        .btn-primary:hover {
            background: #2980b9;
            border-color: #2980b9;
            transform: translateY(-1px);
        }
        
        .btn-outline-secondary {
            border: 1px solid rgba(108, 117, 125, 0.3);
            color: var(--medium-gray);
        }
        
        .btn-outline-secondary:hover {
            background: rgba(108, 117, 125, 0.1);
            border-color: var(--medium-gray);
            color: var(--medium-gray);
        }
        
        .btn-outline-primary {
            border: 1px solid rgba(52, 152, 219, 0.3);
            color: var(--accent-color);
        }

        .btn-outline-primary:hover {
            background: rgba(52, 152, 219, 0.1);
            border-color: var(--accent-color);
            color: var(--accent-color);
        }
    </style>
</head>
<body>
    <!-- Navbar Global -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('products') ?>">
                <i class="bi bi-shop text-primary"></i> Montink
            </a>
            <div class="navbar-nav ms-auto">
                <?php
                $current_url = uri_string();
                $is_products = (strpos($current_url, 'products') !== false && strpos($current_url, 'cart') === false);
                $is_coupons = strpos($current_url, 'coupons') !== false;
                $is_cart = strpos($current_url, 'cart') !== false;
                ?>
                <a href="<?= base_url('products') ?>" class="btn <?= $is_products ? 'btn-primary' : 'btn-outline-secondary' ?> me-2">
                    <i class="bi bi-box"></i> Produtos
                </a>
                <a href="<?= base_url('coupons') ?>" class="btn <?= $is_coupons ? 'btn-primary' : 'btn-outline-secondary' ?> me-2">
                    <i class="bi bi-tag"></i> Cupons
                </a>
                <a href="<?= base_url('cart') ?>" class="btn <?= $is_cart ? 'btn-primary' : 'btn-outline-primary' ?>">
                    <i class="bi bi-cart3"></i> Carrinho
                </a>
            </div>
        </div>
    </nav>