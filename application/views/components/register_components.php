<script>
// Registra todos os componentes globalmente para Vue 3
// Componentes disponíveis globalmente
console.log('=== REGISTRO DE COMPONENTES ===');
console.log('Verificando componentes individuais:');
console.log('- BaseModal:', typeof BaseModal !== 'undefined' ? 'OK' : 'FALTANDO');
console.log('- ProductForm:', typeof ProductForm !== 'undefined' ? 'OK' : 'FALTANDO');
console.log('- ProductModal:', typeof ProductModal !== 'undefined' ? 'OK' : 'FALTANDO');
console.log('- BuyModal:', typeof BuyModal !== 'undefined' ? 'OK' : 'FALTANDO');
console.log('- BuyModal details:', typeof BuyModal !== 'undefined' ? BuyModal : 'UNDEFINED');
console.log('- ProductsTable:', typeof ProductsTable !== 'undefined' ? 'OK' : 'FALTANDO');

// Registra os componentes globalmente
window.VueComponents = {};

if (typeof BaseModal !== 'undefined') {
    window.VueComponents.BaseModal = BaseModal;
    console.log('✓ BaseModal registrado');
}
if (typeof ProductForm !== 'undefined') {
    window.VueComponents.ProductForm = ProductForm;
    console.log('✓ ProductForm registrado');
}
if (typeof ProductModal !== 'undefined') {
    window.VueComponents.ProductModal = ProductModal;
    console.log('✓ ProductModal registrado');
}
if (typeof BuyModal !== 'undefined') {
    window.VueComponents.BuyModal = BuyModal;
    console.log('✓ BuyModal registrado');
}
if (typeof ProductsTable !== 'undefined') {
    window.VueComponents.ProductsTable = ProductsTable;
    console.log('✓ ProductsTable registrado');
}

console.log('Componentes registrados:', Object.keys(window.VueComponents));
console.log('=== FIM DO REGISTRO ===');
</script> 