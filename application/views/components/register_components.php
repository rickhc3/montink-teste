<script>
// Registra todos os componentes globalmente para Vue 3
// Componentes disponíveis globalmente
// Verificando componentes individuais

// Registra os componentes globalmente
window.VueComponents = {};

if (typeof BaseModal !== 'undefined') {
    window.VueComponents.BaseModal = BaseModal;

}
if (typeof ProductForm !== 'undefined') {
    window.VueComponents.ProductForm = ProductForm;

}
if (typeof ProductModal !== 'undefined') {
    window.VueComponents.ProductModal = ProductModal;

}
if (typeof BuyModal !== 'undefined') {
    window.VueComponents.BuyModal = BuyModal;

}
if (typeof ProductsTable !== 'undefined') {
    window.VueComponents.ProductsTable = ProductsTable;

}
if (typeof CouponForm !== 'undefined') {
    window.VueComponents.CouponForm = CouponForm;

}
if (typeof CouponModal !== 'undefined') {
    window.VueComponents.CouponModal = CouponModal;

}
if (typeof CouponsTable !== 'undefined') {
    window.VueComponents.CouponsTable = CouponsTable;

}


</script>