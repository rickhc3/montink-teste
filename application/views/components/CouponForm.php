<script>
const CouponForm = {
    name: 'CouponForm',
    props: {
        coupon: {
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
            formData: {
                code: '',
                discount_type: 'percentage',
                discount_value: '',
                min_amount: '',
                valid_from: '',
                valid_until: '',
                max_uses: '',
                is_active: 1
            },
            loading: false,
            errors: {}
        };
    },
    watch: {
        coupon: {
            immediate: true,
            handler(newCoupon) {
                if (newCoupon) {
                    this.formData = {
                        code: newCoupon.code || '',
                        discount_type: newCoupon.discount_type || 'percentage',
                        discount_value: newCoupon.discount_value || '',
                        min_amount: newCoupon.min_amount || '',
                        valid_from: newCoupon.valid_from || '',
                        valid_until: newCoupon.valid_until || '',
                        max_uses: newCoupon.max_uses || '',
                        is_active: newCoupon.is_active || 1
                    };
                } else {
                    this.resetForm();
                }
            }
        }
    },
    template: `
        <form @submit.prevent="submitForm">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="code" class="form-label">Código do Cupom</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="code" 
                        v-model="formData.code" 
                        :class="{ 'is-invalid': errors.code }"
                        required
                    >
                    <div v-if="errors.code" class="invalid-feedback">{{ errors.code }}</div>
                </div>
                <div class="col-md-6">
                    <label for="discount_type" class="form-label">Tipo de Desconto</label>
                    <select 
                        class="form-select" 
                        id="discount_type" 
                        v-model="formData.discount_type" 
                        :class="{ 'is-invalid': errors.discount_type }"
                        required
                    >
                        <option value="percentage">Percentual</option>
                        <option value="fixed">Valor Fixo</option>
                    </select>
                    <div v-if="errors.discount_type" class="invalid-feedback">{{ errors.discount_type }}</div>
                </div>
                <div class="col-md-6">
                    <label for="discount_value" class="form-label">Valor do Desconto</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="discount_value" 
                        v-model="formData.discount_value" 
                        :class="{ 'is-invalid': errors.discount_value }"
                        step="0.01" 
                        required
                    >
                    <div v-if="errors.discount_value" class="invalid-feedback">{{ errors.discount_value }}</div>
                </div>
                <div class="col-md-6">
                    <label for="min_amount" class="form-label">Valor Mínimo</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="min_amount" 
                        v-model="formData.min_amount" 
                        :class="{ 'is-invalid': errors.min_amount }"
                        step="0.01"
                    >
                    <div v-if="errors.min_amount" class="invalid-feedback">{{ errors.min_amount }}</div>
                </div>
                <div class="col-md-6">
                    <label for="valid_from" class="form-label">Válido de</label>
                    <input 
                        type="date" 
                        class="form-control" 
                        id="valid_from" 
                        v-model="formData.valid_from" 
                        :class="{ 'is-invalid': errors.valid_from }"
                        required
                    >
                    <div v-if="errors.valid_from" class="invalid-feedback">{{ errors.valid_from }}</div>
                </div>
                <div class="col-md-6">
                    <label for="valid_until" class="form-label">Válido até</label>
                    <input 
                        type="date" 
                        class="form-control" 
                        id="valid_until" 
                        v-model="formData.valid_until" 
                        :class="{ 'is-invalid': errors.valid_until }"
                        required
                    >
                    <div v-if="errors.valid_until" class="invalid-feedback">{{ errors.valid_until }}</div>
                </div>
                <div class="col-md-6">
                    <label for="max_uses" class="form-label">Limite de Usos</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="max_uses" 
                        v-model="formData.max_uses" 
                        :class="{ 'is-invalid': errors.max_uses }"
                        min="1"
                    >
                    <div class="form-text">Deixe em branco para uso ilimitado</div>
                    <div v-if="errors.max_uses" class="invalid-feedback">{{ errors.max_uses }}</div>
                </div>
                <div class="col-md-6">
                    <label for="is_active" class="form-label">Status</label>
                    <select 
                        class="form-select" 
                        id="is_active" 
                        v-model="formData.is_active" 
                        :class="{ 'is-invalid': errors.is_active }"
                        required
                    >
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                    <div v-if="errors.is_active" class="invalid-feedback">{{ errors.is_active }}</div>
                </div>
            </div>
            
            <div class="modal-footer mt-4">
                <button type="button" class="btn btn-secondary" @click="cancel">Cancelar</button>
                <button type="submit" class="btn btn-primary" :disabled="loading">
                    <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status"></span>
                    {{ loading ? 'Salvando...' : 'Salvar Cupom' }}
                </button>
            </div>
        </form>
    `,
    methods: {
        async submitForm() {
            this.loading = true;
            this.errors = {};
            
            try {
                const url = this.isEdit 
                    ? `<?= base_url('coupons/update/') ?>${this.coupon.id}`
                    : '<?= base_url('coupons/store') ?>';
                
                const formData = new FormData();
                Object.keys(this.formData).forEach(key => {
                    if (this.formData[key] !== null && this.formData[key] !== '') {
                        formData.append(key, this.formData[key]);
                    }
                });
                
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.$emit('success', {
                        message: data.message,
                        coupon: data.coupon || this.formData
                    });
                    this.resetForm();
                } else {
                    if (data.errors) {
                        this.errors = data.errors;
                    } else {
                        utils.showToast('Erro', data.message || 'Erro ao salvar cupom', 'error');
                    }
                }
            } catch (error) {
                console.error('Erro ao salvar cupom:', error);
                utils.showToast('Erro', 'Erro ao salvar cupom', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        cancel() {
            this.$emit('cancel');
            this.resetForm();
        },
        
        resetForm() {
            this.formData = {
                code: '',
                discount_type: 'percentage',
                discount_value: '',
                min_amount: '',
                valid_from: '',
                valid_until: '',
                max_uses: '',
                is_active: 1
            };
            this.errors = {};
        }
    }
};
</script>