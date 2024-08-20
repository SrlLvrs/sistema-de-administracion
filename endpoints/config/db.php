<?php
class Database {
    private $host = "localhost";
    private $db_name = "nuestrocampo_vue";
    private $username = "nuestrocampo_vue";
    private $password = "LetSRKz4vxY59Ya";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
