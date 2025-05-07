<?php
require_once __DIR__ . '/../dao/OrderDao.php';

class OrderService {
    private $dao;

    public function __construct() {
        $this->dao = new OrderDao();
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function get($id) {
        return $this->dao->getById($id);
    }

    public function getByUser($userId) {
        return $this->dao->getOrdersByUserId($userId);
    }

    public function getByStatus($status) {
        return $this->dao->getOrdersByStatus($status);
    }

    public function create($order) {
        return $this->dao->insert($order);
    }

    public function update($id, $data) {
        return $this->dao->update($id, $data);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }
}
