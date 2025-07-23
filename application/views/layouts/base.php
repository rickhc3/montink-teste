<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Montink' ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

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

        [v-cloak] {
            display: none;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 2rem;
        }

        .page-title {
            font-weight: 300;
            font-size: 2.2rem;
            margin: 0;
        }

        .page-title i {
            margin-right: 0.5rem;
            opacity: 0.9;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color) !important;
        }

        .card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            transition: all 0.2s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .btn {
            border-radius: 4px;
            padding: 0.4rem 0.8rem;
            font-weight: 500;
            transition: all 0.15s ease;
            border: 1px solid transparent;
            font-size: 0.875rem;
        }

        /* Botões de ação primária - azul para criar/salvar */
        .btn-primary {
            background: var(--accent-color);
            border: 1px solid var(--accent-color);
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
            border-color: #2980b9;
            transform: translateY(-1px);
        }

        /* Botões de sucesso - verde para adicionar/confirmar */
        .btn-success {
            background: var(--success-color);
            border: 1px solid var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background: #229954;
            border-color: #229954;
            transform: translateY(-1px);
        }

        /* Botões de perigo - vermelho para excluir */
        .btn-danger {
            background: var(--danger-color);
            border: 1px solid var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
            border-color: #c0392b;
            transform: translateY(-1px);
        }

        /* Botões secundários - cinza para cancelar/voltar */
        .btn-secondary, .btn-light {
            background: var(--medium-gray);
            border: 1px solid var(--medium-gray);
            color: white;
        }

        .btn-secondary:hover, .btn-light:hover {
            background: #5a6268;
            border-color: #5a6268;
            transform: translateY(-1px);
        }

        /* Botões outline - mais discretos */
        .btn-outline-primary {
            background: transparent;
            border: 1px solid rgba(52, 152, 219, 0.3);
            color: var(--accent-color);
        }

        .btn-outline-primary:hover {
            background: rgba(52, 152, 219, 0.1);
            border-color: var(--accent-color);
            color: var(--accent-color);
        }

        .btn-outline-secondary {
            background: transparent;
            border: 1px solid rgba(108, 117, 125, 0.3);
            color: var(--medium-gray);
        }

        .btn-outline-secondary:hover {
            background: rgba(108, 117, 125, 0.1);
            border-color: var(--medium-gray);
            color: var(--medium-gray);
        }

        .btn-outline-danger {
            background: transparent;
            border: 1px solid rgba(231, 76, 60, 0.3);
            color: var(--danger-color);
        }

        .btn-outline-danger:hover {
            background: rgba(231, 76, 60, 0.1);
            border-color: var(--danger-color);
            color: var(--danger-color);
        }

        /* Botões pequenos ainda mais discretos */
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            border-radius: 3px;
        }

        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
            padding: 0.75rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }

        .badge {
            border-radius: 4px;
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge.bg-success {
            background: linear-gradient(135deg, var(--success-color), #229954) !important;
        }

        .badge.bg-warning {
            background: linear-gradient(135deg, var(--warning-color), #e67e22) !important;
        }

        .badge.bg-danger {
            background: linear-gradient(135deg, var(--danger-color), #c0392b) !important;
        }

        .modal-content {
            border: none;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
            border-radius: 8px 8px 0 0;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 6px;
            backdrop-filter: blur(10px);
        }

        .toast {
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .fade-enter-active, .fade-leave-active {
            transition: opacity 0.3s;
        }
        .fade-enter-from, .fade-leave-to {
            opacity: 0;
        }

        .slide-enter-active, .slide-leave-active {
            transition: all 0.3s ease;
        }
        .slide-enter-from {
            transform: translateX(-100%);
            opacity: 0;
        }
        .slide-leave-to {
            transform: translateX(100%);
            opacity: 0;
        }

        .hover-lift {
            transition: all 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1) !important;
        }

        .btn-check:checked + .btn {
            background: linear-gradient(135deg, var(--accent-color), #2980b9);
            border-color: var(--accent-color);
            color: #fff;
        }

        .btn-group .btn {
            border-radius: 6px;
        }

        .btn-group .btn:not(:last-child) {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .btn-group .btn:not(:first-child) {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        @media (max-width: 768px) {
            .main-container {
                margin: 1rem;
                border-radius: 8px;
            }

            .page-header {
                padding: 1.5rem;
                border-radius: 8px 8px 0 0;
            }

            .page-title {
                font-size: 1.8rem;
            }
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

    <div id="app" v-cloak>
        <?= $content ?? '' ?>
    </div>

    <!-- Toast Container Global -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong id="toast-title" class="me-auto">Notificação</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toast-message">
                Mensagem aqui
            </div>
        </div>
    </div>

    <!-- Scripts - Ordem importante -->
    <script src="https://unpkg.com/vue@3.3.4/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/imask"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Configurações globais -->
    <script>
        // Configuração global do Axios
        axios.defaults.baseURL = '<?= base_url() ?>';
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

        // Configuração global do Bootstrap
        window.bootstrap = bootstrap;


        window.utils = {

            formatPrice: (value) => {
                if (value === null || value === undefined || value === '') {
                    return '';
                }

                let numericValue = value;
                if (typeof value === 'string') {
                    numericValue = parseFloat(value);
                    if (isNaN(numericValue)) {
                        return '';
                    }
                }

                return new Intl.NumberFormat('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                }).format(numericValue);
            },

            parsePrice: (value) => {
                if (value === null || value === undefined || value === '') {
                    return 0;
                }

                if (typeof value === 'number') {
                    return value;
                }

                if (typeof value === 'string') {
                    let cleanValue = value.replace(/[^\d,.]/g, '');

                    if (cleanValue.includes(',') && cleanValue.includes('.')) {
                        cleanValue = cleanValue.replace('.', '').replace(',', '.');
                    } else if (cleanValue.includes(',')) {
                        cleanValue = cleanValue.replace(',', '.');
                    }

                    const result = parseFloat(cleanValue);
                    return isNaN(result) ? 0 : result;
                }

                return 0;
            },

            applyPriceMask: (element) => {
                if (!element) {
                    return null;
                }

                try {
                    const mask = IMask(element, {
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

                    return mask;
                } catch (error) {
                    console.error('Erro ao criar máscara:', error);
                    return null;
                }
            },

            showToast: (title, message, type = 'info') => {
                const toast = document.getElementById('toast');
                const toastTitle = document.getElementById('toast-title');
                const toastMessage = document.getElementById('toast-message');

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
        };
    </script>
</body>
</html>
