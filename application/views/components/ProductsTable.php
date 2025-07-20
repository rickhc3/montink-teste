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
            <table class="table table-hover">
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
                console.log('Stock não encontrado para produto:', product.id);
                return 0;
            }
            
            console.log('Calculando estoque para produto:', product.id, 'Stock:', product.stock);
            
            const total = product.stock.reduce((total, stock) => {
                // Garante que a quantidade seja um número
                const quantity = parseInt(stock.quantity) || 0;
                console.log(`Variação ${stock.variation}: ${stock.quantity} (${typeof stock.quantity}) -> ${quantity}`);
                return total + quantity;
            }, 0);
            
            console.log('Total calculado:', total);
            return total;
        },
        
        editProduct(product) {
            console.log('ProductsTable.editProduct() chamado com produto:', product);
            
            // Validação do produto
            if (!product || !product.id) {
                console.error('ProductsTable.editProduct() - produto inválido:', product);
                return;
            }
            
            this.$emit('edit', product);
        },
        
        buyProduct(product) {
            console.log('ProductsTable.buyProduct() chamado com produto:', product);
            
            // Validação do produto
            if (!product || !product.id) {
                console.error('ProductsTable.buyProduct() - produto inválido:', product);
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