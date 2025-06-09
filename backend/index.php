<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
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
