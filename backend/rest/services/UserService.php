<?php
require_once __DIR__ . '/../dao/UserDao.php';

class UserService {
    private $dao;

    public function __construct() {
        $this->dao = new UserDao();
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function get($id) {
        return $this->dao->getById($id);
    }

    public function getByEmail($email) {
        return $this->dao->getByEmail($email);
    }

    public function getByRole($role) {
        return $this->dao->getByRole($role);
    }

    public function create($user) {
        if (!isset($user['email'], $user['password_hash'])) {
            throw new Exception("Missing required user fields");
        }
        return $this->dao->insert($user);
    }

    public function update($id, $data) {
        return $this->dao->update($id, $data);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }
}