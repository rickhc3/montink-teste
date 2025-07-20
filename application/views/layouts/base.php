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
        
        /* Estilos customizados */
        .hover-lift {
            transition: all 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .rounded-4 {
            border-radius: 1rem !important;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
        }
        
        .card {
            transition: all 0.3s ease;
        }
        
        .badge {
            font-size: 0.75rem;
        }
        
        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
        }
        
        .modal-content {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        }
        
        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        /* Estilos para tabela */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }
        
        .btn-check:checked + .btn {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
        }
        
        .btn-group .btn {
            border-radius: 0.375rem;
        }
        
        .btn-group .btn:not(:last-child) {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        
        .btn-group .btn:not(:first-child) {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
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
        
        // Utilitários globais
        window.utils = {
            // Formatar preço
            formatPrice: (value) => {
                console.log('utils.formatPrice() - valor recebido:', value, 'tipo:', typeof value);
                
                if (value === null || value === undefined || value === '') {
                    console.log('utils.formatPrice() - valor vazio ou nulo');
                    return '';
                }
                
                // Converte para número se for string
                let numericValue = value;
                if (typeof value === 'string') {
                    numericValue = parseFloat(value);
                    if (isNaN(numericValue)) {
                        console.log('utils.formatPrice() - string não é número válido');
                        return '';
                    }
                }
                
                console.log('utils.formatPrice() - valor numérico:', numericValue);
                const result = new Intl.NumberFormat('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                }).format(numericValue);
                
                console.log('utils.formatPrice() - resultado formatado:', result);
                return result;
            },
            
            // Parsear preço
            parsePrice: (value) => {
                console.log('utils.parsePrice() - valor recebido:', value, 'tipo:', typeof value);
                
                if (value === null || value === undefined || value === '') {
                    console.log('utils.parsePrice() - valor vazio ou nulo');
                    return 0;
                }
                
                if (typeof value === 'number') {
                    console.log('utils.parsePrice() - já é número:', value);
                    return value;
                }
                
                if (typeof value === 'string') {
                    // Remove tudo exceto números, vírgulas e pontos
                    let cleanValue = value.replace(/[^\d,.]/g, '');
                    console.log('utils.parsePrice() - valor limpo:', cleanValue);
                    
                    // Se tem vírgula e ponto, assume que vírgula é separador decimal
                    if (cleanValue.includes(',') && cleanValue.includes('.')) {
                        cleanValue = cleanValue.replace('.', '').replace(',', '.');
                    } else if (cleanValue.includes(',')) {
                        cleanValue = cleanValue.replace(',', '.');
                    }
                    
                    console.log('utils.parsePrice() - valor final para parse:', cleanValue);
                    const result = parseFloat(cleanValue);
                    console.log('utils.parsePrice() - resultado:', result);
                    return isNaN(result) ? 0 : result;
                }
                
                console.log('utils.parsePrice() - tipo não suportado, retornando 0');
                return 0;
            },
            
            // Aplicar máscara de preço
            applyPriceMask: (element) => {
                console.log('utils.applyPriceMask() - aplicando máscara para elemento:', element);
                
                if (!element) {
                    console.error('utils.applyPriceMask() - elemento não fornecido');
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
                            console.log('utils.applyPriceMask() - parser chamado com:', str);
                            const result = str.replace(/\D/g, '');
                            console.log('utils.applyPriceMask() - parser resultado:', result);
                            return result;
                        },
                        formatter: function (str) {
                            console.log('utils.applyPriceMask() - formatter chamado com:', str);
                            const result = str.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            console.log('utils.applyPriceMask() - formatter resultado:', result);
                            return result;
                        }
                    });
                    
                    console.log('utils.applyPriceMask() - máscara criada com sucesso:', mask);
                    return mask;
                } catch (error) {
                    console.error('utils.applyPriceMask() - erro ao criar máscara:', error);
                    return null;
                }
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