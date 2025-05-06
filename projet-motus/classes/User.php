<?php

class User {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function register($username, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->db->query($query);
        
        if($this->db->execute($stmt, [
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashed_password
        ])) {
            return true;
        } else {
            return false;
        }
    }
    
    public function login($username, $password) {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->query($query);
        $this->db->execute($stmt, [':username' => $username]);
        
        $row = $this->db->single($stmt);
        
        if($row) {
            $hashed_password = $row->password;
            
            if(password_verify($password, $hashed_password)) {
                return $row;
            }
        }
        
        return false;
    }
    
    public function findUserByUsername($username) {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->query($query);
        
        // Passez les paramètres directement à la méthode single()
        return $this->db->single($stmt, [':username' => $username]);
        
    }
    
    public function findUserByEmail($email) {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->query($query);
        $this->db->execute($stmt, [':email' => $email]);
        
        return $this->db->single($stmt);
    }
    
    public function getUserById($id) {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->query($query);
        $this->db->execute($stmt, [':id' => $id]);
        
        return $this->db->single($stmt);
    }
}
?>