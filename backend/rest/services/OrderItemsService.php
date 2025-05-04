<?php
require_once __DIR__ . '/../dao/OrderItemsDao.php';

class OrderItemService {
    private $dao;

    public function __construct() {
        $this->dao = new OrderItemDao();
    }

    public function getByOrder($orderId) {
        return $this->dao->getItemsByOrderId($orderId);
    }

    public function getTotalItems($orderId) {
        return $this->dao->getTotalItemsForOrder($orderId);
    }

    public function deleteByOrder($orderId) {
        return $this->dao->deleteItemsByOrderId($orderId);
    }

    public function addItem($item) {
        return $this->dao->insert($item);
    }
}