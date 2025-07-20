<script>
const ProductModal = {
    name: 'ProductModal',
    components: {
        BaseModal,
        ProductForm
    },
    props: {
        modalId: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            currentProduct: null,
            isEdit: false
        };
    },
    computed: {
        title() {
            return this.isEdit ? 'Editar Produto' : 'Criar Novo Produto';
        }
    },
    template: `
        <base-modal 
            :modal-id="modalId" 
            :title="title" 
            modal-size="modal-lg"
            ref="baseModal"
        >
            <product-form 
                :product="currentProduct" 
                :is-edit="isEdit"
                @success="handleSuccess"
                @cancel="handleCancel"
            />
        </base-modal>
    `,
    mounted() {
        console.log('ProductModal.mounted() - modalId:', this.modalId);
    },
    methods: {
        show(product = null, isEdit = false) {
            console.log('ProductModal.show() chamado:', { product, isEdit });
            
            // Validação do produto
            if (isEdit && (!product || !product.id)) {
                console.error('ProductModal.show() - produto inválido para edição:', product);
                utils.showToast('Erro', 'Dados do produto inválidos para edição', 'error');
                return;
            }
            
            this.currentProduct = product;
            this.isEdit = isEdit;
            
            console.log('ProductModal.show() - dados configurados:', {
                currentProduct: this.currentProduct,
                isEdit: this.isEdit,
                productId: product ? product.id : 'N/A'
            });
            
            // Aguarda um pouco para garantir que o DOM foi atualizado
            this.$nextTick(() => {
                console.log('ProductModal.show() - dados antes de abrir modal:', {
                    currentProduct: this.currentProduct,
                    isEdit: this.isEdit
                });
                this.$refs.baseModal.show();
            });
        },
        
        hide() {
            this.$refs.baseModal.hide();
        },
        
        handleSuccess(data) {
            console.log('ProductModal.handleSuccess() chamado:', data);
            this.hide();
            this.$emit('success', data);
        },
        
        handleCancel() {
            console.log('ProductModal.handleCancel() chamado');
            this.hide();
            this.$emit('cancel');
        }
    }
};
</script> 