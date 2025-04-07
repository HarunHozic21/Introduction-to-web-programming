<?php
require_once 'BaseDao.php';


class OrderDao extends BaseDao {
   public function __construct() {
       parent::__construct("orders");
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
        return $this->update($id, $data); 
    }

    public function deleteOrder($id) {
        return $this->delete($id); 
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
