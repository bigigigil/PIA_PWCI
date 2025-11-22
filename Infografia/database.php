<?php
class Database {
    private $host = 'localhost'; 
    private $db_name = 'infografia_pwci'; 
    private $username = 'root'; 
    private $password = 'young.KDAY6rati'; 
    private $conn;

  
    public function connect() {
        $this->conn = null;

        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;
        
        try {
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->conn->exec("SET NAMES utf8mb4");

        } catch(PDOException $exception) {
          
            error_log("Error de Conexión a la Base de Datos: " . $exception->getMessage());
           
            return null;
        }

        return $this->conn;
    }
}
?>