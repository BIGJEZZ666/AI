<?php
session_start();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Training Management System (TMS)</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600&display=swap" rel="stylesheet">

    <!-- Icon Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: "TH Sarabun New", sans-serif;
            background: #f8fff0;
        }

        h1,h2,h3,h4,h5,h6 {
            font-family: "Kanit", sans-serif;
        }

        .navbar {
            background: linear-gradient(to right, #28a745, #ffc107);
        }

        .hero {
            padding: 80px 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 600;
        }

        .hero p {
            font-size: 1.4rem;
            color: #444;
        }

        .card-role {
            border-radius: 20px;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .card-role:hover {
            transform: translateY(-8px);
        }

        .btn-theme {
            background: #28a745;
            color: white;
            border-radius: 10px;
        }

        .btn-theme:hover {
            background: #218838;
        }

        footer {
            margin-top: 60px;
            padding: 20px;
            text-align: center;
            background: #eee;
        }
    </style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="fa-solid fa-graduation-cap"></i> TMS System
        </a>

        <div class="ms-auto">
            <?php if(isset($_SESSION['user'])) { ?>
                <a href="dashboard.php" class="btn btn-light me-2">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
                <a href="logout.php" class="btn btn-danger">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            <?php } else { ?>
                <a href="login.php" class="btn btn-light me-2">
                    <i class="fa-solid fa-user"></i> Login
                </a>
                <a href="register.php" class="btn btn-dark">
                    <i class="fa-solid fa-user-plus"></i> Register
                </a>
            <?php } ?>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Training Management System (TMS)</h1>
        <p>
            ระบบบริหารจัดการอบรมออนไลน์แบบครบวงจร  
            สำหรับหน่วยงานราชการ สถานศึกษา และองค์กรฝึกอบรม
        </p>
    </div>
</section>

<!-- Role Cards -->
<div class="container">
    <div class="row g-4 text-center">

        <!-- Admin -->
        <div class="col-md-4">
            <div class="card card-role p-4">
                <h3 class="text-success">
                    <i class="fa-solid fa-user-shield"></i> Admin
                </h3>
                <p>
                    ผู้ดูแลระบบ สามารถจัดการหลักสูตร  
                    ตรวจสอบใบสมัคร และออกรายงานได้ทั้งหมด
                </p>
                <a href="login.php" class="btn btn-theme w-100">
                    เข้าสู่ระบบ Admin
                </a>
            </div>
        </div>

        <!-- Staff -->
        <div class="col-md-4">
            <div class="card card-role p-4">
                <h3 class="text-warning">
                    <i class="fa-solid fa-users-gear"></i> Staff
                </h3>
                <p>
                    เจ้าหน้าที่จัดการอบรม  
                    ตรวจสอบและอนุมัติใบสมัครผู้เข้าอบรม
                </p>
                <a href="login.php" class="btn btn-theme w-100">
                    เข้าสู่ระบบ Staff
                </a>
            </div>
        </div>

        <!-- User -->
        <div class="col-md-4">
            <div class="card card-role p-4">
                <h3 class="text-primary">
                    <i class="fa-solid fa-user-graduate"></i> User
                </h3>
                <p>
                    ผู้สมัครทั่วไป สามารถสมัครอบรม  
                    ตรวจสอบสถานะ และดาวน์โหลดเกียรติบัตรได้
                </p>
                <a href="register.php" class="btn btn-theme w-100">
                    สมัครเข้าอบรม
                </a>
            </div>
        </div>

    </div>
</div>

<!-- Footer -->
<footer>
    <p class="mb-0">
        © <?= date("Y"); ?> Training Management System (TMS) | Developed with ❤️ using PHP + Bootstrap
    </p>
</footer>

</body>
</html>
