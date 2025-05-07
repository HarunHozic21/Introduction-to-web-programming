<?php
require_once __DIR__ . '/../dao/PaymentDao.php';

class PaymentService {
    private $dao;

    public function __construct() {
        $this->dao = new PaymentsDao();
    }

    public function getByOrder($orderId) {
        return $this->dao->getPaymentByOrderId($orderId);
    }

    public function getByStatus($status) {
        return $this->dao->getPaymentsByStatus($status);
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function get($id) {
        return $this->dao->getById($id);
    }

    public function create($payment) {
        return $this->dao->insert($payment);
    }

    public function update($id, $data) {
        return $this->dao->update($id, $data);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }
}
