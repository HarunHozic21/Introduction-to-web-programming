<?php
require 'vendor/autoload.php'; 

require_once __DIR__ . '/rest/routes/cart.php';
require_once __DIR__ . '/rest/routes/orders.php';
require_once __DIR__ . '/rest/routes/orderItems.php';
require_once __DIR__ . '/rest/routes/payments.php';
require_once __DIR__ . '/rest/routes/products.php';
require_once __DIR__ . '/rest/routes/users.php';

require_once __DIR__ . '/rest/services/CartService.php';
require_once __DIR__ . '/rest/services/OrderService.php';
require_once __DIR__ . '/rest/services/OrderItemsService.php';
require_once __DIR__ . '/rest/services/PaymentService.php';
require_once __DIR__ . '/rest/services/ProductService.php';
require_once __DIR__ . '/rest/services/UserService.php';

Flight::register('cart_service', 'CartService');
Flight::register('order_service', 'OrderService');
Flight::register('orderitems_service', 'OrderItemsService');
Flight::register('payment_service', 'PaymentService');
Flight::register('product_service', 'ProductService');
Flight::register('user_service', 'UserService');



Flight::start(); 
?>
