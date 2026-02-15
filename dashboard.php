<?php
session_start();

// ตรวจสอบว่า Login หรือยัง
if(!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$role = $user['role'];

// ป้องกันกรณีไม่มีรูป
$img = $user['profile_img'] ?? "default.png";
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | TMS System</title>

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

        .navbar {
            background: linear-gradient(to right, #28a745, #ffc107);
        }

        .navbar img {
            background: white;
        }

        .card-dashboard {
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            transition: 0.3s;
            height: 100%;
        }

        .card-dashboard:hover {
            transform: translateY(-7px);
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

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">
            <i class="fa-solid fa-graduation-cap"></i> TMS Dashboard
        </a>

        <div class="ms-auto d-flex align-items-center text-white">

            <!-- Profile Image (Click เปิด Modal) -->
            <img src="prof_img/<?= $img; ?>"
                 alt="profile"
                 width="45"
                 height="45"
                 class="rounded-circle border border-2 border-light me-2"
                 style="object-fit:cover; cursor:pointer;"
                 data-bs-toggle="modal"
                 data-bs-target="#profileModal">

            <!-- User Name -->
            <span class="fw-bold">
                <?= $user['fullname']; ?>
                (<?= strtoupper($role); ?>)
            </span>

            <!-- Logout Button -->
            <a href="logout.php" class="btn btn-danger btn-sm ms-3">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>

        </div>
    </div>
</nav>


<!-- ================= CONTENT ================= -->
<div class="container mt-5">

    <h2 class="mb-4">
        สวัสดีคุณ <?= $user['fullname']; ?> 👋
    </h2>

    <div class="row g-4">

        <!-- ================= ADMIN DASHBOARD ================= -->
        <?php if($role == "admin") { ?>

            <div class="col-md-4">
                <div class="card-dashboard bg-white">
                    <h4 class="text-success">
                        <i class="fa-solid fa-book"></i> จัดการหลักสูตร
                    </h4>
                    <p>เพิ่ม แก้ไข ลบ หลักสูตรอบรมในระบบ</p>
                    <a href="admin/courses.php" class="btn btn-theme w-100">
                        ไปยังเมนูหลักสูตร
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-dashboard bg-white">
                    <h4 class="text-warning">
                        <i class="fa-solid fa-users"></i> ใบสมัครอบรม
                    </h4>
                    <p>ตรวจสอบและอัปเดตสถานะผู้สมัครทั้งหมด</p>
                    <a href="admin/applicants.php" class="btn btn-theme w-100">
                        จัดการใบสมัคร
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-dashboard bg-white">
                    <h4 class="text-primary">
                        <i class="fa-solid fa-chart-pie"></i> รายงานสรุป
                    </h4>
                    <p>ดูสถิติผู้สมัครและผลการอบรม</p>
                    <a href="admin/reports.php" class="btn btn-theme w-100">
                        ดูรายงาน
                    </a>
                </div>
            </div>

        <?php } ?>


        <!-- ================= STAFF DASHBOARD ================= -->
        <?php if($role == "staff") { ?>

            <div class="col-md-6">
                <div class="card-dashboard bg-white">
                    <h4 class="text-success">
                        <i class="fa-solid fa-book-open"></i> หลักสูตรอบรม
                    </h4>
                    <p>ดูและจัดการหลักสูตรที่เปิดอบรม</p>
                    <a href="staff/courses.php" class="btn btn-theme w-100">
                        จัดการหลักสูตร
                    </a>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card-dashboard bg-white">
                    <h4 class="text-warning">
                        <i class="fa-solid fa-user-check"></i> ตรวจสอบใบสมัคร
                    </h4>
                    <p>อนุมัติ / ปฏิเสธ / Verify ผู้สมัครอบรม</p>
                    <a href="staff/applicants.php" class="btn btn-theme w-100">
                        จัดการใบสมัคร
                    </a>
                </div>
            </div>

        <?php } ?>


        <!-- ================= USER DASHBOARD ================= -->
        <?php if($role == "user") { ?>

            <div class="col-md-4">
                <div class="card-dashboard bg-white">
                    <h4 class="text-success">
                        <i class="fa-solid fa-pen-to-square"></i> สมัครอบรม
                    </h4>
                    <p>เลือกหลักสูตรและสมัครเข้าอบรมออนไลน์</p>
                    <a href="user/apply.php" class="btn btn-theme w-100">
                        สมัครหลักสูตร
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-dashboard bg-white">
                    <h4 class="text-warning">
                        <i class="fa-solid fa-clock"></i> สถานะใบสมัคร
                    </h4>
                    <p>ตรวจสอบสถานะ Pending / Accepted / Verified</p>
                    <a href="user/my_status.php" class="btn btn-theme w-100">
                        ดูสถานะ
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-dashboard bg-white">
                    <h4 class="text-primary">
                        <i class="fa-solid fa-award"></i> เกียรติบัตร
                    </h4>
                    <p>ดาวน์โหลด Certificate เมื่อสถานะ Verified</p>
                    <a href="user/certificate.php" class="btn btn-theme w-100">
                        ดาวน์โหลด
                    </a>
                </div>
            </div>

        <?php } ?>

    </div>
</div>


<!-- ================= PROFILE MODAL ================= -->
<div class="modal fade" id="profileModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">

      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">
          <i class="fa-solid fa-user"></i> ข้อมูลโปรไฟล์
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">

        <!-- Big Profile Image -->
        <img src="prof_img/<?= $img; ?>"
             class="rounded-circle border border-3 border-success mb-3"
             width="120"
             height="120"
             style="object-fit:cover;">

        <h4 class="text-success fw-bold">
          <?= $user['fullname']; ?>
        </h4>

        <p class="mb-1">
          <i class="fa-solid fa-envelope"></i>
          <?= $user['email']; ?>
        </p>

        <p>
          <i class="fa-solid fa-id-badge"></i>
          Role:
          <span class="badge bg-warning text-dark">
            <?= strtoupper($role); ?>
          </span>
        </p>

        <hr>

        <a href="user/profile.php" class="btn btn-theme w-100">
          <i class="fa-solid fa-pen"></i> แก้ไขโปรไฟล์
        </a>

      </div>

    </div>
  </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
