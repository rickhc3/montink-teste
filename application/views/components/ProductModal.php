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
    methods: {
        show(product = null, isEdit = false) {
            this.currentProduct = product;
            this.isEdit = isEdit;
            this.$refs.baseModal.show();
        },
        
        hide() {
            this.$refs.baseModal.hide();
        },
        
        handleSuccess(data) {
            this.hide();
            this.$emit('success', data);
        },
        
        handleCancel() {
            this.hide();
            this.$emit('cancel');
        }
    }
};
</script> 