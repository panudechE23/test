<?php
require("../db_connect.php"); // เชื่อมต่อฐานข้อมูล

if (isset($_POST['submit'])) {
    // รับข้อมูลจากฟอร์ม
    $name_product = $_POST['name_product'];
    $type_product = $_POST['type_product'];
    $dtaill_product = $_POST['dtaill_product'];
    $date_product = $_POST['date_product'];
    $dtaill_vdo_product = $_POST['dtaill_vdo_product'];

    // ลบ https://www.youtube.com/watch/?v= และ https://youtu.be/ ออกจากลิงก์
    $dtaill_vdo_product = preg_replace('/^https:\/\/(www\.youtube\.com\/(watch\?v=|shorts\/)|youtu\.be\/)/', '', $dtaill_vdo_product);

    // จัดการกับรูปภาพสินค้า
    $img_product = '';
    if (isset($_FILES['img_product']) && $_FILES['img_product']['error'] == UPLOAD_ERR_OK) {
        $img_product = $_FILES['img_product']['name'];
        $target_path = '../images/' . $img_product;
        if (!move_uploaded_file($_FILES['img_product']['tmp_name'], $target_path)) {
            echo "เกิดข้อผิดพลาดในการอัปโหลดรูปภาพสินค้า";
            exit;
        }
    }

    // จัดการกับรูปภาพรายละเอียด (หลายรูป)
    $dtaill_img_products = [];
    if (isset($_FILES['dtaill_img_product'])) {
        $file_count = count($_FILES['dtaill_img_product']['name']);
        for ($i = 0; $i < $file_count; $i++) {
            if ($_FILES['dtaill_img_product']['error'][$i] === UPLOAD_ERR_OK) {
                $file_name = $_FILES['dtaill_img_product']['name'][$i];
                $target_path = '../images/' . $file_name;
                if (move_uploaded_file($_FILES['dtaill_img_product']['tmp_name'][$i], $target_path)) {
                    $dtaill_img_products[] = $file_name; // เก็บชื่อไฟล์ใน array หากอัปโหลดสำเร็จ
                } else {
                    echo "การย้ายไฟล์ล้มเหลว: " . $file_name;
                    exit;
                }
            } else {
                echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์: " . $_FILES['dtaill_img_product']['name'][$i];
                exit;
            }
        }
    }

    // ทำการเข้ารหัส (serialize) array เพื่อเก็บในฐานข้อมูล
    $dtaill_img_product = serialize($dtaill_img_products);

    // สร้างคำสั่ง SQL เพื่อเพิ่มข้อมูล
    $sql = "INSERT INTO product (name_product, img_product, type_product, dtaill_product, dtaill_img_product, date_product, dtaill_vdo_product) 
            VALUES (:name_product, :img_product, :type_product, :dtaill_product, :dtaill_img_product, :date_product, :dtaill_vdo_product)";

    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([
        'name_product' => $name_product,
        'img_product' => $img_product,
        'type_product' => $type_product,
        'dtaill_product' => $dtaill_product,
        'dtaill_img_product' => $dtaill_img_product,
        'date_product' => $date_product,
        'dtaill_vdo_product' => $dtaill_vdo_product
    ])) {
        header("Location: product_list.php");
        exit;
    } else {
        echo "เกิดข้อผิดพลาดในการเพิ่มข้อมูลสินค้า";
    }
}
?>
