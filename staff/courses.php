<?php
session_start();
include "../class/db.php";

/* ===============================
   ตรวจสอบ Login + Role staff
================================ */
if(!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

if($_SESSION['user']['role'] != "staff") {
    echo "<h3 class='text-center text-danger mt-5'>❌ หน้านี้สำหรับเจ้าหน้าที่เท่านั้น</h3>";
    exit();
}

$db = new Database();
$conn = $db->connect();

$message = "";

/* ===============================
   เพิ่มหลักสูตรใหม่
================================ */
if(isset($_POST['add_course'])) {

    $title      = trim($_POST['title']);
    $detail     = trim($_POST['detail']);
    $start_date = $_POST['start_date'];
    $end_date   = $_POST['end_date'];

    if($title == "" || $detail == "" || $start_date == "" || $end_date == "") {
        $message = "❌ กรุณากรอกข้อมูลให้ครบ";
    }
    else {

        $sql = "INSERT INTO courses (title, detail, start_date, end_date, created_at)
                VALUES (?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);

        if($stmt->execute([$title, $detail, $start_date, $end_date])) {
            $message = "✅ เพิ่มหลักสูตรเรียบร้อยแล้ว";
        }
        else {
            $message = "❌ เพิ่มหลักสูตรไม่สำเร็จ";
        }
    }
}

/* ===============================
   ลบหลักสูตร
================================ */
if(isset($_GET['delete'])) {

    $course_id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM courses WHERE id=?");

    if($stmt->execute([$course_id])) {
        $message = "✅ ลบหลักสูตรเรียบร้อยแล้ว";
    }
    else {
        $message = "❌ ไม่สามารถลบหลักสูตรได้";
    }
}

/* ===============================
   ดึงหลักสูตรทั้งหมด
================================ */
$stmt = $conn->prepare("SELECT * FROM courses ORDER BY id DESC");
$stmt->execute();

$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการหลักสูตร | Staff</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font -->
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

        .card-box {
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            transition: 0.3s;
        }

        .card-box:hover {
            transform: translateY(-5px);
        }

        .btn-theme {
            background: #198754;
            color: white;
            border-radius: 10px;
        }

        .btn-theme:hover {
            background: #146c43;
        }
    </style>
</head>

<body>

<div class="container mt-5">

    <h2 class="text-success mb-4">
        <i class="fa-solid fa-book"></i> จัดการหลักสูตรอบรม (Staff)
    </h2>

    <!-- Message -->
    <?php if($message != "") { ?>
        <div class="alert alert-info text-center">
            <?= $message; ?>
        </div>
    <?php } ?>

    <!-- Add Course Form -->
    <div class="card-box bg-white mb-4">

        <h4 class="text-success">
            <i class="fa-solid fa-plus"></i> เพิ่มหลักสูตรใหม่
        </h4>

        <form method="post" class="row g-3 mt-3">

            <div class="col-md-6">
                <label class="form-label">ชื่อหลักสูตร</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">รายละเอียดหลักสูตร</label>
                <input type="text" name="detail" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">วันที่เริ่มอบรม</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">วันที่สิ้นสุดอบรม</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>

            <div class="col-12 text-center mt-3">
                <button type="submit" name="add_course" class="btn btn-theme w-50">
                    <i class="fa-solid fa-circle-check"></i> บันทึกหลักสูตร
                </button>
            </div>

        </form>

    </div>

    <!-- Courses List -->
    <div class="row g-4">

        <?php if(count($courses) > 0) { ?>
            <?php foreach($courses as $c) { ?>

                <div class="col-md-4">
                    <div class="card-box bg-white">

                        <h5 class="fw-bold text-success">
                            <?= $c['title']; ?>
                        </h5>

                        <p><?= $c['detail']; ?></p>

                        <p>
                            <i class="fa-solid fa-calendar-days text-warning"></i>
                            เริ่ม: <?= $c['start_date']; ?>
                        </p>

                        <p>
                            <i class="fa-solid fa-calendar-check text-primary"></i>
                            สิ้นสุด: <?= $c['end_date']; ?>
                        </p>

                        <p class="text-muted">
                            <i class="fa-solid fa-clock"></i>
                            เพิ่มเมื่อ: <?= $c['created_at']; ?>
                        </p>

                        <a href="courses.php?delete=<?= $c['id']; ?>"
                           onclick="return confirm('ยืนยันการลบหลักสูตรนี้?')"
                           class="btn btn-danger w-100">
                            <i class="fa-solid fa-trash"></i> ลบหลักสูตร
                        </a>

                    </div>
                </div>

            <?php } ?>
        <?php } else { ?>

            <h4 class="text-center text-danger">
                ❌ ยังไม่มีหลักสูตรอบรมในระบบ
            </h4>

        <?php } ?>

    </div>

    <!-- Back -->
    <div class="text-center mt-5">
        <a href="../dashboard.php" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> กลับ Dashboard
        </a>
    </div>

</div>

</body>
</html>
