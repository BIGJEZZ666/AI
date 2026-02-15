<?php
class Database {

    private $host = "localhost";
    private $db_name = "tms_db";
    private $username = "root";
    private $password = "12345678";

    public $conn;

    /* ==========================
       ฟังก์ชันเชื่อมต่อฐานข้อมูล
    ========================== */
    public function connect() {

        $this->conn = null;

        try {

            $this->conn = new PDO(
                "mysql:host=".$this->host.";
                 dbname=".$this->db_name.";
                 charset=utf8mb4",

                $this->username,
                $this->password
            );

            // ตั้งค่าให้ PDO แสดง Error
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e) {

            echo "<h3 style='color:red;'>Database Connection Failed: ".$e->getMessage()."</h3>";

        }

        return $this->conn;
    }
}
?>
