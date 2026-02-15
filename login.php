<?php
session_start();

include "class/auth.php";

$error = "";

if(isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if(Auth::login($email, $password)) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "❌ อีเมลหรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Login | TMS System</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: "TH Sarabun New", sans-serif;
            background: #f8fff0;
        }

        h1,h2,h3,h4,h5,h6 {
            font-family: "Kanit", sans-serif;
        }

        .login-box {
            max-width: 450px;
            margin: auto;
            margin-top: 80px;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(to right, #28a745, #ffc107);
            color: white;
            text-align: center;
            padding: 25px;
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

<div class="login-box bg-white">

    <!-- Header -->
    <div class="login-header">
        <h2><i class="fa-solid fa-graduation-cap"></i> TMS Login</h2>
        <p class="mb-0">เข้าสู่ระบบบริหารจัดการอบรม</p>
    </div>

    <!-- Form -->
    <div class="p-4">

        <?php if($error!=""){ ?>
            <div class="alert alert-danger text-center">
                <?= $error; ?>
            </div>
        <?php } ?>

        <form method="post">

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">อีเมล</label>
                <input type="email" name="email" class="form-control"
                       placeholder="example@email.com" required>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">รหัสผ่าน</label>
                <input type="password" name="password" class="form-control"
                       placeholder="กรอกรหัสผ่าน" required>
            </div>

            <!-- Button -->
            <div class="d-grid">
                <button type="submit" name="login" class="btn btn-theme">
                    <i class="fa-solid fa-right-to-bracket"></i> เข้าสู่ระบบ
                </button>
            </div>

        </form>

        <hr>

        <p class="text-center">
            ยังไม่มีบัญชี?
            <a href="register.php" class="fw-bold text-success">สมัครสมาชิก</a>
        </p>

        <p class="text-center">
            <a href="index.php" class="text-muted">
                ⬅ กลับหน้าแรก
            </a>
        </p>

    </div>
</div>

</body>
</html>
