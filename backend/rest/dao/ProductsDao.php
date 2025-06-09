<?php

require_once __DIR__ . '/BaseDao.php';

class ProductsDao extends BaseDao {
    public function __construct() {
        parent::__construct("products");
    }
 
    public function getAllProducts() {
        return $this->getAll();
    }
 
    public function getProductById($id) {
        return $this->query_unique("SELECT * FROM products WHERE product_id = :id", ['id' => $id]);
    }
 
    public function createProduct($data) {
        return $this->insert($data); 
    }
 
    public function updateProduct($id, $data) {
        return $this->update($data, $id, "product_id"); // Use product_id as the key column
    }
 
    public function deleteProduct($id) {
        $stmt = $this->connection->prepare("DELETE FROM products WHERE product_id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    // Override the base getById method to use product_id
    public function getById($id) {
        return $this->query_unique("SELECT * FROM products WHERE product_id = :id", ['id' => $id]);
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