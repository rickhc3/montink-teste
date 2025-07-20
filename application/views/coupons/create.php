<style>
    .coupon-form-container {
        max-width: 800px;
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
    
    .form-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .form-row.full-width {
        grid-template-columns: 1fr;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-group label {
        font-weight: 600;
        color: var(--dark-gray);
        margin-bottom: 0.5rem;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
    }
    
    .form-group small {
        color: var(--medium-gray);
        margin-top: 0.25rem;
        font-size: 0.85rem;
    }
    
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .checkbox-group input[type="checkbox"] {
        width: auto;
        margin: 0;
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }
    
    .discount-preview {
        background: var(--light-gray);
        border: 1px solid var(--border-color);
        border-radius: 4px;
        padding: 1rem;
        margin-top: 1rem;
    }
    
    .discount-preview h4 {
        margin: 0 0 0.5rem 0;
        color: var(--primary-color);
    }
    
    .preview-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.25rem;
    }
    
    .error-message {
        color: var(--danger-color);
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
    }
</style>

<div class="coupon-form-container">
    <div class="header-section">
        <h1 class="page-title">
            <i class="fas fa-plus-circle"></i>
            Criar Novo Cupom
        </h1>
        <a href="<?= base_url('coupons') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Voltar
        </a>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (validation_errors()): ?>
        <div class="alert alert-danger">
            <?= validation_errors() ?>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <?= form_open('coupons/create', ['id' => 'coupon-form']) ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="code">Código do Cupom *</label>
                    <input type="text" 
                           id="code" 
                           name="code" 
                           value="<?= set_value('code') ?>"
                           placeholder="Ex: DESCONTO10"
                           required
                           style="text-transform: uppercase;">
                    <small>Código único que o cliente irá digitar</small>
                    <?= form_error('code', '<div class="error-message">', '</div>') ?>
                </div>
                
                <div class="form-group">
                    <label for="discount_type">Tipo de Desconto *</label>
                    <select id="discount_type" name="discount_type" required>
                        <option value="">Selecione o tipo</option>
                        <option value="percentage" <?= set_select('discount_type', 'percentage') ?>>Porcentagem (%)</option>
                        <option value="fixed" <?= set_select('discount_type', 'fixed') ?>>Valor Fixo (R$)</option>
                    </select>
                    <?= form_error('discount_type', '<div class="error-message">', '</div>') ?>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="discount_value">Valor do Desconto *</label>
                    <input type="number" 
                           id="discount_value" 
                           name="discount_value" 
                           value="<?= set_value('discount_value') ?>"
                           step="0.01"
                           min="0"
                           placeholder="0.00"
                           required>
                    <small id="discount-hint">Digite o valor do desconto</small>
                    <?= form_error('discount_value', '<div class="error-message">', '</div>') ?>
                </div>
                
                <div class="form-group">
                    <label for="min_amount">Valor Mínimo do Pedido *</label>
                    <input type="number" 
                           id="min_amount" 
                           name="min_amount" 
                           value="<?= set_value('min_amount', '0') ?>"
                           step="0.01"
                           min="0"
                           placeholder="0.00"
                           required>
                    <small>Valor mínimo do carrinho para usar o cupom</small>
                    <?= form_error('min_amount', '<div class="error-message">', '</div>') ?>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="valid_from">Válido A Partir De *</label>
                    <input type="date" 
                           id="valid_from" 
                           name="valid_from" 
                           value="<?= set_value('valid_from', date('Y-m-d')) ?>"
                           required>
                    <?= form_error('valid_from', '<div class="error-message">', '</div>') ?>
                </div>
                
                <div class="form-group">
                    <label for="valid_until">Válido Até *</label>
                    <input type="date" 
                           id="valid_until" 
                           name="valid_until" 
                           value="<?= set_value('valid_until') ?>"
                           required>
                    <?= form_error('valid_until', '<div class="error-message">', '</div>') ?>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="max_uses">Limite de Usos</label>
                    <input type="number" 
                           id="max_uses" 
                           name="max_uses" 
                           value="<?= set_value('max_uses') ?>"
                           min="1"
                           placeholder="Deixe vazio para ilimitado">
                    <small>Número máximo de vezes que o cupom pode ser usado</small>
                    <?= form_error('max_uses', '<div class="error-message">', '</div>') ?>
                </div>
                
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="checkbox-group">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1" 
                               <?= set_checkbox('is_active', '1', TRUE) ?>>
                        <label for="is_active">Cupom ativo</label>
                    </div>
                    <small>Desmarque para criar um cupom inativo</small>
                </div>
            </div>
            
            <div class="form-row full-width">
                <div class="form-group">
                    <label for="description">Descrição</label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              placeholder="Descrição opcional do cupom..."><?= set_value('description') ?></textarea>
                    <small>Descrição interna para identificação do cupom</small>
                    <?= form_error('description', '<div class="error-message">', '</div>') ?>
                </div>
            </div>
            
            <div class="discount-preview" id="discount-preview" style="display: none;">
                <h4><i class="fas fa-calculator"></i> Simulação de Desconto</h4>
                <div class="preview-item">
                    <span>Valor do carrinho:</span>
                    <span id="cart-value">R$ 0,00</span>
                </div>
                <div class="preview-item">
                    <span>Desconto aplicado:</span>
                    <span id="discount-applied">R$ 0,00</span>
                </div>
                <div class="preview-item" style="font-weight: bold; border-top: 1px solid #ddd; padding-top: 0.5rem; margin-top: 0.5rem;">
                    <span>Total final:</span>
                    <span id="final-total">R$ 0,00</span>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="<?= base_url('coupons') ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Criar Cupom
                </button>
            </div>
            
        <?= form_close() ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');
    const discountTypeSelect = document.getElementById('discount_type');
    const discountValueInput = document.getElementById('discount_value');
    const minAmountInput = document.getElementById('min_amount');
    const discountHint = document.getElementById('discount-hint');
    const discountPreview = document.getElementById('discount-preview');
    
    // Auto uppercase code
    codeInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });
    
    // Update discount hint based on type
    discountTypeSelect.addEventListener('change', function() {
        if (this.value === 'percentage') {
            discountHint.textContent = 'Digite a porcentagem (ex: 10 para 10%)';
            discountValueInput.setAttribute('max', '100');
        } else if (this.value === 'fixed') {
            discountHint.textContent = 'Digite o valor em reais (ex: 25.00)';
            discountValueInput.removeAttribute('max');
        } else {
            discountHint.textContent = 'Digite o valor do desconto';
            discountValueInput.removeAttribute('max');
        }
        updatePreview();
    });
    
    // Update preview when values change
    [discountTypeSelect, discountValueInput, minAmountInput].forEach(input => {
        input.addEventListener('input', updatePreview);
    });
    
    function updatePreview() {
        const discountType = discountTypeSelect.value;
        const discountValue = parseFloat(discountValueInput.value) || 0;
        const minAmount = parseFloat(minAmountInput.value) || 0;
        
        if (discountType && discountValue > 0) {
            discountPreview.style.display = 'block';
            
            // Simulate with minimum amount + 50
            const simulationAmount = Math.max(minAmount, 100) + 50;
            let discountApplied = 0;
            
            if (discountType === 'percentage') {
                discountApplied = (simulationAmount * discountValue) / 100;
            } else {
                discountApplied = discountValue;
            }
            
            const finalTotal = Math.max(0, simulationAmount - discountApplied);
            
            document.getElementById('cart-value').textContent = formatCurrency(simulationAmount);
            document.getElementById('discount-applied').textContent = formatCurrency(discountApplied);
            document.getElementById('final-total').textContent = formatCurrency(finalTotal);
        } else {
            discountPreview.style.display = 'none';
        }
    }
    
    function formatCurrency(value) {
        return 'R$ ' + value.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
    
    // Set minimum date for valid_until
    const validFromInput = document.getElementById('valid_from');
    const validUntilInput = document.getElementById('valid_until');
    
    validFromInput.addEventListener('change', function() {
        validUntilInput.setAttribute('min', this.value);
        if (validUntilInput.value && validUntilInput.value < this.value) {
            validUntilInput.value = this.value;
        }
    });
    
    // Form validation
    document.getElementById('coupon-form').addEventListener('submit', function(e) {
        const validFrom = new Date(validFromInput.value);
        const validUntil = new Date(validUntilInput.value);
        
        if (validUntil <= validFrom) {
            e.preventDefault();
            alert('A data final deve ser posterior à data inicial.');
            return false;
        }
        
        if (discountTypeSelect.value === 'percentage' && discountValueInput.value > 100) {
            e.preventDefault();
            alert('O desconto em porcentagem não pode ser maior que 100%.');
            return false;
        }
    });
});
</script>