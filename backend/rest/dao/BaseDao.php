<?php
require_once __DIR__ . "/../config.php";

class BaseDao{
    protected $connection;
    protected $table_name;

    public function __construct($table_name){
        $this->table_name=$table_name;
        try{
            $this->connection = new PDO("mysql:host=" . Config::DB_HOST() . ";dbname=" . Config::DB_NAME() . 
            ";charset=utf8;port=" . Config::DB_PORT(), Config::DB_USER(), Config::DB_PASSWORD(), [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch(PDOException $e){
            print_r($e);
            throw $e;
        }
    }
    
    protected function query($query, $params){
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    protected function query_unique($query, $params){
        $results = $this->query($query, $params);
        return reset($results);
    }

    //Method for adding an entry to a database table
    public function insert($entity){
        $query = "INSERT INTO " . $this->table_name . " (";
        foreach($entity as $column => $value){
            $query .= $column . ', ';
        }
        $query = substr($query, 0, -2);
        $query .= ") VALUES (";
        foreach($entity as $column => $value){
            $query .= ":" . $column . ', ';
        }
        $query = substr($query, 0, -2); 
        $query .= ")";
        $stmt = $this->connection->prepare($query);
        $stmt->execute($entity);
        
        // Get the last inserted ID and add it to the entity with the appropriate key name
        $lastInsertId = $this->connection->lastInsertId();
        if ($lastInsertId) {
            // Determine the primary key name based on table name
            $primaryKey = $this->getPrimaryKeyName();
            $entity[$primaryKey] = $lastInsertId;
        }
        
        return $entity;
    }
    
    // Helper method to determine primary key name based on table name
    protected function getPrimaryKeyName() {
        switch($this->table_name) {
            case 'products':
                return 'product_id';
            case 'users':
                return 'user_id';
            case 'orders':
                return 'order_id';
            default:
                return 'id'; // fallback for tables that use 'id'
        }
    }
    
    public function update($entity, $id, $id_column = "id"){
        $query = "UPDATE " . $this->table_name . " SET ";
        foreach($entity as $column => $value){
            $query .= $column . "=:" . $column . ", ";
        }
        $query = substr($query, 0, -2);
        $query .= " WHERE " . $id_column . " = :id";
        $stmt = $this->connection->prepare($query);
        $entity['id'] = $id;
        $stmt->execute($entity);
        return $entity;
    }

    public function delete($id){
        $primaryKey = $this->getPrimaryKeyName();
        $stmt = $this->connection->prepare("DELETE FROM " . $this->table_name . " WHERE " . $primaryKey . " = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    //Method for getting all entries (no filter criteria) from a database table
    public function getAll(){
        return $this->query("SELECT * FROM " . $this->table_name, []);
    }

    //Method for getting entity by id from a database table
    public function getById($id){
        $primaryKey = $this->getPrimaryKeyName();
        return $this->query_unique("SELECT * FROM " . $this->table_name . " WHERE " . $primaryKey . "=:id", ['id' => $id]);
    }
}