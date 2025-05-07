<?php
require_once __DIR__ . '/../dao/CartDao.php';

class CartService {
    private $dao;

    public function __construct() {
        $this->dao = new CartDao();
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function getCartByUser($user_id) {
        return $this->dao->getCartByUserId($user_id);
    }

    public function getItemByProduct($product_id) {
        return $this->dao->getCartByProductId( $product_id);
    }

    public function updateQuantity($user_id, $product_id, $quantity) {
        return $this->dao->updateQuantity($user_id, $product_id, $quantity);
    }

    public function addItem($item) {
        return $this->dao->insert($item);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }
}