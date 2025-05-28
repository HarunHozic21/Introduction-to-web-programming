<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService extends BaseService {
    private $auth_dao;
    
    public function __construct() {
        $this->auth_dao = new AuthDao();
        parent::__construct(new AuthDao);
    }
    
    public function get_user_by_email($email){
        return $this->auth_dao->get_user_by_email($email);
    }
    
    public function register($entity) {  
        if (empty($entity['email'])) {
            return ['success' => false, 'error' => 'Email is required.'];
        }
        
        $email_exists = $this->auth_dao->get_user_by_email($entity['email']);
        if($email_exists){
            return ['success' => false, 'error' => 'Email already registered.'];
        }
        
        // Validate and set role
        $valid_roles = ['customer', 'admin'];
        $role = $entity['role'] ?? 'customer'; // Default to customer
        
        if (!in_array($role, $valid_roles)) {
            return ['success' => false, 'error' => 'Invalid role. Must be either "customer" or "admin".'];
        }
        
        // Prepare user data with required fields
        $user_data = [
            'first_name' => isset($entity['first_name']) ? $entity['first_name'] : 'User', // Required, default to 'User'
            'last_name' => isset($entity['last_name']) ? $entity['last_name'] : '', // Required
            'email' => $entity['email'],
            'password_hash' => password_hash($entity['password'], PASSWORD_BCRYPT),
            'phone' => isset($entity['phone']) ? $entity['phone'] : '',
            'address' => isset($entity['address']) ? $entity['address'] : '',
            'role' => $role,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $user = parent::add($user_data);
        return ['success' => true, 'data' => $user];            
    }
    
    public function login($entity) {  
        if (empty($entity['email'])) {
            return ['success' => false, 'error' => 'Email is required.'];
        }
        
        $user = $this->auth_dao->get_user_by_email($entity['email']);
        if(!$user){
            return ['success' => false, 'error' => 'Invalid username or password.'];
        }
        
        // Generate JWT token
        $jwt_payload = [
            'user' => [
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'role' => $user['role']
            ],
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24) // valid for day
        ];
        
        $token = JWT::encode(
            $jwt_payload,
            Config::JWT_SECRET(),
            'HS256'
        );
        
        return [
            'success' => true,
            'data' => [
                'user' => [
                    'email' => $user['email'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'role' => $user['role']
                ],
                'token' => $token
            ]
        ];
    }
}