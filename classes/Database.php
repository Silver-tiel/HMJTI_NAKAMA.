<?php
class Database {
    private $host = "localhost";
    private $user = "u458429422_Nakama_HMJTI";
    private $pass = "5rVkO^W+Il4/";
    private $db   = "u458429422_Nakama_HMJTI";
    public $conn;
    public $pdo;

    public function __construct() {
        // MySQLi connection
        try {
            mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
        } catch (mysqli_sql_exception $e) {
            die("Koneksi MySQLi gagal: " . $e->getMessage());
        }

        // PDO connection
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->db;charset=utf8", $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Koneksi PDO gagal: " . $e->getMessage());
        }
    }
}
?>