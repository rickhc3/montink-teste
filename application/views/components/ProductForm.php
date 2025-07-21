<script>
const ProductForm = {
    name: 'ProductForm',
    props: {
        product: {
            type: Object,
            default: null
        },
        isEdit: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            loading: false,
            formData: {
                name: '',
                price: '',
                variations: []
            },
            priceMask: null
        };
    },
    template: `
        <form @submit.prevent="handleSubmit">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nome do Produto</label>
                    <input 
                        type="text" 
                        v-model="formData.name" 
                        class="form-control" 
                        placeholder="Digite o nome do produto" 
                        required
                    >
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Preço</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input 
                            type="text" 
                            ref="priceInput"
                            v-model="formData.price" 
                            class="form-control" 
                            placeholder="0,00" 
                            required
                        >
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">{{ isEdit ? 'Estoque por Variação' : 'Variações' }}</label>
                <div class="variations-wrapper">
                    <div 
                        v-for="(variation, index) in formData.variations" 
                        :key="index"
                        class="row mb-2"
                    >
                        <div :class="isEdit ? 'col-md-6' : 'col-md-8'">
                            <input 
                                type="text" 
                                v-model="variation.name" 
                                :placeholder="isEdit ? 'Nome da variação' : 'Nome da variação (ex: Tamanho M)'" 
                                class="form-control" 
                                required
                            >
                        </div>
                        <div :class="isEdit ? 'col-md-5' : 'col-md-3'">
                            <input 
                                type="number" 
                                v-model="variation.quantity" 
                                placeholder="Estoque" 
                                min="0" 
                                class="form-control" 
                                required
                            >
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button 
                                type="button" 
                                @click="removeVariation(index)" 
                                class="btn btn-danger btn-sm w-100"
                                title="Remover variação"
                                style="height: 38px; display: flex; align-items: center; justify-content: center;"
                            >
                                <i class="bi bi-trash" style="font-size: 0.9rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button 
                    type="button" 
                    @click="addVariation" 
                    class="btn btn-success btn-sm"
                >
                    <i class="bi bi-plus-circle"></i> Adicionar Variação
                </button>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="button" class="btn btn-secondary" @click="$emit('cancel')">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary" :disabled="loading">
                    <i class="bi bi-check-circle"></i> 
                    {{ loading ? 'Salvando...' : (isEdit ? 'Atualizar Produto' : 'Salvar Produto') }}
                </button>
            </div>
        </form>
    `,
    mounted() {
        this.initializeForm();
        this.setupPriceMask();
    },
    
    watch: {
        product: {
            handler(newProduct) {
                this.initializeForm();
            },
            immediate: true
        },
        isEdit: {
            handler(newIsEdit) {
                this.initializeForm();
            },
            immediate: true
        }
    },
    methods: {
        initializeForm() {
            if (this.isEdit && this.product) {
                this.formData.name = this.product.name || '';
                // PROBLEMA: O preço pode já estar formatado e a máscara é aplicada novamente
                this.formData.price = utils.parsePrice(this.product.price) || 0;
                
                if (this.product.stock && Array.isArray(this.product.stock)) {
                    this.formData.variations = this.product.stock.map(item => ({
                        name: item.variation || '',
                        quantity: parseInt(item.quantity) || 0
                    }));
                } else {
                    this.formData.variations = [{ name: '', quantity: 0 }];
                }
            } else {
                this.formData = {
                    name: '',
                    price: '',
                    variations: [{ name: '', quantity: 0 }]
                };
            }
        },
        
        setupPriceMask() {
            this.$nextTick(() => {
                if (this.$refs.priceInput) {
                    this.priceMask = utils.applyPriceMask(this.$refs.priceInput);
                    
                    // Atualiza o valor da máscara com o valor parseado
                    if (this.priceMask && this.formData.price) {
                        this.priceMask.value = utils.parsePrice(this.formData.price);
                    }
                }
            });
        },
        
        addVariation() {
            this.formData.variations.push({ name: '', quantity: 0 });
        },
        
        removeVariation(index) {
            if (this.formData.variations.length > 1) {
                this.formData.variations.splice(index, 1);
            }
        },
        
        async handleSubmit() {
            this.loading = true;
            
            try {
                // Processa o preço
                let priceValue = this.formData.price;
                
                // Remove formatação se for string
                if (typeof priceValue === 'string') {
                    priceValue = priceValue.replace(/[^\d,]/g, '').replace(',', '.');
                }
                
                const numericPrice = parseFloat(priceValue) || 0;
                
                const submitData = {
                    name: this.formData.name,
                    price: numericPrice
                };
                
                if (this.isEdit) {
                    submitData.id = this.product.id;
                    submitData.stock = this.formData.variations.map(v => ({
                        variation: v.name,
                        quantity: parseInt(v.quantity) || 0
                    }));
                } else {
                    submitData.variations = this.formData.variations.map(v => ({
                        name: v.name,
                        quantity: parseInt(v.quantity) || 0
                    }));
                }
                
                const url = this.isEdit ? 'products/update' : 'products/store';
                const response = await axios.post(url, submitData);
                
                if (response.data.success) {
                    utils.showToast('Sucesso', response.data.message, 'success');
                    this.$emit('success', response.data);
                } else {
                    utils.showToast('Erro', response.data.message, 'error');
                }
            } catch (error) {
                utils.showToast('Erro', 'Erro ao salvar produto', 'error');
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>