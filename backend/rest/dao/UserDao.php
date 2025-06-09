<?php

require_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao {
   public function __construct() {
       parent::__construct("users");
   }

   // Override the base getById method to use user_id
   public function getById($id) {
       return $this->query_unique("SELECT * FROM users WHERE user_id = :id", ['id' => $id]);
   }

   public function getByEmail($email) {
       $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
       $stmt->bindParam(':email', $email);
       $stmt->execute();
       return $stmt->fetch();
   }

    public function getAllUsers() {
        return $this->getAll();
    }

    public function getUsersById($id) {
        return $this->getById($id); 
    }

    public function createUser($data) {
        return $this->insert($data); 
    }

    public function updateUser($id, $data) {
        return $this->update($data, $id, "user_id"); // Use user_id as the key column
    }

    public function deleteUser($id) {
        $stmt = $this->connection->prepare("DELETE FROM users WHERE user_id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public function getByRole($role) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE role = :role");
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        return $stmt->fetchAll(); // Changed from fetch() to fetchAll() to get all users with that role
    }
}
?>