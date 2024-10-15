<?php
require "../db_connect.php"; // ตรวจสอบให้แน่ใจว่า path ถูกต้อง

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name_type'])) {
    $name_type = $_POST['name_type'];

    // ตรวจสอบว่ามีประเภทสินค้านี้ในฐานข้อมูลหรือไม่
    $sql = "SELECT * FROM type WHERE name_type = :name_type";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':name_type' => $name_type]);

    if ($stmt->rowCount() == 0) {
        // เพิ่มประเภทสินค้าใหม่
        $sql = "INSERT INTO type (name_type) VALUES (:name_type)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':name_type' => $name_type]);

        // ส่งผลลัพธ์กลับไปให้ AJAX
        echo 'success';
    } else {
        // ถ้ามีประเภทนี้อยู่แล้ว
        echo 'exists';
    }
} else {
    echo 'error';
}
