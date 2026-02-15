<?php
session_start();
include "class/db.php";

$message = "";

if(isset($_POST['register'])) {

    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // รูปโปรไฟล์
    $profile_img = "default.png";

    // เชื่อมต่อฐานข้อมูล
    $db = new Database();
    $conn = $db->connect();

    // ===============================
    // ตรวจสอบ Email ซ้ำ
    // ===============================
    $check = $conn->prepare("SELECT * FROM users WHERE email=?");
    $check->execute([$email]);

    if($check->rowCount() > 0) {
        $message = "❌ Email นี้ถูกใช้งานแล้ว!";
    }
    else {

        // ===============================
        // Upload รูปโปรไฟล์ (ไม่เกิน 3MB)
        // ===============================
        if(!empty($_FILES['profile']['name'])) {

            $file_name = $_FILES['profile']['name'];
            $file_size = $_FILES['profile']['size'];
            $tmp_name  = $_FILES['profile']['tmp_name'];

            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed = ["jpg","jpeg","png"];

            if(!in_array($ext, $allowed)) {
                $message = "❌ รองรับเฉพาะไฟล์ JPG, JPEG, PNG เท่านั้น";
            }
            elseif($file_size > 3 * 1024 * 1024) {
                $message = "❌ ไฟล์รูปต้องไม่เกิน 3 MB";
            }
            else {
                // ตั้งชื่อไฟล์ใหม่กันซ้ำ
                $new_name = time() . "_" . rand(1000,9999) . "." . $ext;

                // ย้ายไฟล์เข้าโฟลเดอร์ prof_img/
                move_uploaded_file($tmp_name, "prof_img/" . $new_name);

                $profile_img = $new_name;
            }
        }

        // ===============================
        // บันทึกข้อมูลลงฐานข้อมูล
        // ===============================
        if($message == "") {

            $sql = "INSERT INTO users (fullname, email, password, role, profile_img)
                    VALUES (?, ?, ?, 'user', ?)";

            $stmt = $conn->prepare($sql);

            if($stmt->execute([$fullname, $email, $password, $profile_img])) {
                $message = "✅ สมัครสมาชิกสำเร็จ! ไปเข้าสู่ระบบได้เลย";
            }
            else {
                $message = "❌ สมัครสมาชิกไม่สำเร็จ";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Register | TMS System</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: #f8fff0;
            font-family: "TH Sarabun New", sans-serif;
        }

        h1,h2,h3,h4,h5,h6 {
            font-family: "Kanit", sans-serif;
        }

        .register-box {
            max-width: 520px;
            margin: auto;
            margin-top: 70px;
            background: white;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }

        .btn-theme {
            background: #28a745;
            color: white;
            border-radius: 10px;
        }

        .btn-theme:hover {
            background: #218838;
        }
    </style>
</head>

<body>

<div class="register-box">

    <h2 class="text-center mb-4 text-success">
        <i class="fa-solid fa-user-plus"></i> สมัครสมาชิกระบบ TMS
    </h2>

    <!-- แสดงข้อความ -->
    <?php if($message != "") { ?>
        <div class="alert alert-info text-center">
            <?= $message; ?>
        </div>
    <?php } ?>

    <!-- Form Register -->
    <form method="post" enctype="multipart/form-data">

        <div class="mb-3">
            <label class="form-label">ชื่อ-นามสกุล</label>
            <input type="text" name="fullname" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">อีเมล</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">รหัสผ่าน</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">รูปโปรไฟล์ (ไม่เกิน 3MB)</label>
            <input type="file" name="profile" class="form-control">
        </div>

        <button type="submit" name="register" class="btn btn-theme w-100">
            สมัครสมาชิก
        </button>

        <div class="text-center mt-3">
            มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a>
        </div>

    </form>

</div>

</body>
</html>
