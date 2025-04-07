<?php
require_once 'UserDao.php';
require_once 'OrderDao.php';
require_once 'ProductsDao.php';
require_once 'CartDao.php';
require_once 'OrderItemsDao.php';
require_once 'PaymentsDao.php';

$userDao = new UserDao();
$orderDao = new OrderDao();
$productDao = new ProductDao();
$cartDao = new CartDao();
$orderItemDao = new OrderItemDao();
$paymentDao = new PaymentsDao();


$productDao->insert([
    'name' => 'Nike Air Max',
    'brand' => 'Nike',
    'description' => 'Comfortable running shoes',
    'price' => 120.00,
    'stock' => 50,
    'image_url' => 'nike_air_max.jpg',
    'created_at' => date('Y-m-d H:i:s')
]);

$productDao->insert([
    'name' => 'Adidas Ultraboost',
    'brand' => 'Adidas',
    'description' => 'High-performance sports shoes',
    'price' => 140.00,
    'stock' => 30,
    'image_url' => 'adidas_ultraboost.jpg',
    'created_at' => date('Y-m-d H:i:s')
]);


$cartDao->insert([
    'user_id' => 1,
    'product_id' => 1,
    'quantity' => 2,
    'added_at' => date('Y-m-d H:i:s')
]);

$cartDao->insert([
    'user_id' => 1,
    'product_id' => 2,
    'quantity' => 1,
    'added_at' => date('Y-m-d H:i:s')
]);

$orderDao->insert([
    'user_id' => 1,
    'order_status' => 'Pending',
    'total_price' => 380.00,
    'created_at' => date('Y-m-d H:i:s')
]);

$orderItemDao->insert([
    'order_id' => 1,
    'product_id' => 1,
    'quantity' => 2,
    'subtotal' => 240.00
]);

$orderItemDao->insert([
    'order_id' => 1,
    'product_id' => 2,
    'quantity' => 1,
    'subtotal' => 140.00
]);

$paymentDao->insert([
    'order_id' => 1,
    'user_id' => 1,
    'payment_method' => 'credit_card',
    'payment_status' => 'completed',
    'transaction_date' => date('Y-m-d H:i:s')
]);



$users = $userDao->getAll();
print_r($users);

$orders = $orderDao->getAll();
print_r($orders);

echo "\nFetching user by email:\n";
print_r($userDao->getByEmail('john@example.com'));

echo "\nGetting cart items for user 1:\n";
print_r($cartDao->getCartByUserId(1));

echo "\nFetching orders for user 1:\n";
print_r($orderDao->getOrdersByUserId(1));

echo "\nGetting items for order 1:\n";
print_r($orderItemDao->getItemsByOrderId(1));

echo "\nGetting payment for order 1:\n";
print_r($paymentDao->getPaymentByOrderId(1));

?>
