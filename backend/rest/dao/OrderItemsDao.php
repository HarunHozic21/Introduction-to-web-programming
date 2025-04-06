<?php
require_once 'BaseDao.php';

class OrderItemDao extends BaseDao {
    public function __construct() {
        parent::__construct("order_items");
    }

    public function getOrderItemById($id) {
        return $this->getById($id);
    }

    public function getItemsByOrderId($order_id) {
        $stmt = $this->connection->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createOrderItem($data) {
        return $this->insert($data);
    }

    public function updateOrderItem($id, $data) {
        return $this->update($id, $data);
    }

    public function deleteOrderItem($id) {
        return $this->delete($id);
    }

    public function getTotalItemsForOrder($order_id) {
        $stmt = $this->connection->prepare("SELECT SUM(quantity) as total_items FROM order_items WHERE order_id = :order_id");
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function deleteItemsByOrderId($order_id) {
        $stmt = $this->connection->prepare("DELETE FROM order_items WHERE order_id = :order_id");
        $stmt->bindParam(':order_id', $order_id);
        return $stmt->execute();
    }
}
?>
