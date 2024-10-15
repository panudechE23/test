<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}
require "../db_connect.php";

// ตรวจสอบว่ามีการส่ง `id_banner` มาหรือไม่
if (!isset($_POST['id_banner'])) {
    header("Location: list_banner.php");
    exit;
}

$id_banner = $_POST['id_banner'];

// ดึงข้อมูลแบนเนอร์ที่ต้องการแก้ไข
$sql = "SELECT * FROM banner WHERE id_banner = :id_banner";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_banner' => $id_banner]);
$banner = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$banner) {
    header("Location: list_banner.php");
    exit;
}

$name_banner= $_POST['name_banner'];
$detail_banner = $_POST['detail_banner'];
$start_date = $_POST['start_date_banner'];
$end_date = $_POST['end_date_banner'];
$active_banner = $_POST['active_banner'] ; // ตรวจสอบค่า active_banner

// สร้างไดเรกทอรีสำหรับอัพโหลดถ้ายังไม่มี
$upload_dir = "../images/banner/" . $id_banner . "/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// อัพโหลดรูปภาพหลัก
$img_banner = $banner['img_banner']; // รักษาค่าเดิมหากไม่มีการอัพโหลดรูปใหม่
if (isset($_FILES['img_banner']) && $_FILES['img_banner']['error'] == UPLOAD_ERR_OK) {
    $img_banner = basename($_FILES['img_banner']['name']);
    $target_path = $upload_dir . $img_banner;
    if (!move_uploaded_file($_FILES['img_banner']['tmp_name'], $target_path)) {
        echo "เกิดข้อผิดพลาดในการอัปโหลดรูปภาพหลัก";
        exit;
    }
    // ลบภาพเก่าหากมี
    if ($banner['img_banner'] && file_exists($upload_dir . $banner['img_banner'])) {
        unlink($upload_dir . $banner['img_banner']);
    }
}

// จัดการกับรูปภาพรายละเอียด (หลายรูป)
$img_detail_banners = [];
$old_img_detail_banners = unserialize($banner['img_detail_banner']); // รูปภาพเก่า

if (isset($_FILES['img_detail_banner']) && !empty($_FILES['img_detail_banner']['name'][0])) {
    $file_count = count($_FILES['img_detail_banner']['name']);
    for ($i = 0; $i < $file_count; $i++) {
        if ($_FILES['img_detail_banner']['error'][$i] === UPLOAD_ERR_OK) {
            $file_name = basename($_FILES['img_detail_banner']['name'][$i]);
            $target_path = $upload_dir . $file_name;
            if (move_uploaded_file($_FILES['img_detail_banner']['tmp_name'][$i], $target_path)) {
                $img_detail_banners[] = $file_name; // เก็บชื่อไฟล์ใน array หากอัปโหลดสำเร็จ
            } else {
                echo "การย้ายไฟล์ล้มเหลว: " . $file_name;
                exit;
            }
        } else {
            echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์: " . $_FILES['img_detail_banner']['name'][$i];
            exit;
        }
    }
} else {
    $img_detail_banners = $old_img_detail_banners; // ใช้ข้อมูลเดิมหากไม่มีการอัปโหลดใหม่
}

// ลบรูปภาพเก่า
foreach ($old_img_detail_banners as $old_image) {
    $old_image_path = $upload_dir . $old_image;
    if (!in_array($old_image, $img_detail_banners) && file_exists($old_image_path)) {
        unlink($old_image_path);
    }
}

// แปลงที่อยู่ของรูปภาพเพิ่มเติมเป็นรูปแบบ serialize
$img_detail_banners_serialized = serialize($img_detail_banners);

// เตรียมคำสั่ง SQL สำหรับการอัพเดท
$sql_update = "UPDATE banner 
               SET img_banner = :img_banner, 
                   name_banner = :name_banner, 
                   detail_banner = :detail_banner, 
                   img_detail_banner = :img_detail_banner, 
                   start_date_banner = :start_date_banner, 
                   end_date_banner = :end_date_banner, 
                   active_banner = :active_banner 
               WHERE id_banner = :id_banner";

$stmt_update = $pdo->prepare($sql_update);
$stmt_update->execute([
    ':img_banner' => $img_banner,
    ':name_banner' => $name_banner,
    ':detail_banner' => $detail_banner,
    ':img_detail_banner' => $img_detail_banners_serialized,
    ':start_date_banner' => $start_date,
    ':end_date_banner' => $end_date,
    ':active_banner' => $active_banner,
    ':id_banner' => $id_banner
]);

header("Location: list_banner.php");
exit;
?>
