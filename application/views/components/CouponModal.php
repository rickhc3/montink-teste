<script>
const CouponModal = {
    name: 'CouponModal',
    components: {
        BaseModal,
        CouponForm
    },
    props: {
        modalId: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            currentCoupon: null,
            isEdit: false
        };
    },
    computed: {
        title() {
            return this.isEdit ? 'Editar Cupom' : 'Criar Novo Cupom';
        }
    },
    template: `
        <base-modal 
            :modal-id="modalId" 
            :title="title" 
            modal-size="modal-lg"
            ref="baseModal"
        >
            <coupon-form 
                :coupon="currentCoupon" 
                :is-edit="isEdit"
                @success="handleSuccess"
                @cancel="handleCancel"
            />
        </base-modal>
    `,
    mounted() {
        // Modal montado
    },
    methods: {
        show(coupon = null, isEdit = false) {
            // Validação do cupom
            if (isEdit && (!coupon || !coupon.id)) {
                console.error('CouponModal.show() - cupom inválido para edição:', coupon);
                utils.showToast('Erro', 'Dados do cupom inválidos para edição', 'error');
                return;
            }
            
            this.currentCoupon = coupon;
            this.isEdit = isEdit;
            
            // Aguarda um pouco para garantir que o DOM foi atualizado
            this.$nextTick(() => {
                this.$refs.baseModal.show();
            });
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