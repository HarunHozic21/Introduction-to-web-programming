<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}
require './vendor/autoload.php';    


require_once __DIR__ . '/rest/services/CartService.php';
require_once __DIR__ . '/rest/services/OrderService.php';
require_once __DIR__ . '/rest/services/OrderItemsService.php';
require_once __DIR__ . '/rest/services/PaymentsService.php';
require_once __DIR__ . '/rest/services/ProductsService.php';
require_once __DIR__ . '/rest/services/UserService.php';
require_once __DIR__ . '/rest/services/AuthService.php';

require_once __DIR__ . '/middleware/AuthMiddleware.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

Flight::register('cart_service', 'CartService');
Flight::register('order_service', 'OrderService');
Flight::register('orderitems_service', 'OrderItemsService');
Flight::register('payment_service', 'PaymentsService');
Flight::register('product_service', 'ProductsService');
Flight::register('user_service', 'UserService');
Flight::register('auth_service', 'AuthService');
Flight::register('auth_middleware', "AuthMiddleware");

 Flight::route('/*', function() {
    $url = Flight::request()->url;

    if (
        strpos($url, '/auth/login') === 0 ||
        strpos($url, '/auth/register') === 0
    ) {
        return TRUE;
    }

    try {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        if (!$token) {
            throw new Exception("Missing Authorization header");
        }

        Flight::auth_middleware()->verifyToken($token);
        return TRUE;
    } catch (\Exception $e) {
        Flight::halt(401, "Unauthorized: " . $e->getMessage());
    }
});

require_once __DIR__ . '/rest/routes/auth.php';
require_once __DIR__ . '/rest/routes/cart.php';
require_once __DIR__ . '/rest/routes/orders.php';
require_once __DIR__ . '/rest/routes/orderItems.php';
require_once __DIR__ . '/rest/routes/payments.php';
require_once __DIR__ . '/rest/routes/products.php';
require_once __DIR__ . '/rest/routes/users.php';


Flight::start(); 
?>
