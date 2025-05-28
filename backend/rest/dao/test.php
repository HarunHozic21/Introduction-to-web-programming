<?php
require_once 'UserDao.php';
require_once 'OrderDao.php';
require_once 'ProductsDao.php';
require_once 'CartDao.php';
require_once 'OrderItemsDao.php';
require_once 'PaymentsDao.php';

$userDao = new UserDao();
$orderDao = new OrderDao();
$productDao = new ProductsDao();
$cartDao = new CartDao();
$orderItemDao = new OrderItemDao();
$paymentDao = new PaymentsDao();


$userDao->insert([
    'first_name' => 'Harun',
    'last_name'=>"Hozić",
    'email' => 'Harun@admin.com',
    'password_hash' => 'Harun123',   
    'phone' => '+38762617774',
    'Address' => 'Hasana Suceske 3',
    'role' => 'admin',
    'created_at' => date('Y-m-d H:i:s')
]);
