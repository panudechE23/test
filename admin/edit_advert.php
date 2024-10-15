<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}
require "../db_connect.php";

// ตรวจสอบว่ามีการส่ง `id` มาหรือไม่
if (!isset($_GET['id'])) {
    header("Location: list_advert.php");
    exit;
}

$id_advert = $_GET['id'];

// ดึงข้อมูลแบนเนอร์ที่ต้องการแก้ไข
$sql = "SELECT * FROM advert WHERE id_advert = :id_advert";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_advert' => $id_advert]);
$advert = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$advert) {
    header("Location: list_advert.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>แก้ไขแบนเนอร์</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.3.0/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
    tinymce.init({
        selector: 'textarea#detail_advert',
        plugins: 'advlist anchor autolink autosave charmap code codesample directionality emoticons fullscreen help image imagetools insertdatetime link lists media nonbreaking pagebreak paste preview print save searchreplace spellchecker table template textcolor visualblocks visualchars wordcount',
        toolbar: 'undo redo | formatselect | link image | fontselect fontsizeselect | bold italic underline strikethrough | forecolor backcolor removeformat | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table emoticons charmap | preview fullscreen code',
        menubar: 'file edit view insert format tools table',
    });
    </script>
    <style>
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
        <h1>แก้ไขโฆษณา</h1>

        <!-- Card for advert Information -->
        <div class="card">
            <div class="card-body">
                <form action="edit_advert_update.php" method="post" enctype="multipart/form-data">
                    <!-- Hidden ID Field -->
                    <input type="hidden" name="id_advert" value="<?php echo htmlspecialchars($advert['id_advert']); ?>">

                    <!-- advert Title -->
                    <div class="form-group-edit">
                        <label for="name_advert">ชื่อโฆษณา</label>
                        <input type="text" class="form-edit" id="name_advert" name="name_advert"
                            value="<?php echo htmlspecialchars($advert['name_advert']); ?>" required>
                    </div>

                    <!-- advert Image -->
                    <div class="form-group-edit">
                        <label for="img_advert">รูปภาพโฆษณา</label>
                        <input type="file" class="form-edit" id="img_advert" name="img_advert">
                        <?php if ($advert['img_advert']): ?>
                        <img src="../images/advert/<?php echo htmlspecialchars($advert['id_advert']); ?>/<?php echo htmlspecialchars($advert['img_advert']); ?>"
                            alt="advert Image" width="200">
                        <?php endif; ?>
                    </div>

                    <!-- advert Image -->
                    <div class="form-group-edit">
                        <label for="img_banner_advert">รูปภาพ banner โฆษณา</label>
                        <input type="file" class="form-edit" id="img_banner_advert" name="img_banner_advert">
                        <?php if ($advert['img_advert']): ?>
                        <img src="../images/advert/<?php echo htmlspecialchars($advert['id_advert']); ?>/<?php echo htmlspecialchars($advert['img_banner_advert']); ?>"
                            alt="advert Image" width="200">
                        <?php endif; ?>
                    </div>

                    <!-- advert Description -->
                    <div class="form-group-edit">
                        <label for="detail_advert">รายละเอียดโฆษณา</label>
                        <textarea class="form-edit" id="detail" name="detail_advert"
                            rows="3"><?php echo htmlspecialchars($advert['detail_advert']); ?></textarea>
                    </div>
                    <!-- Additional Images -->
                    <div class="form-group-edit">
                        <label for="img_detail_advert">รูปภาพเพิ่มเติม</label>
                        <input type="file" class="form-edit" id="img_detail_advert" name="img_detail_advert[]" multiple>
                        <?php
                        $img_detail_advert = unserialize($advert['img_detail_advert']); // ใช้ unserialize เพื่อถอดรหัสข้อมูล
                        if (!empty($img_detail_advert) && is_array($img_detail_advert)): // ตรวจสอบว่าเป็น array ที่ถูกต้อง
                            echo '<div class="row">'; // เริ่มต้นการใช้ Bootstrap grid row
                            foreach ($img_detail_advert as $image):
                                if (!empty($image)): // ตรวจสอบว่ารูปภาพไม่ใช่ค่าว่าง
                                    echo '<div class="col-md-2 col-sm-3 mb-4">'; // ใช้ col สำหรับขนาดหน้าจอใหญ่และเล็ก
                                    echo '<img src="../images/advert/'. htmlspecialchars($advert['id_advert']) .'/'. htmlspecialchars($image).'" alt="Additional Image" class="img-fluid" style="width: 100%;">'; // ใช้ img-fluid เพื่อให้รูปภาพตอบสนองต่อขนาดหน้าจอ
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
                            <label for="start_date_advert">วันที่เริ่มแสดง</label>
                            <input type="date" class="form-edit" id="start_date_advert" name="start_date_advert"
                                value="<?php echo htmlspecialchars($advert['start_date_advert']); ?>" required>
                        </div>
                        <div class="col">
                            <label for="end_date_advert">วันที่สิ้นสุดการแสดง</label>
                            <input type="date" class="form-edit" id="end_date_advert" name="end_date_advert"
                                value="<?php echo htmlspecialchars($advert['end_date_advert']); ?>" required>
                        </div>
                    </div>


                    <div class="form-group-edit">
                        <label for="active_advert">สถานะการแสดงผล</label>
                        <div class="form-check">
                            <input type="hidden" name="active_advert" value="0">
                            <input class="form-check-input" type="checkbox" id="active_advert" name="active_advert"
                                value="1" <?php echo $advert['active_advert'] ? 'checked' : ''; ?>>

                            <label class="form-check-label" for="active_advert">แสดง</label>
                        </div>
                    </div>


                    <button type="submit" name="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                </form>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var startDateInput = document.getElementById('start_date_advert');
        var endDateInput = document.getElementById('end_date_advert');

        // ตั้งค่า min เริ่มต้นให้กับ end_date_advert เมื่อโหลดหน้าเว็บ
        if (startDateInput.value) {
            endDateInput.setAttribute('min', startDateInput.value);
        }

        // เมื่อผู้ใช้เปลี่ยนวันที่เริ่มแสดง
        startDateInput.addEventListener('change', function() {
            var startDate = startDateInput.value;
            endDateInput.setAttribute('min',
            startDate); // ตั้งค่า min ของ end_date_advert ตามวันที่เริ่ม
        });
    });
    </script>
</body>

</html>