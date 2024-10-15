<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}
require "../db_connect.php";

// ตรวจสอบว่ามีการส่ง `id_advert` มาหรือไม่
if (!isset($_POST['id_advert'])) {
    header("Location: list_advert.php");
    exit;
}

$id_advert = $_POST['id_advert'];

// ดึงข้อมูลแบนเนอร์ที่ต้องการแก้ไข
$sql = "SELECT * FROM advert WHERE id_advert = :id_advert";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_advert' => $id_advert]);
$advert = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$advert) {
    header("Location: list_advert.php");
    exit;
}

$name_advert= $_POST['name_advert'];
$detail_advert = $_POST['detail_advert'];
$start_date = $_POST['start_date_advert'];
$end_date = $_POST['end_date_advert'];
$active_advert = $_POST['active_advert'] ; // ตรวจสอบค่า active_advert

// สร้างไดเรกทอรีสำหรับอัพโหลดถ้ายังไม่มี
$upload_dir = "../images/advert/" . $id_advert . "/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// อัพโหลดรูปภาพหลัก
$img_advert = $advert['img_advert']; // รักษาค่าเดิมหากไม่มีการอัพโหลดรูปใหม่
if (isset($_FILES['img_advert']) && $_FILES['img_advert']['error'] == UPLOAD_ERR_OK) {
    $img_advert = basename($_FILES['img_advert']['name']);
    $target_path = $upload_dir . $img_advert;
    if (!move_uploaded_file($_FILES['img_advert']['tmp_name'], $target_path)) {
        echo "เกิดข้อผิดพลาดในการอัปโหลดรูปภาพหลัก";
        exit;
    }
    // ลบภาพเก่าหากมี
    if ($advert['img_advert'] && file_exists($upload_dir . $advert['img_advert'])) {
        unlink($upload_dir . $advert['img_advert']);
    }
}


// อัพโหลดรูปภาพหลัก banner
$img_banner_advert = $advert['img_banner_advert']; // รักษาค่าเดิมหากไม่มีการอัพโหลดรูปใหม่
if (isset($_FILES['img_banner_advert']) && $_FILES['img_banner_advert']['error'] == UPLOAD_ERR_OK) {
    $img_banner_advert = basename($_FILES['img_banner_advert']['name']);
    $target_path = $upload_dir . $img_banner_advert;
    if (!move_uploaded_file($_FILES['img_banner_advert']['tmp_name'], $target_path)) {
        echo "เกิดข้อผิดพลาดในการอัปโหลดรูปภาพหลัก";
        exit;
    }
    // ลบภาพเก่าหากมี
    if ($advert['img_banner_advert'] && file_exists($upload_dir . $advert['img_banner_advert'])) {
        unlink($upload_dir . $advert['img_banner_advert']);
    }
}


// จัดการกับรูปภาพรายละเอียด (หลายรูป)
$img_detail_adverts = [];
$old_img_detail_adverts = unserialize($advert['img_detail_advert']); // รูปภาพเก่า

if (isset($_FILES['img_detail_advert']) && !empty($_FILES['img_detail_advert']['name'][0])) {
    $file_count = count($_FILES['img_detail_advert']['name']);
    for ($i = 0; $i < $file_count; $i++) {
        if ($_FILES['img_detail_advert']['error'][$i] === UPLOAD_ERR_OK) {
            $file_name = basename($_FILES['img_detail_advert']['name'][$i]);
            $target_path = $upload_dir . $file_name;
            if (move_uploaded_file($_FILES['img_detail_advert']['tmp_name'][$i], $target_path)) {
                $img_detail_adverts[] = $file_name; // เก็บชื่อไฟล์ใน array หากอัปโหลดสำเร็จ
            } else {
                echo "การย้ายไฟล์ล้มเหลว: " . $file_name;
                exit;
            }
        } else {
            echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์: " . $_FILES['img_detail_advert']['name'][$i];
            exit;
        }
    }
} else {
    $img_detail_adverts = $old_img_detail_adverts; // ใช้ข้อมูลเดิมหากไม่มีการอัปโหลดใหม่
}

// ลบรูปภาพเก่า
foreach ($old_img_detail_adverts as $old_image) {
    $old_image_path = $upload_dir . $old_image;
    if (!in_array($old_image, $img_detail_adverts) && file_exists($old_image_path)) {
        unlink($old_image_path);
    }
}

// แปลงที่อยู่ของรูปภาพเพิ่มเติมเป็นรูปแบบ serialize
$img_detail_adverts_serialized = serialize($img_detail_adverts);

// เตรียมคำสั่ง SQL สำหรับการอัพเดท
$sql_update = "UPDATE advert 
               SET img_advert = :img_advert, 
               img_banner_advert = :img_banner_advert, 
                   name_advert = :name_advert, 
                   detail_advert = :detail_advert, 
                   img_detail_advert = :img_detail_advert, 
                   start_date_advert = :start_date_advert, 
                   end_date_advert = :end_date_advert, 
                   active_advert = :active_advert 
               WHERE id_advert = :id_advert";

$stmt_update = $pdo->prepare($sql_update);
$stmt_update->execute([
    ':img_advert' => $img_advert,
    ':img_banner_advert' => $img_banner_advert,
    ':name_advert' => $name_advert,
    ':detail_advert' => $detail_advert,
    ':img_detail_advert' => $img_detail_adverts_serialized,
    ':start_date_advert' => $start_date,
    ':end_date_advert' => $end_date,
    ':active_advert' => $active_advert,
    ':id_advert' => $id_advert
]);

header("Location: list_advert.php");
exit;
?>
