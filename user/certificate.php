<?php
session_start();
include "../class/db.php";

/* ===============================
   ตรวจสอบ Login + Role user
================================ */
if(!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

if($_SESSION['user']['role'] != "user") {
    echo "<h3 class='text-center text-danger mt-5'>❌ หน้านี้สำหรับผู้ใช้ทั่วไปเท่านั้น</h3>";
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['id'];

$db = new Database();
$conn = $db->connect();

/* ===============================
   ตรวจสอบ app_id
================================ */
if(!isset($_GET['app_id'])) {
    echo "<h3 class='text-center text-danger mt-5'>❌ ไม่พบข้อมูลใบสมัคร</h3>";
    exit();
}

$app_id = $_GET['app_id'];

/* ===============================
   ตรวจสอบว่าใบสมัครเป็นของ user จริง
================================ */
$sql = "SELECT * FROM applications 
        WHERE id = ? AND user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$app_id, $user_id]);

$app = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$app) {
    echo "<h3 class='text-center text-danger mt-5'>❌ คุณไม่มีสิทธิ์เข้าถึงใบสมัครนี้</h3>";
    exit();
}

/* ===============================
   ตรวจสอบสถานะต้อง Verified
================================ */
if($app['status'] != "Verified") {
    echo "<h3 class='text-center text-warning mt-5'>⏳ ยังไม่สามารถดาวน์โหลดเกียรติบัตรได้ (ต้อง Verified ก่อน)</h3>";
    exit();
}

/* ===============================
   ตรวจสอบว่ามี certificate หรือไม่
================================ */
$sql2 = "SELECT * FROM certificates WHERE application_id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute([$app_id]);

$cert = $stmt2->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ดาวน์โหลดเกียรติบัตร | TMS</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
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

        .cert-card {
            border-radius: 25px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .btn-theme {
            background: #28a745;
            color: white;
            border-radius: 12px;
        }

        .btn-theme:hover {
            background: #218838;
        }
    </style>
</head>

<body>

<div class="container mt-5">

    <div class="cert-card bg-white text-center">

        <h2 class="text-success mb-3">
            <i class="fa-solid fa-award"></i> เกียรติบัตรของคุณ
        </h2>

        <p class="mb-4">
            คุณได้รับการยืนยันการเข้าอบรมเรียบร้อยแล้ว 🎉
        </p>

        <?php if($cert) { ?>

            <h4 class="text-primary mb-3">
                ไฟล์เกียรติบัตรพร้อมดาวน์โหลด
            </h4>

            <a href="../certificates/<?= $cert['cert_file']; ?>"
               class="btn btn-theme btn-lg w-75"
               download>
                <i class="fa-solid fa-download"></i> ดาวน์โหลด Certificate
            </a>

        <?php } else { ?>

            <div class="alert alert-warning">
                ⚠ ยังไม่มีไฟล์เกียรติบัตรในระบบ กรุณารอเจ้าหน้าที่ออกเอกสาร
            </div>

        <?php } ?>

        <div class="mt-4">
            <a href="my_status.php" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> กลับหน้าสถานะใบสมัคร
            </a>
        </div>

    </div>

</div>

</body>
</html>
