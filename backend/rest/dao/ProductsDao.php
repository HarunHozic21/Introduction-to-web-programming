<?php

require_once "BaseDao.php";
class ProductDao extends BaseDao {
    public function __construct() {
        parent::__construct("products");
    }
 
    public function getAllProducts() {
        return $this->getAll();
    }
 
    public function getProductById($id) {
        return $this->getById($id); 
    }
 
    public function createProduct($data) {
        return $this->insert($data); 
    }
 
    public function updateProduct($id, $data) {
        return $this->update($id, $data); 
    }
 
    public function deleteProduct($id) {
        return $this->delete($id); 
    }

    public function getAvailableProducts() {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE stock > 0");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getProductsByBrand($brand) {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE brand = :brand");
        $stmt->bindParam(":brand", $brand);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function searchProducts($keyword) {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE name LIKE :keyword");
        $keyword = "%$keyword%";
        $stmt->bindParam(":keyword", $keyword);
        $stmt->execute();
        return $stmt->fetchAll();
    }   
 }

?>