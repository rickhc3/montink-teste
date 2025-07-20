<div id="app" class="min-vh-100" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <!-- Header -->
    <!-- Navbar elegante -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('products') ?>">
                <i class="bi bi-shop text-primary"></i> Montink
            </a>
            <div class="navbar-nav ms-auto">
                <a href="<?= base_url('products/cart') ?>" class="btn btn-outline-primary me-2">
                    <i class="bi bi-cart3"></i> Carrinho
                </a>
                <button type="button" class="btn btn-primary" @click="showCreateModal">
                    <i class="bi bi-plus-circle"></i> Novo Produto
                </button>
            </div>
        </div>
    </nav>
    
    <!-- Container principal -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="main-container">
                    <div class="page-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="page-title">
                                    <i class="bi bi-shop"></i> Catálogo de Produtos
                                </h1>
                                <p class="mb-0 opacity-75">Gerencie seus produtos e vendas</p>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-light text-dark fs-6 px-3 py-2">
                                    {{ products.length }} {{ products.length === 1 ? 'produto' : 'produtos' }}
                                </span>
                                
                                <!-- Toggle de Visualização -->
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="viewType" id="viewTable" autocomplete="off" v-model="viewType" value="table">
                                    <label class="btn btn-outline-light" for="viewTable">
                                        <i class="bi bi-list-ul"></i>
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="viewType" id="viewCards" autocomplete="off" v-model="viewType" value="cards">
                                    <label class="btn btn-outline-light" for="viewCards">
                                        <i class="bi bi-grid-3x3-gap"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="p-4">
                        <!-- Estado vazio -->
                        <div v-if="products.length === 0" class="text-center py-5">
                            <div class="mb-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="bi bi-box text-primary fs-1"></i>
                                </div>
                            </div>
                            <h3 class="fw-bold text-dark mb-2">Nenhum produto cadastrado</h3>
                            <p class="text-muted mb-4">Comece criando seu primeiro produto e gerencie seu catálogo!</p>
                            <button type="button" class="btn btn-primary btn-lg px-4" @click="showCreateModal">
                                <i class="bi bi-plus-circle me-2"></i> Criar Primeiro Produto
                            </button>
                        </div>
    
                        <!-- Visualização em Tabela -->
                        <div v-else-if="viewType === 'table'" class="card">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="fw-bold text-dark px-4 py-3">Produto</th>
                                            <th class="fw-bold text-dark py-3">Preço</th>
                                            <th class="fw-bold text-dark py-3">Estoque</th>
                                            <th class="fw-bold text-dark py-3 text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="product in products" :key="product.id" class="align-middle">
                                            <td class="px-4 py-3">
                                                <div>
                                                    <h6 class="mb-1 fw-bold text-dark">{{ product.name }}</h6>
                                                    <small class="text-muted">ID: #{{ product.id }}</small>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <span class="fw-bold text-success fs-5">{{ formatPrice(product.price) }}</span>
                                            </td>
                                            <td class="py-3">
                                                <div>
                                                    <span class="badge" :class="getTotalStock(product) > 10 ? 'bg-success' : getTotalStock(product) > 0 ? 'bg-warning' : 'bg-danger'">
                                                        {{ getTotalStock(product) }} total
                                                    </span>
                                                    <div class="mt-1">
                                                        <small class="text-muted">
                                                            <span v-for="(stock, index) in product.stock" :key="stock.variation">
                                                                {{ stock.variation }}: {{ stock.quantity }}{{ index < product.stock.length - 1 ? ' • ' : '' }}
                                                            </span>
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 text-center">
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-success btn-sm"
                                                        @click="showBuyModal(product)"
                                                        :disabled="getTotalStock(product) === 0"
                                                        title="Adicionar ao carrinho"
                                                    >
                                                        <i class="bi bi-cart-plus"></i>
                                                    </button>
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-primary btn-sm"
                                                        @click="showEditModal(product)"
                                                        title="Editar produto"
                                                    >
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-danger btn-sm"
                                                        @click="deleteProduct(product.id)"
                                                        title="Excluir produto"
                                                    >
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
    
                        <!-- Visualização em Cards -->
                        <div v-else class="row g-4">
                            <div v-for="product in products" :key="product.id" class="col-lg-4 col-md-6">
                                <div class="card h-100">
                                    <div class="card-body p-4">
                                        <!-- Header do Card -->
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="flex-grow-1">
                                                <h5 class="card-title fw-bold text-dark mb-1">{{ product.name }}</h5>
                                                <p class="text-muted small mb-0">ID: #{{ product.id }}</p>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light rounded-pill" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" @click="showEditModal(product)">
                                                        <i class="bi bi-pencil me-2"></i>Editar
                                                    </a></li>
                                                    <li><a class="dropdown-item text-danger" href="#" @click="deleteProduct(product.id)">
                                                        <i class="bi bi-trash me-2"></i>Excluir
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
    
                                        <!-- Preço -->
                                        <div class="mb-3">
                                            <span class="h4 fw-bold text-success">{{ formatPrice(product.price) }}</span>
                                        </div>
    
                                        <!-- Estoque -->
                                        <div class="mb-4">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="small text-muted">Estoque Total</span>
                                                <span class="badge" :class="getTotalStock(product) > 10 ? 'bg-success' : getTotalStock(product) > 0 ? 'bg-warning' : 'bg-danger'">
                                                    {{ getTotalStock(product) }} unidades
                                                </span>
                                            </div>
                                            <div class="small text-muted">
                                                <div v-for="stock in product.stock" :key="stock.variation" class="d-flex justify-content-between">
                                                    <span>{{ stock.variation }}:</span>
                                                    <span>{{ stock.quantity }}</span>
                                                </div>
                                            </div>
                                        </div>
    
                                        <!-- Ações -->
                                        <div class="d-grid">
                                            <button 
                                                type="button" 
                                                class="btn btn-success"
                                                @click="showBuyModal(product)"
                                                :disabled="getTotalStock(product) === 0"
                                            >
                                                <i class="bi bi-cart-plus me-2"></i>
                                                {{ getTotalStock(product) === 0 ? 'Sem Estoque' : 'Adicionar ao Carrinho' }}
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
    <!-- Modais -->
    <product-modal
        ref="productModal"
        modal-id="productModal"
        @success="onProductSuccess"
        @cancel="onProductCancel"
    />

    </div>

