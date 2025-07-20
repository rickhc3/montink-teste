<script>
const BuyModal = {
    name: 'BuyModal',
    data() {
        return {
            productId: null,
            productName: '',
            productPrice: 0,
            stock: [],
            selectedVariation: '',
            quantity: 1,
            loading: false
        };
    },
    computed: {
        totalPrice() {
            return this.productPrice * this.quantity;
        },
        maxQuantity() {
            if (!this.selectedVariation) return 0;
            const stockItem = this.stock.find(s => s.variation === this.selectedVariation);
            return stockItem ? stockItem.quantity : 0;
        },
        canAddToCart() {
            return this.selectedVariation && this.quantity > 0 && this.quantity <= this.maxQuantity;
        }
    },
    template: `
        <div class="modal fade" id="buyModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Adicionar ao Carrinho</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Produto</label>
                            <input type="text" v-model="productName" class="form-control" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Variação</label>
                            <select v-model="selectedVariation" class="form-select" @change="onVariationChange">
                                <option value="">Selecione uma variação</option>
                                <option v-for="item in stock" :key="item.variation" :value="item.variation">
                                    {{ item.variation }} (Estoque: {{ item.quantity }})
                                </option>
                            </select>
                        </div>

                        <div v-if="selectedVariation" class="mb-3">
                            <label class="form-label">Quantidade</label>
                            <input type="number" v-model="quantity" min="1" :max="maxQuantity" class="form-control">
                            <div class="form-text">Máximo: {{ maxQuantity }}</div>
                        </div>

                        <div v-if="selectedVariation" class="mb-3">
                            <label class="form-label">Preço Unitário</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" :value="formatPrice(productPrice)" class="form-control" readonly>
                            </div>
                        </div>

                        <div v-if="selectedVariation" class="mb-3">
                            <label class="form-label">Total</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" :value="formatPrice(totalPrice)" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success" @click="addToCart" :disabled="!canAddToCart || loading">
                            <i class="bi bi-cart-plus"></i> 
                            {{ loading ? 'Adicionando...' : 'Adicionar ao Carrinho' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `,
    methods: {
        show(id, name, price) {
            this.productId = id;
            this.productName = name;
            this.productPrice = parseFloat(price) || 0;
            this.selectedVariation = '';
            this.quantity = 1;
            this.loading = false;
            
            this.loadStock();
            this.showModal();
        },
        
        showModal() {
            const modal = new bootstrap.Modal(document.getElementById('buyModal'));
            modal.show();
        },
        
        async loadStock() {
            try {
                const response = await axios.get(`products/get_stock/${this.productId}`);
                if (response.data.success) {
                    this.stock = response.data.stock;
                }
            } catch (error) {
                utils.showToast('Erro', 'Erro ao carregar estoque', 'error');
            }
        },
        
        onVariationChange() {
            this.quantity = 1;
        },
        
        formatPrice(price) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(price);
        },
        
        async addToCart() {
            if (!this.canAddToCart) {
                utils.showToast('Atenção', 'Selecione uma variação e quantidade válida', 'warning');
                return;
            }
            
            this.loading = true;
            
            try {
                const response = await axios.post('products/add_to_cart', {
                    product_id: this.productId,
                    variation: this.selectedVariation,
                    quantity: this.quantity
                });
                
                if (response.data.success) {
                    utils.showToast('Sucesso', 'Produto adicionado ao carrinho!', 'success');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('buyModal'));
                    modal.hide();
                    this.$emit('success', response.data);
                } else {
                    utils.showToast('Erro', response.data.message || 'Erro ao adicionar ao carrinho', 'error');
                }
            } catch (error) {
                utils.showToast('Erro', 'Erro ao adicionar ao carrinho', 'error');
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>