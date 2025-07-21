<style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #34495e;
        --accent-color: #3498db;
        --success-color: #27ae60;
        --danger-color: #e74c3c;
        --warning-color: #f39c12;
        --light-gray: #f8f9fa;
        --medium-gray: #6c757d;
        --border-color: #dee2e6;
    }
    
    [v-cloak] {
        display: none;
    }
    
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
    }
    
    .main-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .page-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 2rem;
    }
    
    .coupons-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: 600;
        color: var(--primary-color);
        margin: 0;
    }
    
    .coupon-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        margin-bottom: 1rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .coupon-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .coupon-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .coupon-code {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary-color);
        background: var(--light-gray);
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-family: monospace;
    }
    
    .coupon-status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .status-active {
        background: var(--success-color);
        color: white;
    }
    
    .status-inactive {
        background: var(--danger-color);
        color: white;
    }
    
    .status-expired {
        background: var(--medium-gray);
        color: white;
    }
    
    .coupon-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .detail-item {
        display: flex;
        flex-direction: column;
    }
    
    .detail-label {
        font-size: 0.85rem;
        color: var(--medium-gray);
        margin-bottom: 0.25rem;
    }
    
    .detail-value {
        font-weight: 600;
        color: var(--dark-gray);
    }
    
    .coupon-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        margin-top: 2rem;
    }
    
    .pagination a, .pagination span {
        padding: 0.5rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        text-decoration: none;
        color: var(--primary-color);
    }
    
    .pagination .current {
        background: var(--primary-color);
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--medium-gray);
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>

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
                                <?= count($coupons) ?> <?= count($coupons) === 1 ? 'cupom' : 'cupons' ?>
                            </span>

                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#couponModal">
                                <i class="bi bi-plus-circle"></i> Novo Cupom
                            </button>
                        </div>
                    </div>
                </div>

                <div class="coupons-container p-4">
                    <!-- Mensagens Flash -->
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            <?= $this->session->flashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <?= $this->session->flashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Estado vazio -->
                    <?php if (empty($coupons)): ?>
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="bi bi-tag text-primary fs-1"></i>
                                </div>
                            </div>
                            <h3 class="fw-bold text-dark mb-2">Nenhum cupom encontrado</h3>
                            <p class="text-muted mb-4">Crie seu primeiro cupom para começar a oferecer descontos aos seus clientes.</p>
                            <a href="<?= base_url('coupons/create') ?>" class="btn btn-primary btn-lg px-4">
                                <i class="bi bi-plus-circle me-2"></i> Criar Primeiro Cupom
                            </a>
                        </div>
                    <?php else: ?>

                        
                        <!-- Lista de cupons em tabela -->
                        <div id="tableViewContainer" class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Código</th>
                                        <th>Status</th>
                                        <th>Tipo</th>
                                        <th>Desconto</th>
                                        <th>Valor Mínimo</th>
                                        <th>Validade</th>
                                        <th>Usos</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($coupons as $coupon): ?>
                                        <?php
                                        $today = date('Y-m-d');
                                        $status = 'active';
                                        $status_text = 'Ativo';
                                        $status_class = 'bg-success';
                                        
                                        if (!$coupon->is_active) {
                                            $status = 'inactive';
                                            $status_text = 'Inativo';
                                            $status_class = 'bg-secondary';
                                        } elseif ($today > $coupon->valid_until) {
                                            $status = 'expired';
                                            $status_text = 'Expirado';
                                            $status_class = 'bg-danger';
                                        } elseif ($today < $coupon->valid_from) {
                                            $status = 'pending';
                                            $status_text = 'Aguardando';
                                            $status_class = 'bg-warning';
                                        }
                                        
                                        if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
                                            $status = 'exhausted';
                                            $status_text = 'Esgotado';
                                            $status_class = 'bg-danger';
                                        }
                                        
                                        $typeText = $coupon->discount_type === 'percentage' ? 'Percentual' : 'Valor Fixo';
                                        $discountValue = $coupon->discount_type === 'percentage' 
                                            ? number_format($coupon->discount_value, 1) . '%' 
                                            : 'R$ ' . number_format($coupon->discount_value, 2, ',', '.');
                                        $minValue = 'R$ ' . number_format($coupon->min_amount, 2, ',', '.');
                                        $validUntil = date('d/m/Y', strtotime($coupon->valid_until));
                                        $usageText = $coupon->used_count . ' / ' . ($coupon->max_uses ?: '∞');
                                        ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($coupon->code) ?></strong></td>
                                            <td><span class="badge <?= $status_class ?>"><?= $status_text ?></span></td>
                                            <td><?= $typeText ?></td>
                                            <td><?= $discountValue ?></td>
                                            <td><?= $minValue ?></td>
                                            <td><?= $validUntil ?></td>
                                            <td><?= $usageText ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" onclick="editCoupon(<?= $coupon->id ?>)">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <a class="btn btn-outline-danger" href="<?= base_url('coupons/delete/' . $coupon->id) ?>" 
                                                       onclick="return confirm('Tem certeza que deseja excluir este cupom?')">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginação -->
                        <?php if ($total_pages > 1): ?>
                            <nav aria-label="Paginação de cupons" class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <?php if ($current_page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?= base_url('coupons?page=' . ($current_page - 1)) ?>">
                                                <i class="bi bi-chevron-left"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                                            <a class="page-link" href="<?= base_url('coupons?page=' . $i) ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($current_page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?= base_url('coupons?page=' . ($current_page + 1)) ?>">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Criar/Editar Cupom -->
<div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="couponModalLabel">Novo Cupom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="couponForm" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">Código do Cupom</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                        <div class="col-md-6">
                            <label for="discount_type" class="form-label">Tipo de Desconto</label>
                            <select class="form-select" id="discount_type" name="discount_type" required>
                                <option value="percentage">Percentual</option>
                                <option value="fixed">Valor Fixo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="discount_value" class="form-label">Valor do Desconto</label>
                            <input type="number" class="form-control" id="discount_value" name="discount_value" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label for="min_amount" class="form-label">Valor Mínimo</label>
                            <input type="number" class="form-control" id="min_amount" name="min_amount" step="0.01">
                        </div>
                        <div class="col-md-6">
                            <label for="valid_from" class="form-label">Válido de</label>
                            <input type="date" class="form-control" id="valid_from" name="valid_from" required>
                        </div>
                        <div class="col-md-6">
                            <label for="valid_until" class="form-label">Válido até</label>
                            <input type="date" class="form-control" id="valid_until" name="valid_until" required>
                        </div>
                        <div class="col-md-6">
                            <label for="max_uses" class="form-label">Limite de Usos</label>
                            <input type="number" class="form-control" id="max_uses" name="max_uses" min="1">
                            <div class="form-text">Deixe em branco para uso ilimitado</div>
                        </div>
                        <div class="col-md-6">
                            <label for="is_active" class="form-label">Status</label>
                            <select class="form-select" id="is_active" name="is_active" required>
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Cupom</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Aguardar o carregamento completo da página
    document.addEventListener('DOMContentLoaded', function() {
        
        // Reset do modal ao fechar
        const couponModal = document.getElementById('couponModal');
        if (couponModal) {
            couponModal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('couponModalLabel').textContent = 'Novo Cupom';
                document.getElementById('couponForm').action = '<?= base_url("coupons/store") ?>';
                document.getElementById('couponForm').reset();
            });
        }
        
        // Verificar se Vue está disponível
        if (typeof Vue !== 'undefined') {
            const { createApp } = Vue;
            
            createApp({
                data() {
                    return {
                        // dados da aplicação se necessário
                    }
                },
                mounted() {
                    // Vue app mounted for coupons
                }
            }).mount('#app');
        } else {
            // Se Vue não estiver disponível, remover v-cloak manualmente
            const app = document.getElementById('app');
            if (app) {
                app.removeAttribute('v-cloak');
            }
        }
    });
    
    // Função para editar cupom
    function editCoupon(id) {
        // Buscar dados do cupom via AJAX
        fetch('<?= base_url("coupons/get/") ?>' + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const coupon = data.coupon;
                    document.getElementById('couponModalLabel').textContent = 'Editar Cupom';
                    document.getElementById('couponForm').action = '<?= base_url("coupons/update/") ?>' + id;
                    
        
                    document.getElementById('code').value = coupon.code;
                    document.getElementById('discount_type').value = coupon.discount_type;
                    document.getElementById('discount_value').value = coupon.discount_value;
                    document.getElementById('min_amount').value = coupon.min_amount;
                    document.getElementById('valid_from').value = coupon.valid_from;
                    document.getElementById('valid_until').value = coupon.valid_until;
                    document.getElementById('max_uses').value = coupon.max_uses;
                    document.getElementById('is_active').value = coupon.is_active;
                    
        
                    new bootstrap.Modal(document.getElementById('couponModal')).show();
                } else {
                    alert('Erro ao carregar dados do cupom');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao carregar dados do cupom');
            });
    }
</script>