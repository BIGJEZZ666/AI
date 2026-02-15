<?php
session_start();
include "../class/db.php";

/* ===============================
   ตรวจสอบ Login
================================ */
if(!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['id'];

$db = new Database();
$conn = $db->connect();

$message = "";

/* ===============================
   ดึงข้อมูลล่าสุดจาก DB
================================ */
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$user_id]);
$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

$img = $currentUser['profile_img'] ?? "default.png";


/* ===============================
   Update Profile
================================ */
if(isset($_POST['update'])) {

    $fullname = trim($_POST['fullname']);
    $password = trim($_POST['password']);

    // รูปเดิม
    $profile_img = $img;

    /* ---------- Upload รูปใหม่ ---------- */
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
            $message = "❌ รูปต้องมีขนาดไม่เกิน 3MB";
        }
        else {
            $new_name = time()."_".rand(1000,9999).".".$ext;
            move_uploaded_file($tmp_name, "../prof_img/".$new_name);

            $profile_img = $new_name;
        }
    }

    /* ---------- Update DB ---------- */
    if($message == "") {

        $sql = "UPDATE users 
                SET fullname=?, password=?, profile_img=? 
                WHERE id=?";

        $stmt = $conn->prepare($sql);

        if($stmt->execute([$fullname, $password, $profile_img, $user_id])) {

            // Update Session ทันที
            $_SESSION['user']['fullname'] = $fullname;
            $_SESSION['user']['profile_img'] = $profile_img;

            $message = "✅ อัปเดตโปรไฟล์เรียบร้อยแล้ว!";
        }
        else {
            $message = "❌ เกิดข้อผิดพลาดในการอัปเดต";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>My Profile | TMS</title>

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

        .profile-box {
            max-width: 600px;
            margin: auto;
            margin-top: 60px;
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

<div class="profile-box">

    <h3 class="text-center text-success mb-4">
        <i class="fa-solid fa-user"></i> แก้ไขโปรไฟล์ของฉัน
    </h3>

    <!-- Message -->
    <?php if($message != "") { ?>
        <div class="alert alert-info text-center">
            <?= $message; ?>
        </div>
    <?php } ?>

    <!-- Profile Image -->
    <div class="text-center mb-3">
        <img src="../prof_img/<?= $img; ?>"
             width="130"
             height="130"
             class="rounded-circle border border-3 border-success"
             style="object-fit:cover;">
    </div>

    <!-- Form -->
    <form method="post" enctype="multipart/form-data">

        <div class="mb-3">
            <label class="form-label">ชื่อ-นามสกุล</label>
            <input type="text" name="fullname"
                   value="<?= $currentUser['fullname']; ?>"
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">อีเมล (แก้ไขไม่ได้)</label>
            <input type="email"
                   value="<?= $currentUser['email']; ?>"
                   class="form-control" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">รหัสผ่านใหม่</label>
            <input type="text" name="password"
                   value="<?= $currentUser['password']; ?>"
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">เปลี่ยนรูปโปรไฟล์ (ไม่เกิน 3MB)</label>
            <input type="file" name="profile" class="form-control">
        </div>

        <button type="submit" name="update" class="btn btn-theme w-100">
            <i class="fa-solid fa-save"></i> บันทึกข้อมูล
        </button>

        <a href="../dashboard.php" class="btn btn-secondary w-100 mt-2">
            <i class="fa-solid fa-arrow-left"></i> กลับ Dashboard
        </a>

    </form>

</div>

</body>
</html>
