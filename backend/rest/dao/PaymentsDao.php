<?php
require_once 'BaseDao.php';

class PaymentsDao extends BaseDao {
    public function __construct() {
        parent::__construct("payments");
    }

    public function getPaymentById($id) {
        return $this->getById($id);
    }

    public function getPaymentsByUserId($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM payments WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createPayment($data) {
        return $this->insert($data);
    }

    public function updatePayment($id, $data) {
        return $this->update($id, $data);
    }

    public function deletePayment($id) {
        return $this->delete($id);
    }

    public function getPaymentByOrderId($order_id) {
        $stmt = $this->connection->prepare("SELECT * FROM payments WHERE order_id = :order_id");
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getPaymentsByStatus($status) {
        $stmt = $this->connection->prepare("SELECT * FROM payments WHERE payment_status = :status");
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
