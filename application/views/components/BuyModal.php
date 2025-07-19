<script>
const BuyModal = {
    name: 'BuyModal',
    components: {
        BaseModal
    },
    data() {
        return {
            productId: null,
            productName: '',
            unitPrice: 0,
            productStock: [],
            selectedVariation: '',
            quantity: 1,
            maxQuantity: 0,
            loading: false
        };
    },
    computed: {
        totalPrice() {
            return this.unitPrice * this.quantity;
        },
        
        canAddToCart() {
            return this.selectedVariation && this.quantity > 0 && this.quantity <= this.maxQuantity;
        }
    },
    template: `
        <base-modal 
            modal-id="buyModal" 
            title="Adicionar ao Carrinho"
            ref="baseModal"
        >
            <div class="mb-3">
                <label class="form-label fw-bold">Produto</label>
                <input type="text" v-model="productName" class="form-control" readonly>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Variação</label>
                <select v-model="selectedVariation" class="form-select" @change="onVariationChange">
                    <option value="">Selecione uma variação</option>
                    <option 
                        v-for="stock in productStock" 
                        :key="stock.variation"
                        :value="stock.variation"
                        :data-quantity="stock.quantity"
                    >
                        {{ stock.variation }} (Estoque: {{ stock.quantity }})
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Quantidade</label>
                <input 
                    type="number" 
                    v-model="quantity" 
                    min="1" 
                    :max="maxQuantity"
                    class="form-control"
                    @input="updateTotal"
                >
                <div class="form-text">Máximo disponível: <span>{{ maxQuantity || '-' }}</span></div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Preço Unitário</label>
                <div class="input-group">
                    <span class="input-group-text">R$</span>
                    <input type="text" :value="formatPrice(unitPrice)" class="form-control" readonly>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Total</label>
                <div class="input-group">
                    <span class="input-group-text">R$</span>
                    <input type="text" :value="formatPrice(totalPrice)" class="form-control" readonly>
                </div>
            </div>
            
            <template #footer>
                <button type="button" class="btn btn-secondary" @click="hide">Cancelar</button>
                <button 
                    type="button" 
                    class="btn btn-success" 
                    @click="addToCart"
                    :disabled="!canAddToCart || loading"
                >
                    <i class="bi bi-cart-plus"></i> 
                    {{ loading ? 'Adicionando...' : 'Adicionar ao Carrinho' }}
                </button>
            </template>
        </base-modal>
    `,
    methods: {
        show(productId, productName, productPrice) {
            this.productId = productId;
            this.productName = productName;
            this.unitPrice = productPrice;
            this.resetForm();
            this.loadStock();
            this.$refs.baseModal.show();
        },
        
        hide() {
            this.$refs.baseModal.hide();
        },
        
        resetForm() {
            this.selectedVariation = '';
            this.quantity = 1;
            this.maxQuantity = 0;
        },
        
        async loadStock() {
            try {
                const response = await axios.get(`products/get_stock/${this.productId}`);
                if (response.data.success) {
                    this.productStock = response.data.stock;
                }
            } catch (error) {
                console.error('Erro ao carregar estoque:', error);
                utils.showToast('Erro', 'Erro ao carregar estoque', 'error');
            }
        },
        
        onVariationChange() {
            if (this.selectedVariation) {
                const stock = this.productStock.find(s => s.variation === this.selectedVariation);
                if (stock) {
                    this.maxQuantity = stock.quantity;
                    this.quantity = Math.min(this.quantity, this.maxQuantity);
                }
            } else {
                this.maxQuantity = 0;
                this.quantity = 1;
            }
        },
        
        updateTotal() {
            // Método chamado quando quantidade muda
        },
        
        formatPrice(price) {
            return utils.formatPrice(price);
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
                    this.hide();
                    this.$emit('success', response.data);
                } else {
                    utils.showToast('Erro', response.data.message || 'Erro ao adicionar ao carrinho', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                utils.showToast('Erro', 'Erro ao adicionar ao carrinho', 'error');
            } finally {
                this.loading = false;
            }
        }
    }
};
</script> 