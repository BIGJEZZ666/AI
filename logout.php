<?php
session_start();

// ล้างข้อมูล session ทั้งหมด
session_unset();
session_destroy();

// กลับไปหน้า Login
header("Location: login.php");
exit();
?>
