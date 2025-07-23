<script>
const ProductsTable = {
    name: 'ProductsTable',
    props: {
        products: {
            type: Array,
            required: true
        }
    },
    template: `
        <div class="table-responsive">
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Estoque Total</th>
                        <th>Variações</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products" :key="product.id">
                        <td><span class="badge bg-secondary">{{ product.id }}</span></td>
                        <td>
                            <strong>{{ product.name }}</strong>
                        </td>
                        <td>
                            <span class="text-success fw-bold">{{ formatPrice(product.price) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ getTotalStock(product) }} unidades</span>
                        </td>
                        <td>
                            <span 
                                v-for="stock in product.stock" 
                                :key="stock.variation"
                                class="badge bg-light text-dark me-1"
                            >
                                {{ stock.variation }}: {{ stock.quantity }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button 
                                    type="button" 
                                    class="btn btn-outline-primary btn-sm" 
                                    @click="editProduct(product)"
                                    title="Editar"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button 
                                    type="button" 
                                    class="btn btn-outline-success btn-sm" 
                                    @click="buyProduct(product)"
                                    title="Comprar"
                                >
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                                <button 
                                    type="button" 
                                    class="btn btn-outline-danger btn-sm" 
                                    @click="deleteProduct(product.id)"
                                    title="Excluir"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    `,
    methods: {
        formatPrice(price) {
            return utils.formatPrice(price);
        },
        
        getTotalStock(product) {
            if (!product.stock || !Array.isArray(product.stock)) {
                return 0;
            }
            
            const total = product.stock.reduce((total, stock) => {
                const quantity = parseInt(stock.quantity) || 0;
                return total + quantity;
            }, 0);
            
            return total;
        },
        
        editProduct(product) {
            
            // Validação do produto
            if (!product || !product.id) {
                return;
            }
            
            this.$emit('edit', product);
        },
        
        buyProduct(product) {
            
            // Validação do produto
            if (!product || !product.id) {
                return;
            }
            
            this.$emit('buy', product);
        },
        
        deleteProduct(productId) {
            if (confirm('Tem certeza que deseja excluir este produto?')) {
                this.$emit('delete', productId);
            }
        }
    }
};
</script>