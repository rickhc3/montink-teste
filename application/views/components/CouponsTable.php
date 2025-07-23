<script>
const CouponsTable = {
    name: 'CouponsTable',
    props: {
        coupons: {
            type: Array,
            required: true
        }
    },
    template: `
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Status</th>
                        <th>Tipo</th>
                        <th>Desconto</th>
                        <th>Valor Mínimo</th>
                        <th>Validade</th>
                        <th>Usos</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="coupon in coupons" :key="coupon.id">
                        <td><span class="badge bg-secondary">{{ coupon.id }}</span></td>
                        <td><strong class="text-dark">{{ coupon.code }}</strong></td>
                        <td><span :class="getStatusClass(coupon)">{{ getStatusText(coupon) }}</span></td>
                        <td><span class="badge bg-light text-dark">{{ getTypeText(coupon.discount_type) }}</span></td>
                        <td><span class="text-success fw-bold">{{ getDiscountDisplay(coupon) }}</span></td>
                        <td><span class="text-muted">{{ formatCurrency(coupon.min_amount) }}</span></td>
                        <td><span class="text-muted">{{ formatDate(coupon.valid_until) }}</span></td>
                        <td><span class="badge bg-info">{{ getUsageText(coupon) }}</span></td>
                        <td class="py-3 text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <button 
                                    type="button" 
                                    class="btn btn-primary btn-sm" 
                                    @click="editCoupon(coupon)"
                                    title="Editar"
                                >
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button 
                                    type="button" 
                                    class="btn btn-danger btn-sm" 
                                    @click="deleteCoupon(coupon)"
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
        getStatusClass(coupon) {
            const today = new Date().toISOString().split('T')[0];
            
            if (!coupon.is_active) {
                return 'badge bg-secondary';
            } else if (today > coupon.valid_until) {
                return 'badge bg-danger';
            } else if (today < coupon.valid_from) {
                return 'badge bg-warning';
            } else if (coupon.max_uses && coupon.used_count >= coupon.max_uses) {
                return 'badge bg-danger';
            }
            
            return 'badge bg-success';
        },
        
        getStatusText(coupon) {
            const today = new Date().toISOString().split('T')[0];
            
            if (!coupon.is_active) {
                return 'Inativo';
            } else if (today > coupon.valid_until) {
                return 'Expirado';
            } else if (today < coupon.valid_from) {
                return 'Aguardando';
            } else if (coupon.max_uses && coupon.used_count >= coupon.max_uses) {
                return 'Esgotado';
            }
            
            return 'Ativo';
        },
        
        getTypeText(type) {
            return type === 'percentage' ? 'Percentual' : 'Valor Fixo';
        },
        
        getDiscountDisplay(coupon) {
            if (coupon.discount_type === 'percentage') {
                return parseFloat(coupon.discount_value).toFixed(1) + '%';
            } else {
                return 'R$ ' + parseFloat(coupon.discount_value).toFixed(2).replace('.', ',');
            }
        },
        
        formatCurrency(value) {
            return 'R$ ' + parseFloat(value || 0).toFixed(2).replace('.', ',');
        },
        
        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString + 'T00:00:00');
            return date.toLocaleDateString('pt-BR');
        },
        
        getUsageText(coupon) {
            return (coupon.used_count || 0) + ' / ' + (coupon.max_uses || '∞');
        },
        
        editCoupon(coupon) {
            this.$emit('edit-coupon', coupon);
        },
        
        deleteCoupon(coupon) {
            if (confirm('Tem certeza que deseja excluir este cupom?')) {
                this.$emit('delete-coupon', coupon);
            }
        }
    }
};
</script>