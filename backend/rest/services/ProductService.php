<?php
require_once __DIR__ . '/../dao/ProductDao.php';

class ProductService {
    private $dao;

    public function __construct() {
        $this->dao = new ProductDao();
    }

    public function getAllAvailable() {
        return $this->dao->getAvailableProducts();
    }

    public function getByBrand($brand) {
        return $this->dao->getProductsByBrand($brand);
    }

    public function search($keyword) {
        return $this->dao->searchProducts($keyword);
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function get($id) {
        return $this->dao->getById($id);
    }

    public function create($product) {
        if (!isset($product['name']) || !isset($product['brand']) || !isset($product['price'])) {
            throw new Exception("Missing required product fields");
        }

        return $this->dao->insert($product);
    }

    public function update($id, $data) {
        return $this->dao->update($id, $data);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }
}
