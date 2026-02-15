<?php
session_start();
include "../class/db.php";

$db = new Database();
$conn = $db->connect();

$search = "";

if(isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

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

/* ===============================
   แสดงผลเป็น Card HTML
================================ */
if(count($courses) > 0) {

    foreach($courses as $c) { ?>

        <div class="col-md-4">
            <div class="course-card bg-white">

                <h4 class="text-success fw-bold">
                    <?= $c['title']; ?>
                </h4>

                <p>
                    วันที่: <?= $c['start_date']; ?>
                </p>

                <p>
                    สถานที่: <?= $c['location']; ?>
                </p>

                <a href="apply.php?course_id=<?= $c['id']; ?>"
                   class="btn btn-theme w-100">
                    <i class="fa-solid fa-check"></i> สมัครอบรม
                </a>

            </div>
        </div>

<?php }

} else {

    echo "<h4 class='text-center text-danger'>❌ ไม่พบหลักสูตรที่ค้นหา</h4>";
}
?>
