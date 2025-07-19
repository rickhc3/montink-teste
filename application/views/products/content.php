<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="card-title h2 mb-0">Produtos</h1>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('products/cart') ?>" class="btn btn-outline-primary">
                                <i class="bi bi-cart3"></i> Carrinho
                            </a>
                            <button type="button" class="btn btn-primary" @click="showCreateModal">
                                <i class="bi bi-plus-circle"></i> Novo Produto
                            </button>
                        </div>
                    </div>

                    <!-- Estado vazio -->
                    <div v-if="products.length === 0" class="text-center py-5">
                        <i class="bi bi-box h1 text-muted"></i>
                        <h3 class="mt-3">Nenhum produto cadastrado</h3>
                        <p class="text-muted">Comece criando seu primeiro produto!</p>
                        <button type="button" class="btn btn-primary" @click="showCreateModal">
                            <i class="bi bi-plus-circle"></i> Criar Produto
                        </button>
                    </div>

                    <!-- Tabela de produtos -->
                    <products-table 
                        v-else
                        :products="products"
                        @edit="showEditModal"
                        @buy="showBuyModal"
                        @delete="deleteProduct"
                    />
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

<buy-modal 
    ref="buyModal"
    @success="onBuySuccess"
/>

<!-- Componentes -->
<?php $this->load->view('components/BaseModal'); ?>
<?php $this->load->view('components/ProductForm'); ?>
<?php $this->load->view('components/ProductModal'); ?>
<?php $this->load->view('components/BuyModal'); ?>
<?php $this->load->view('components/ProductsTable'); ?>
<?php $this->load->view('components/register_components'); ?>

<script>
// Aguarda o carregamento completo da página
window.addEventListener('load', function() {
    console.log('Página carregada');
    console.log('Vue disponível:', typeof Vue !== 'undefined');
    console.log('Utils disponível:', typeof window.utils !== 'undefined');
    
    if (typeof Vue === 'undefined') {
        console.error('Vue.js não foi carregado');
        return;
    }

    // Verifica se os componentes estão disponíveis
    if (!window.VueComponents) {
        console.error('Componentes Vue não foram carregados corretamente');
        return;
    }

    // Cria a aplicação Vue
    const app = Vue.createApp({
        name: 'ProductsApp',
        components: window.VueComponents,
        data() {
            return {
                products: <?= json_encode($products) ?>,
                loading: false
            };
        },
        methods: {
            showCreateModal() {
                this.$refs.productModal.show();
            },
            
            showEditModal(product) {
                this.$refs.productModal.show(product, true);
            },
            
            showBuyModal(product) {
                this.$refs.buyModal.show(product.id, product.name, product.price);
            },
            
            async deleteProduct(productId) {
                try {
                    const response = await axios.get(`products/delete/${productId}`);
                    if (response.status === 200) {
                        utils.showToast('Sucesso', 'Produto excluído com sucesso!', 'success');
                        this.loadProducts();
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    utils.showToast('Erro', 'Erro ao excluir produto', 'error');
                }
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
                this.$refs.productModal.currentProduct = null;
                this.$refs.productModal.isEdit = false;
            },
            
            onBuySuccess(data) {
                utils.showToast('Sucesso', data.message, 'success');
            }
        }
    });

    // Monta a aplicação
    app.mount('#app');
});
</script> 