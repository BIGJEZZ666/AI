<?php
session_start();
include "db.php";

class Auth {

    /* ==========================
       ฟังก์ชัน Login (ไม่ Hash)
    ========================== */
    public static function login($email, $password) {

        $db = new Database();
        $conn = $db->connect();

        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email, $password]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // ถ้าเจอ user → Login สำเร็จ
        if($user) {

            $_SESSION['user'] = [
                "id" => $user['id'],
                "fullname" => $user['fullname'],
                "email" => $user['email'],
                "role" => $user['role'],
                "profile_img" => $user['profile_img']
            ];

            return true;
        }

        return false;
    }


    /* ==========================
       ตรวจสอบว่า Login หรือยัง
    ========================== */
    public static function check() {
        return isset($_SESSION['user']);
    }


    /* ==========================
       ดึงข้อมูลผู้ใช้ปัจจุบัน
    ========================== */
    public static function user() {
        return $_SESSION['user'] ?? null;
    }


    /* ==========================
       ตรวจสอบ Role
    ========================== */
    public static function role() {
        return $_SESSION['user']['role'] ?? null;
    }


    /* ==========================
       จำกัดสิทธิ์การเข้าถึงหน้า
       เช่น Auth::only("admin")
    ========================== */
    public static function only($role) {

        if(!self::check()) {
            header("Location: ../login.php");
            exit();
        }

        if(self::role() != $role) {
            echo "<h3 style='color:red; text-align:center; margin-top:50px;'>
                    ❌ คุณไม่มีสิทธิ์เข้าหน้านี้
                  </h3>";
            exit();
        }
    }


    /* ==========================
       Logout
    ========================== */
    public static function logout() {
        session_destroy();
        header("Location: login.php");
        exit();
    }
}
?>