<!-- Modal de Compra (fora do escopo Vue) -->
<div class="modal fade" id="buyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold text-dark mb-1">
                        <i class="bi bi-cart-plus text-primary me-2"></i>Adicionar ao Carrinho
                    </h5>
                    <p class="text-muted small mb-0">Configure as opções do produto</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Produto</label>
                    <input type="text" id="buyProductName" class="form-control bg-light" readonly>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Variação</label>
                    <select id="buyVariation" class="form-select">
                        <option value="">Selecione uma variação</option>
                    </select>
                </div>

                <div class="mb-4 d-none" id="buyQuantitySection">
                    <label class="form-label fw-bold text-dark">Quantidade</label>
                    <input type="number" id="buyQuantity" min="1" value="1" class="form-control">
                    <div class="form-text">
                        <i class="bi bi-info-circle me-1"></i>
                        Máximo disponível: <span id="buyMaxQuantity" class="fw-bold">-</span> unidades
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-6">
                        <label class="form-label fw-bold text-dark">Preço Unitário</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white border-success">R$</span>
                            <input type="text" id="buyUnitPrice" class="form-control bg-light border-success" readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold text-dark">Total</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white border-primary">R$</span>
                            <input type="text" id="buyTotalPrice" class="form-control bg-light border-primary fw-bold" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-success px-4" id="buyAddToCartBtn">
                    <i class="bi bi-cart-plus me-2"></i>Adicionar ao Carrinho
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Componentes -->
<?php $this->load->view('components/BaseModal'); ?>
<?php $this->load->view('components/ProductForm'); ?>
<?php $this->load->view('components/ProductModal'); ?>
<?php $this->load->view('components/ProductsTable'); ?>
<?php $this->load->view('components/register_components'); ?>

