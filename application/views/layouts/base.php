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
        [v-cloak] {
            display: none;
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
    </style>
</head>
<body class="bg-light">
    <div id="app" v-cloak>
        <?= $content ?? '' ?>
    </div>

    <!-- Toast Container Global -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
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
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
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
        
        // Utilitários globais
        window.utils = {
            // Formatar preço
            formatPrice: (value) => {
                return new Intl.NumberFormat('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                }).format(value);
            },
            
            // Parsear preço
            parsePrice: (value) => {
                if (typeof value === 'string') {
                    return parseFloat(value.replace(/[^\d,]/g, '').replace(',', '.'));
                }
                return value;
            },
            
            // Aplicar máscara de preço
            applyPriceMask: (element) => {
                return IMask(element, {
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
            },
            
            // Mostrar toast
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