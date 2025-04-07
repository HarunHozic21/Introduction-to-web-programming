<?php
require_once 'BaseDao.php';

class CartDao extends BaseDao {
    public function __construct() {
        parent::__construct("cart");
    }

    public function getCartItemById($id) {
        return $this->getById($id);
    }

    public function getCartByUserId($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM cart WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function addToCart($data) {
        return $this->insert($data);
    }

    public function updateCartItem($id, $data) {
        return $this->update($id, $data);
    }

    public function deleteCartItem($id) {
        return $this->delete($id);
    }

    public function clearCartForUser($user_id) {
        $stmt = $this->connection->prepare("DELETE FROM cart WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    public function updateQuantity($user_id, $product_id, $quantity) {
        $stmt = $this->connection->prepare("UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        return $stmt->execute();
    }
}
?>
