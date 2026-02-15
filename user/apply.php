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

$message = "";

/* ===============================
   สมัครอบรม
================================ */
if(isset($_GET['course_id'])) {

    $course_id = $_GET['course_id'];

    // ตรวจสอบว่าสมัครซ้ำหรือยัง
    $check = $conn->prepare("SELECT * FROM applications 
                             WHERE user_id=? AND course_id=?");
    $check->execute([$user_id, $course_id]);

    if($check->rowCount() > 0) {
        $message = "❌ คุณสมัครหลักสูตรนี้ไปแล้ว!";
    }
    else {

        // สมัครใหม่ → Pending
        $sql = "INSERT INTO applications (user_id, course_id, status)
                VALUES (?, ?, 'Pending')";
        $stmt = $conn->prepare($sql);

        if($stmt->execute([$user_id, $course_id])) {
            $message = "✅ สมัครหลักสูตรเรียบร้อยแล้ว (รอการอนุมัติ)";
        }
        else {
            $message = "❌ เกิดข้อผิดพลาดในการสมัคร";
        }
    }
}

/* ===============================
   ค้นหาหลักสูตร
================================ */
$search = "";

if(isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

/* ===============================
   ดึงรายการหลักสูตรทั้งหมด + Search
================================ */
if($search != "") {

    $stmt = $conn->prepare("SELECT * FROM courses 
                        WHERE title LIKE ?
                        ORDER BY id DESC");

$stmt->execute(["%$search%"]);


} else {

    $stmt = $conn->prepare("SELECT * FROM courses ORDER BY id DESC");
    $stmt->execute();
}

$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สมัครอบรม | TMS</title>

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

        .course-card {
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            transition: 0.3s;
            height: 100%;
        }

        .course-card:hover {
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

<div class="container mt-5">

    <h2 class="text-success mb-3">
        <i class="fa-solid fa-pen-to-square"></i> สมัครหลักสูตรอบรม
    </h2>

    <!-- Search Box -->
<div class="mb-4">
    <input type="text"
           id="searchBox"
           class="form-control"
           placeholder="🔍 พิมพ์เพื่อค้นหาหลักสูตรอบรม...">
</div>


    <!-- Message -->
    <?php if($message != "") { ?>
        <div class="alert alert-info text-center">
            <?= $message; ?>
        </div>
    <?php } ?>

    <div class="row g-4" id="courseList">

        <?php if(count($courses) > 0) { ?>
            <?php foreach($courses as $c) { ?>

                <div class="col-md-4">
                    <div class="course-card bg-white">

                        <h4 class="text-success fw-bold">
                            <?= $c['title']; ?>
                        </h4>

                        <p><?= $c['detail']; ?></p>

                        <p>
                            <i class="fa-solid fa-calendar-days text-warning"></i>
                            วันที่: <?= $c['start_date']; ?>
                        </p>

                        <p>
                            <i class="fa-solid fa-location-dot text-danger"></i>
                            สถานที่: <?= $c['location']; ?>
                        </p>

                        <a href="apply.php?course_id=<?= $c['id']; ?>"
                           class="btn btn-theme w-100">
                            <i class="fa-solid fa-check"></i> สมัครอบรม
                        </a>

                    </div>
                </div>

            <?php } ?>
        <?php } else { ?>

            <h4 class="text-center text-danger">
                ❌ ไม่พบหลักสูตรที่ค้นหา
            </h4>

        <?php } ?>

    </div>

    <!-- Back -->
    <div class="text-center mt-4">
        <a href="../dashboard.php" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> กลับ Dashboard
        </a>
    </div>

</div>
<script>
document.getElementById("searchBox").addEventListener("keyup", function() {

    let keyword = this.value;

    fetch("search_courses.php?search=" + keyword)
        .then(response => response.text())
        .then(data => {
            document.getElementById("courseList").innerHTML = data;
        });

});
</script>

</body>
</html>
