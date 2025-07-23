<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

// =========================================================================
// CONFIGURAÇÕES BÁSICAS DO SISTEMA
// =========================================================================
$route['default_controller'] = 'products';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// =========================================================================
// ROTAS PRINCIPAIS
// =========================================================================
// Redireciona a raiz para products
$route[''] = 'products/index';

// =========================================================================
// SEÇÃO: PRODUTOS
// =========================================================================
// CRUD de produtos
$route['products/create'] = 'products/create';
$route['products/store']  = 'products/store';
$route['products/edit/(:num)'] = 'products/edit/$1';
$route['products/update'] = 'products/update';
$route['products/delete/(:num)'] = 'products/delete/$1';

// Gestão de estoque
$route['products/get_stock/(:num)'] = 'products/get_stock/$1';
$route['products/get_products'] = 'products/get_products';

// Carrinho de compras
$route['products/add_to_cart'] = 'products/add_to_cart';
$route['cart'] = 'products/cart';
$route['products/remove_from_cart/(:any)'] = 'products/remove_from_cart/$1';
$route['products/clear_cart'] = 'products/clear_cart';

// Checkout e finalização
$route['products/checkout'] = 'products/checkout';
$route['products/finalize_order'] = 'products/finalize_order';
$route['products/calculate_shipping'] = 'products/calculate_shipping';

// Testes e desenvolvimento
$route['products/test'] = 'products/test';

// =========================================================================
// SEÇÃO: CUPONS DE DESCONTO
// =========================================================================
// CRUD de cupons
$route['coupons'] = 'coupons/index';
$route['coupons/create'] = 'coupons/create';
$route['coupons/edit/(:num)'] = 'coupons/edit/$1';
$route['coupons/delete/(:num)'] = 'coupons/delete/$1';

// Validação de cupons
$route['coupons/validate'] = 'coupons/validate';

// =========================================================================
// SEÇÃO: WEBHOOKS
// =========================================================================
// Webhook para atualização de status de pedidos
$route['webhook/order_status'] = 'webhook/order_status';

// Health check para webhooks
$route['webhook/health'] = 'webhook/health';

