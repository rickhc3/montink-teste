<div class="min-vh-100" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <!-- Container principal -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="main-container">
                    <div class="page-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="page-title">
                                    <i class="bi bi-tag"></i> Gerenciar Cupons
                                </h1>
                                <p class="mb-0 opacity-75">Gerencie seus cupons de desconto</p>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-light text-dark fs-6 px-3 py-2">
                                    {{ coupons.length }} {{ coupons.length === 1 ? 'cupom' : 'cupons' }}
                                </span>

                                <button type="button" class="btn btn-primary" @click="showCreateModal">
                                    <i class="bi bi-plus-circle"></i> Novo Cupom
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="p-4">
                        <!-- Estado vazio -->
                        <div v-if="coupons.length === 0" class="text-center py-5">
                            <div class="mb-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="bi bi-tag text-primary fs-1"></i>
                                </div>
                            </div>
                            <h3 class="fw-bold text-dark mb-2">Nenhum cupom encontrado</h3>
                            <p class="text-muted mb-4">Crie seu primeiro cupom para começar a oferecer descontos aos seus clientes!</p>
                            <button type="button" class="btn btn-primary btn-lg px-4" @click="showCreateModal">
                                <i class="bi bi-plus-circle me-2"></i> Criar Primeiro Cupom
                            </button>
                        </div>

                        <!-- Visualização em Tabela -->
                        <div v-else class="card">
                            <coupons-table
                                :coupons="coupons"
                                @edit-coupon="showEditModal"
                                @delete-coupon="deleteCoupon"
                            ></coupons-table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modais -->
    <coupon-modal
        modal-id="couponModal"
        ref="couponModal"
        @success="onCouponSuccess"
        @cancel="onCouponCancel"
    ></coupon-modal>

</div>



<?php $this->load->view('components/BaseModal'); ?>
<?php $this->load->view('components/CouponForm'); ?>
<?php $this->load->view('components/CouponModal'); ?>
<?php $this->load->view('components/CouponsTable'); ?>
<?php $this->load->view('components/register_components'); ?>

<script>
// Aguarda o carregamento completo da página
window.addEventListener('load', function() {
    // Aguarda um pouco mais para garantir que todos os scripts sejam carregados
    setTimeout(function() {
        if (typeof Vue === 'undefined') {
            console.error('Vue.js não foi carregado');
            return;
        }

        // Verifica se os componentes estão disponíveis
        if (!window.VueComponents) {
            console.error('Componentes Vue não foram carregados');
            return;
        }

        // Cria a aplicação Vue usando uma variável única
        const couponsApp = Vue.createApp({
            name: 'CouponsApp',
            components: window.VueComponents,
            data() {
                const coupons = <?= json_encode($coupons) ?>;
                return {
                    coupons: coupons,
                    loading: false
                };
            },
            mounted() {
                // Aplicação montada com sucesso
                console.log('Aplicação de cupons montada com', this.coupons.length, 'cupons');
            },
            methods: {
                showCreateModal() {
                    if (this.$refs.couponModal) {
                        this.$refs.couponModal.show();
                    } else {
                        console.error('couponModal ref não encontrado');
                    }
                },

                showEditModal(coupon) {
                    // Validação do cupom
                    if (!coupon || !coupon.id) {
                        console.error('showEditModal - cupom inválido:', coupon);
                        utils.showToast('Erro', 'Dados do cupom inválidos', 'error');
                        return;
                    }

                    if (this.$refs.couponModal) {
                        this.$refs.couponModal.show(coupon, true);
                    } else {
                        console.error('couponModal ref não encontrado');
                        utils.showToast('Erro', 'Modal de edição não encontrado', 'error');
                    }
                },

                async deleteCoupon(coupon) {
                    try {
                        const response = await fetch(`<?= base_url('coupons/delete/') ?>${coupon.id}`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.ok) {
                            // Remove o cupom da lista
                            const index = this.coupons.findIndex(c => c.id === coupon.id);
                            if (index !== -1) {
                                this.coupons.splice(index, 1);
                            }

                            utils.showToast('Sucesso', 'Cupom excluído com sucesso', 'success');
                        } else {
                            utils.showToast('Erro', 'Erro ao excluir cupom', 'error');
                        }
                    } catch (error) {
                        console.error('Erro ao excluir cupom:', error);
                        utils.showToast('Erro', 'Erro ao excluir cupom', 'error');
                    }
                },

                async loadCoupons() {
                    try {
                        this.loading = true;
                        const response = await fetch('<?= base_url("coupons/get_coupons") ?>');
                        const data = await response.json();

                        if (data.success) {
                            this.coupons = data.coupons;
                        } else {
                            utils.showToast('Erro', 'Erro ao carregar cupons', 'error');
                        }
                    } catch (error) {
                        console.error('Erro ao carregar cupons:', error);
                        utils.showToast('Erro', 'Erro ao carregar cupons', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                onCouponSuccess(data) {
                    utils.showToast('Sucesso', data.message, 'success');
                    this.loadCoupons();
                },

                onCouponCancel() {
                    // Modal cancelado
                }
            }
        });

        // Monta a aplicação
        couponsApp.mount('#app');
    }, 100);
});
</script>
