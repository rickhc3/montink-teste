<style>
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

<div class="coupons-container">
    <div class="header-section">
        <h1 class="page-title">
            <i class="fas fa-ticket-alt"></i>
            Gerenciar Cupons
        </h1>
        <a href="<?= base_url('coupons/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Novo Cupom
        </a>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($coupons)): ?>
        <div class="empty-state">
            <i class="fas fa-ticket-alt"></i>
            <h3>Nenhum cupom encontrado</h3>
            <p>Crie seu primeiro cupom para começar a oferecer descontos aos seus clientes.</p>
            <a href="<?= base_url('coupons/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Criar Primeiro Cupom
            </a>
        </div>
    <?php else: ?>
        <?php foreach ($coupons as $coupon): ?>
            <?php
                $today = date('Y-m-d');
                $status = 'active';
                $status_text = 'Ativo';
                
                if (!$coupon->is_active) {
                    $status = 'inactive';
                    $status_text = 'Inativo';
                } elseif ($today > $coupon->valid_until) {
                    $status = 'expired';
                    $status_text = 'Expirado';
                } elseif ($today < $coupon->valid_from) {
                    $status = 'inactive';
                    $status_text = 'Aguardando';
                }
                
                if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
                    $status = 'expired';
                    $status_text = 'Esgotado';
                }
            ?>
            <div class="coupon-card">
                <div class="coupon-header">
                    <div class="coupon-code"><?= $coupon->code ?></div>
                    <div class="coupon-status status-<?= $status ?>"><?= $status_text ?></div>
                </div>
                
                <div class="coupon-details">
                    <div class="detail-item">
                        <span class="detail-label">Tipo de Desconto</span>
                        <span class="detail-value">
                            <?= $coupon->discount_type === 'percentage' ? 'Porcentagem' : 'Valor Fixo' ?>
                        </span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Valor do Desconto</span>
                        <span class="detail-value">
                            <?php if ($coupon->discount_type === 'percentage'): ?>
                                <?= number_format($coupon->discount_value, 1) ?>%
                            <?php else: ?>
                                R$ <?= number_format($coupon->discount_value, 2, ',', '.') ?>
                            <?php endif; ?>
                        </span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Valor Mínimo</span>
                        <span class="detail-value">R$ <?= number_format($coupon->min_amount, 2, ',', '.') ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Período de Validade</span>
                        <span class="detail-value">
                            <?= date('d/m/Y', strtotime($coupon->valid_from)) ?> - 
                            <?= date('d/m/Y', strtotime($coupon->valid_until)) ?>
                        </span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Usos</span>
                        <span class="detail-value">
                            <?= $coupon->used_count ?>
                            <?php if ($coupon->max_uses): ?>
                                / <?= $coupon->max_uses ?>
                            <?php else: ?>
                                / Ilimitado
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                
                <div class="coupon-actions">
                    <a href="<?= base_url('coupons/edit/' . $coupon->id) ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i>
                        Editar
                    </a>
                    <a href="<?= base_url('coupons/delete/' . $coupon->id) ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Tem certeza que deseja deletar este cupom?')">
                        <i class="fas fa-trash"></i>
                        Deletar
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <a href="<?= base_url('coupons?page=' . ($current_page - 1)) ?>">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i == $current_page): ?>
                        <span class="current"><?= $i ?></span>
                    <?php else: ?>
                        <a href="<?= base_url('coupons?page=' . $i) ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($current_page < $total_pages): ?>
                    <a href="<?= base_url('coupons?page=' . ($current_page + 1)) ?>">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>