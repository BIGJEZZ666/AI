<?php
session_start();
include "../class/db.php";

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
   ดึงข้อมูลใบสมัครของ user
================================ */
$sql = "SELECT a.*, c.title
        FROM applications a
        JOIN courses c ON a.course_id = c.id
        WHERE a.user_id = ?
        ORDER BY a.id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);

$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สถานะใบสมัคร | TMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600&display=swap" rel="stylesheet">
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
        .card-status {
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            transition: 0.3s;
        }
        .card-status:hover {
            transform: translateY(-5px);
        }
        .status-badge {
            font-size: 16px;
            padding: 8px 15px;
            border-radius: 15px;
        }
    </style>
</head>

<body>

<div class="container mt-5">

    <h2 class="text-success mb-4">
        <i class="fa-solid fa-clock"></i> สถานะใบสมัครของฉัน
    </h2>

    <?php if(count($applications) > 0) { ?>

        <div class="row g-4">

            <?php foreach($applications as $app) { ?>

                <div class="col-md-6">
                    <div class="card-status bg-white">

                        <h4 class="fw-bold text-success">
                            <?= $app['title']; ?>
                        </h4>

                        <p>
                            สถานะ:
                            <?php
                                $status = $app['status'];

                                if($status == "Pending")
                                    echo "<span class='badge bg-secondary status-badge'>Pending</span>";
                                elseif($status == "Accepted")
                                    echo "<span class='badge bg-primary status-badge'>Accepted</span>";
                                elseif($status == "Rejected")
                                    echo "<span class='badge bg-danger status-badge'>Rejected</span>";
                                elseif($status == "Cancelled")
                                    echo "<span class='badge bg-warning text-dark status-badge'>Cancelled</span>";
                                elseif($status == "Verified")
                                    echo "<span class='badge bg-success status-badge'>Verified</span>";
                            ?>
                        </p>

                        <?php if($status == "Verified") { ?>
                            <a href="certificate.php?app_id=<?= $app['id']; ?>"
                               class="btn btn-success w-100 mt-2">
                                <i class="fa-solid fa-award"></i> ดาวน์โหลดเกียรติบัตร
                            </a>
                        <?php } ?>

                    </div>
                </div>

            <?php } ?>

        </div>

    <?php } else { ?>

        <div class="alert alert-danger text-center">
            ❌ คุณยังไม่ได้สมัครหลักสูตรอบรมใด ๆ
        </div>

    <?php } ?>

    <div class="text-center mt-4">
        <a href="../dashboard.php" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> กลับ Dashboard
        </a>
    </div>

</div>

</body>
</html>