<script>
// Aguarda o carregamento completo da página
window.addEventListener('load', function() {
    console.log('Página carregada');
    console.log('Vue disponível:', typeof Vue !== 'undefined');
    console.log('Utils disponível:', typeof window.utils !== 'undefined');

    // Aguarda um pouco mais para garantir que todos os scripts sejam carregados
    setTimeout(function() {
        if (typeof Vue === 'undefined') {
            console.error('Vue.js não foi carregado');
            return;
        }

        // Verifica se os componentes estão disponíveis
        if (!window.VueComponents) {
            return;
        }

        

        // Cria a aplicação Vue usando uma variável única
        const productsApp = Vue.createApp({
            name: 'ProductsApp',
            components: window.VueComponents,
                        data() {
                const products = <?= json_encode($products) ?>;
                return {
                    products: products,
                    loading: false,
                    currentBuyProduct: null,
                    viewType: 'table', // 'table' ou 'cards' - tabela é o padrão
                };
            },


                                    mounted() {
                // Aplicação montada com sucesso
            },
            methods: {
                showCreateModal() {
                    console.log('Abrindo modal de criação');
                    if (this.$refs.productModal) {
                        this.$refs.productModal.show();
                    } else {
                        console.error('productModal ref não encontrado');
                    }
                },

                showEditModal(product) {
                    console.log('Abrindo modal de edição para produto:', product);

                    // Validação do produto
                    if (!product || !product.id) {
                        console.error('showEditModal - produto inválido:', product);
                        utils.showToast('Erro', 'Dados do produto inválidos', 'error');
                        return;
                    }

                    if (this.$refs.productModal) {
                        console.log('Chamando productModal.show() com:', {
                            product: product,
                            isEdit: true
                        });
                        this.$refs.productModal.show(product, true);
                    } else {
                        console.error('productModal ref não encontrado');
                        utils.showToast('Erro', 'Modal de edição não encontrado', 'error');
                    }
                },

                                showBuyModal(product) {
                    // Preenche os dados do modal
                    document.getElementById('buyProductName').value = product.name;
                    document.getElementById('buyUnitPrice').value = this.formatPrice(product.price);
                    
                    // Reset do estado do modal - oculta campo de quantidade
                    document.getElementById('buyQuantitySection').classList.add('d-none');
                    document.getElementById('buyVariation').value = '';
                    document.getElementById('buyQuantity').value = 1;
                    
                    // Armazena dados para uso posterior
                    this.currentBuyProduct = product;
                    
                    // Carrega estoque
                    this.loadBuyModalStock(product.id);
                    
                    // Abre o modal
                    const modal = new bootstrap.Modal(document.getElementById('buyModal'));
                    modal.show();
                },

                                

                async deleteProduct(productId) {
                    // Busca o produto para mostrar o nome na confirmação
                    const product = this.products.find(p => p.id === productId);
                    const productName = product ? product.name : 'este produto';
                    
                    // Cria modal de confirmação personalizado
                    const confirmModal = document.createElement('div');
                    confirmModal.className = 'modal fade';
                    confirmModal.innerHTML = `
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header border-0 pb-0">
                                    <div>
                                        <h5 class="modal-title fw-bold text-danger mb-1">
                                            <i class="bi bi-exclamation-triangle me-2"></i>Confirmar Exclusão
                                        </h5>
                                        <p class="text-muted small mb-0">Esta ação não pode ser desfeita</p>
                                    </div>
                                </div>
                                <div class="modal-body px-4">
                                    <div class="text-center mb-4">
                                        <div class="bg-danger bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-trash text-danger" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <p class="mb-0">Tem certeza que deseja excluir o produto <strong>${productName}</strong>?</p>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle me-2"></i>Cancelar
                                    </button>
                                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                                        <i class="bi bi-trash me-2"></i>Excluir Produto
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    document.body.appendChild(confirmModal);
                    const modal = new bootstrap.Modal(confirmModal);
                    
                    // Adiciona evento de confirmação
                    const confirmBtn = confirmModal.querySelector('#confirmDeleteBtn');
                    confirmBtn.addEventListener('click', async () => {
                        confirmBtn.disabled = true;
                        confirmBtn.innerHTML = '<i class="bi bi-trash"></i> Excluindo...';
                        
                        try {
                            const response = await axios.get(`products/delete/${productId}`);
                            if (response.status === 200) {
                                utils.showToast('Sucesso', 'Produto excluído com sucesso!', 'success');
                                this.loadProducts();
                                modal.hide();
                            }
                        } catch (error) {
                            console.error('Erro:', error);
                            utils.showToast('Erro', 'Erro ao excluir produto', 'error');
                            confirmBtn.disabled = false;
                            confirmBtn.innerHTML = '<i class="bi bi-trash me-2"></i>Excluir Produto';
                        }
                    });
                    
                    // Remove modal do DOM quando fechado
                    confirmModal.addEventListener('hidden.bs.modal', () => {
                        document.body.removeChild(confirmModal);
                    });
                    
                    modal.show();
                },

                async loadProducts() {
                    this.loading = true;
                    try {
                        const response = await axios.get('products/get_products');
                        if (response.data.success) {
                            this.products = response.data.products;
                        }
                    } catch (error) {
                        console.error('Erro:', error);
                        utils.showToast('Erro', 'Erro ao carregar produtos', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                onProductSuccess(data) {
                    utils.showToast('Sucesso', data.message, 'success');
                    this.loadProducts();
                },

                onProductCancel() {
                    // Reset do modal
                    if (this.$refs.productModal) {
                        this.$refs.productModal.currentProduct = null;
                        this.$refs.productModal.isEdit = false;
                    }
                },

                onBuySuccess(data) {
                    utils.showToast('Sucesso', data.message, 'success');
                },
                
                async loadBuyModalStock(productId) {
                    try {
                        const response = await axios.get(`products/get_stock/${productId}`);
                        if (response.data.success) {
                            const select = document.getElementById('buyVariation');
                            select.innerHTML = '<option value="">Selecione uma variação</option>';
                            
                            response.data.stock.forEach(item => {
                                const option = document.createElement('option');
                                option.value = item.variation;
                                option.textContent = `${item.variation} (Estoque: ${item.quantity})`;
                                option.dataset.quantity = item.quantity;
                                select.appendChild(option);
                            });
                            
                            this.setupBuyModalEvents();
                        }
                    } catch (error) {
                        utils.showToast('Erro', 'Erro ao carregar estoque', 'error');
                    }
                },
                
                setupBuyModalEvents() {
                    const variationSelect = document.getElementById('buyVariation');
                    const quantityInput = document.getElementById('buyQuantity');
                    const addToCartBtn = document.getElementById('buyAddToCartBtn');
                    
                    // Remove listeners antigos
                    variationSelect.removeEventListener('change', this.onVariationChange);
                    quantityInput.removeEventListener('input', this.onQuantityChange);
                    addToCartBtn.removeEventListener('click', this.onAddToCartClick);
                    
                    // Adiciona novos listeners
                    variationSelect.addEventListener('change', this.onVariationChange.bind(this));
                    quantityInput.addEventListener('input', this.onQuantityChange.bind(this));
                    addToCartBtn.addEventListener('click', this.onAddToCartClick.bind(this));
                },
                
                onVariationChange() {
                    const variationSelect = document.getElementById('buyVariation');
                    const quantityInput = document.getElementById('buyQuantity');
                    const maxQuantitySpan = document.getElementById('buyMaxQuantity');
                    const quantitySection = document.getElementById('buyQuantitySection');
                    
                    const selectedOption = variationSelect.options[variationSelect.selectedIndex];
                    if (selectedOption && selectedOption.value && selectedOption.dataset.quantity) {
                        // Mostra o campo de quantidade quando uma variação é selecionada
                        quantitySection.classList.remove('d-none');
                        
                        const maxQuantity = parseInt(selectedOption.dataset.quantity);
                        maxQuantitySpan.textContent = maxQuantity;
                        quantityInput.max = maxQuantity;
                        quantityInput.value = Math.min(parseInt(quantityInput.value) || 1, maxQuantity);
                    } else {
                        // Oculta o campo de quantidade quando nenhuma variação é selecionada
                        quantitySection.classList.add('d-none');
                        
                        maxQuantitySpan.textContent = '-';
                        quantityInput.max = '';
                        quantityInput.value = 1;
                    }
                    this.updateTotal();
                },
                
                onQuantityChange() {
                    this.updateTotal();
                },
                
                updateTotal() {
                    const unitPrice = parseFloat(this.currentBuyProduct?.price) || 0;
                    const quantity = parseInt(document.getElementById('buyQuantity').value) || 0;
                    const total = unitPrice * quantity;
                    document.getElementById('buyTotalPrice').value = this.formatPrice(total);
                },
                
                onAddToCartClick() {
                    this.addToCart();
                },
                
                getTotalStock(product) {
                    if (!product.stock || !Array.isArray(product.stock)) {
                        return 0;
                    }
                    
                    return product.stock.reduce((total, stock) => {
                        const quantity = parseInt(stock.quantity) || 0;
                        return total + quantity;
                    }, 0);
                },
                
                formatPrice(price) {
                    return new Intl.NumberFormat('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    }).format(price);
                },
                
                async addToCart() {
                    const variation = document.getElementById('buyVariation').value;
                    const quantity = parseInt(document.getElementById('buyQuantity').value);
                    const addBtn = document.getElementById('buyAddToCartBtn');
                    
                    if (!variation || !quantity) {
                        utils.showToast('Atenção', 'Selecione uma variação e quantidade válida', 'warning');
                        return;
                    }
                    
                    addBtn.disabled = true;
                    addBtn.innerHTML = '<i class="bi bi-cart-plus"></i> Adicionando...';
                    
                    try {
                        const response = await axios.post('products/add_to_cart', {
                            product_id: this.currentBuyProduct.id,
                            variation: variation,
                            quantity: quantity
                        });
                        
                        if (response.data.success) {
                            utils.showToast('Sucesso', 'Produto adicionado ao carrinho!', 'success');
                            const modal = bootstrap.Modal.getInstance(document.getElementById('buyModal'));
                            modal.hide();
                        } else {
                            utils.showToast('Erro', response.data.message || 'Erro ao adicionar ao carrinho', 'error');
                        }
                    } catch (error) {
                        utils.showToast('Erro', 'Erro ao adicionar ao carrinho', 'error');
                    } finally {
                        addBtn.disabled = false;
                        addBtn.innerHTML = '<i class="bi bi-cart-plus"></i> Adicionar ao Carrinho';
                    }
                }
            }
        });

                // Monta a aplicação
        productsApp.mount('#app');
    }, 200);
});
</script>
