<?php
// classes/Database.php
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    
    private $conn;
    private $error;
    
    public function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        
        try {
            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo 'Connection Error: ' . $this->error;
        }
    }
    
    public function query($sql) {
        return $this->conn->prepare($sql);
    }
    
    public function execute($stmt, $params = []) {
        if(empty($params)) {
            return $stmt->execute();
        } else {
            return $stmt->execute($params);
        }
    }
    
    public function resultSet($stmt, $params = []) {
        $this->execute($stmt, $params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function single($stmt, $params = []) {
        $this->execute($stmt, $params);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    public function rowCount($stmt) {
        return $stmt->rowCount();
    }
    
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
}
?>