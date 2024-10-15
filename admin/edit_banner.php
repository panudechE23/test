<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}
require "../db_connect.php";

// ตรวจสอบว่ามีการส่ง `id` มาหรือไม่
if (!isset($_GET['id'])) {
    header("Location: list_banner.php");
    exit;
}

$id_banner = $_GET['id'];

// ดึงข้อมูลแบนเนอร์ที่ต้องการแก้ไข
$sql = "SELECT * FROM banner WHERE id_banner = :id_banner";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_banner' => $id_banner]);
$banner = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$banner) {
    header("Location: list_banner.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>แก้ไขแบนเนอร์</title>

    <style>
    .tox-promotion-link {
        display: none !important;
    }

    .container {
        max-width: 800px;
        margin-top: 50px;
    }

    .card {
        margin-bottom: 20px;
    }

    .form-group-edit i {
        color: red;
    }

    .form-edit {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
    }

    label {
        font-weight: bold;
    }
    </style>
</head>

<body>
    <div class="header_section header_bg">
        <div class="container-fluid">
            <?php include 'sidebar.php'; ?>
        </div>
    </div>
    <div class="container">
        <h1>แก้ไขแบนเนอร์</h1>

        <!-- Card for Banner Information -->
        <div class="card">
            <div class="card-body">
                <form action="edit_banner_update.php" method="post" enctype="multipart/form-data">
                    <!-- Hidden ID Field -->
                    <input type="hidden" name="id_banner" value="<?php echo htmlspecialchars($banner['id_banner']); ?>">

                    <!-- Banner Title -->
                    <div class="form-group-edit">
                        <label for="name_banner">ชื่อแบนเนอร์</label>
                        <input type="text" class="form-edit" id="name_banner" name="name_banner" value="<?php echo htmlspecialchars($banner['name_banner']); ?>" required>
                    </div>

                    <!-- Banner Image -->
                    <div class="form-group-edit">
                        <label for="img_banner">รูปภาพแบนเนอร์</label>
                        <input type="file" class="form-edit" id="img_banner" name="img_banner">
                        <?php if ($banner['img_banner']): ?>
                        <img src="../images/banner/<?php echo htmlspecialchars($banner['id_banner']); ?>/<?php echo htmlspecialchars($banner['img_banner']); ?>" alt="Banner Image" width="200">
                        <?php endif; ?>
                    </div>

                    <!-- Banner Description -->
                    <div class="form-group-edit">
                        <label for="detail_banner">รายละเอียดแบนเนอร์</label>
                        <textarea class="form-edit" id="detail" name="detail_banner" rows="3"><?php echo htmlspecialchars($banner['detail_banner']); ?></textarea>
                    </div>
                    <!-- Additional Images -->
                    <div class="form-group-edit">
                        <label for="img_detail_banner">รูปภาพเพิ่มเติม</label>
                        <input type="file" class="form-edit" id="img_detail_banner" name="img_detail_banner[]" multiple>
                        <?php
                        $img_detail_banner = unserialize($banner['img_detail_banner']); // ใช้ unserialize เพื่อถอดรหัสข้อมูล
                        if (!empty($img_detail_banner) && is_array($img_detail_banner)): // ตรวจสอบว่าเป็น array ที่ถูกต้อง
                            echo '<div class="row">'; // เริ่มต้นการใช้ Bootstrap grid row
                            foreach ($img_detail_banner as $image):
                                if (!empty($image)): // ตรวจสอบว่ารูปภาพไม่ใช่ค่าว่าง
                                    echo '<div class="col-md-2 col-sm-3 mb-4">'; // ใช้ col สำหรับขนาดหน้าจอใหญ่และเล็ก
                                    echo '<img src="../images/banner/'. htmlspecialchars($banner['id_banner']) .'/'. htmlspecialchars($image).'" alt="Additional Image" class="img-fluid" style="width: 100%;">'; // ใช้ img-fluid เพื่อให้รูปภาพตอบสนองต่อขนาดหน้าจอ
                                    echo '</div>'; // ปิด col
                                endif;
                            endforeach;
                            echo '</div>'; // ปิด row
                        endif;
                        ?>
                    </div>
                    <!-- Display Dates -->
                    <div class="row">
                        <div class="col">
                            <label for="start_date_banner">วันที่เริ่มแสดง</label>
                            <input type="date" class="form-edit" id="start_date_banner" name="start_date_banner" value="<?php echo htmlspecialchars($banner['start_date_banner']); ?>" required>
                        </div>
                        <div class="col">
                            <label for="end_date_banner">วันที่สิ้นสุดการแสดง</label>
                            <input type="date" class="form-edit" id="end_date_banner" name="end_date_banner" value="<?php echo htmlspecialchars($banner['end_date_banner']); ?>" required>
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div class="form-group-edit">
                        <label for="active_banner">สถานะการแสดงผล</label>
                        <div class="form-check">
                            <input type="hidden" name="active_banner" value="0">
                            <input class="form-check-input" type="checkbox" name="active_banner" id="activbe_banner"
                            value="1" <?php echo $banner['active_banner']?'checked':'' ;?>>
                            <label class="form-check-label" for="active_banner">แสดง</label>
                        </div>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                </form>
            </div>
        </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    var startDateInput = document.getElementById('start_date_banner');
    var endDateInput = document.getElementById('end_date_banner');

    // ตั้งค่า min เริ่มต้นให้กับ end_date_banner เมื่อโหลดหน้าเว็บ
    if (startDateInput.value) {
        endDateInput.setAttribute('min', startDateInput.value);
    }

    // เมื่อผู้ใช้เปลี่ยนวันที่เริ่มแสดง
    startDateInput.addEventListener('change', function() {
        var startDate = startDateInput.value;
        endDateInput.setAttribute('min', startDate); // ตั้งค่า min ของ end_date_banner ตามวันที่เริ่ม
    });
});


</script>
</body>

</html>
