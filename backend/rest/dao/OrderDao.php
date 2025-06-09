<?php

require_once __DIR__ . '/BaseDao.php';

class OrderDao extends BaseDao {
   public function __construct() {
       parent::__construct("orders");
   }

   // Override the base getById method to use order_id
   public function getById($id) {
       return $this->query_unique("SELECT * FROM orders WHERE order_id = :id", ['id' => $id]);
   }

    public function getByUserId($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM orders WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOrderById($id) {
        return $this->getById($id); 
    }

    public function createOrder($data) {
        return $this->insert($data);
    }

    public function updateOrder($id, $data) {
        return $this->update($data, $id, "order_id"); // Use order_id as the key column
    }

    public function deleteOrder($id) {
        $stmt = $this->connection->prepare("DELETE FROM orders WHERE order_id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public function getOrdersByStatus($status) {
        $stmt = $this->connection->prepare("SELECT * FROM orders WHERE order_status = :status");
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOrdersByUserId($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM orders WHERE user_id = :user_id");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>