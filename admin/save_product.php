<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}
require("../db_connect.php"); // เชื่อมต่อฐานข้อมูล

if (isset($_POST['submit'])) {
    // รับข้อมูลจากฟอร์ม
    $name_product = $_POST['name_product'];
    $id_type = $_POST['id_type'];
    $detail_product = $_POST['detail_product']; 
    $detail_2_product = $_POST['detail_2_product']; // ฟิลด์ใหม่สำหรับรายละเอียดเพิ่มเติม
    $date_product = $_POST['date_product'];
    $vdo_detail_product = $_POST['vdo_detail_product']; 

    // ลบ https://www.youtube.com/watch/?v= และ https://youtu.be/ ออกจากลิงก์
    $vdo_detail_product = preg_replace('/^https:\/\/(www\.youtube\.com\/(watch\?v=|shorts\/)|youtu\.be\/)/', '', $vdo_detail_product);

    // ทำการเข้ารหัส (serialize) array เพื่อเก็บในฐานข้อมูล
    $img_detail_product = serialize([]);

    // สร้างคำสั่ง SQL เพื่อเพิ่มข้อมูล (ขั้นตอนที่ 1: บันทึกข้อมูลสินค้าโดยไม่มีรูปภาพ)
    $sql = "INSERT INTO product (name_product, img_product, img_2_product, banner_product, id_type, detail_product, detail_2_product, img_detail_product, date_product, vdo_detail_product) 
            VALUES (:name_product, '', '', '', :id_type, :detail_product, :detail_2_product, :img_detail_product, :date_product, :vdo_detail_product)";

    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([
        'name_product' => $name_product,
        'id_type' => $id_type,
        'detail_product' => $detail_product,
        'detail_2_product' => $detail_2_product, // เพิ่มการบันทึก detail_2_product
        'img_detail_product' => $img_detail_product,
        'date_product' => $date_product,
        'vdo_detail_product' => $vdo_detail_product,
    ])) {
        // ดึง ID ของสินค้า (id_product) ที่เพิ่งถูกแทรก
        $id_product = $pdo->lastInsertId();

        // สร้างไดเรกทอรีสำหรับบันทึกรูปภาพ
        $upload_dir = "../images/product/" . $id_product . "/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // จัดการกับรูปภาพสินค้า img_product
        $img_product = '';
        if (isset($_FILES['img_product']) && $_FILES['img_product']['error'] == UPLOAD_ERR_OK) {
            $img_product = $_FILES['img_product']['name'];
            $target_path = $upload_dir . $img_product;
            if (!move_uploaded_file($_FILES['img_product']['tmp_name'], $target_path)) {
                echo "เกิดข้อผิดพลาดในการอัปโหลดรูปภาพสินค้า";
                exit;
            }
        }

        // จัดการกับรูปภาพใหม่ img_2_product
        $img_2_product = '';
        if (isset($_FILES['img_2_product']) && $_FILES['img_2_product']['error'] == UPLOAD_ERR_OK) {
            $img_2_product = $_FILES['img_2_product']['name'];
            $target_path = $upload_dir . $img_2_product;
            if (!move_uploaded_file($_FILES['img_2_product']['tmp_name'], $target_path)) {
                echo "เกิดข้อผิดพลาดในการอัปโหลดรูปภาพสินค้าเพิ่มเติม";
                exit;
            }
        }

        // จัดการกับรูปภาพแบนเนอร์ banner_product
        $banner_product = '';
        if (isset($_FILES['banner_product']) && $_FILES['banner_product']['error'] == UPLOAD_ERR_OK) {
            $banner_product = $_FILES['banner_product']['name'];
            $target_path = $upload_dir . $banner_product;
            if (!move_uploaded_file($_FILES['banner_product']['tmp_name'], $target_path)) {
                echo "เกิดข้อผิดพลาดในการอัปโหลดแบนเนอร์สินค้า";
                exit;
            }
        }

        // จัดการกับรูปภาพรายละเอียด (หลายรูป)
        $img_detail_products = [];
        if (isset($_FILES['detail_img_product']) && !empty($_FILES['detail_img_product']['name'][0])) {
            $file_count = count($_FILES['detail_img_product']['name']);
            for ($i = 0; $i < $file_count; $i++) {
                if ($_FILES['detail_img_product']['error'][$i] === UPLOAD_ERR_OK) {
                    $file_name = $_FILES['detail_img_product']['name'][$i];
                    $target_path = $upload_dir . $file_name;
                    if (move_uploaded_file($_FILES['detail_img_product']['tmp_name'][$i], $target_path)) {
                        $img_detail_products[] = $file_name; // เก็บชื่อไฟล์ใน array หากอัปโหลดสำเร็จ
                    } else {
                        echo "การย้ายไฟล์ล้มเหลว: " . $file_name;
                        exit;
                    }
                } else {
                    echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์: " . $_FILES['detail_img_product']['name'][$i];
                    exit;
                }
            }
        }

        // ทำการเข้ารหัส (serialize) array เพื่อเก็บในฐานข้อมูล
        $img_detail_product = serialize($img_detail_products);

        // อัปเดตข้อมูลสินค้าเพื่อบันทึกรูปภาพ (ขั้นตอนที่ 2: อัปเดตข้อมูลสินค้า)
        $sql_update = "UPDATE product SET img_product = :img_product, img_2_product = :img_2_product, banner_product = :banner_product, img_detail_product = :img_detail_product WHERE id_product = :id_product";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            'img_product' => $img_product,
            'img_2_product' => $img_2_product, // เพิ่มการบันทึก img_2_product
            'banner_product' => $banner_product, // เพิ่มการบันทึก banner_product
            'img_detail_product' => $img_detail_product,
            'id_product' => $id_product
        ]);

        // เปลี่ยนเส้นทางไปยังหน้ารายการสินค้า
        header("Location: list_product.php");
        exit;
    } else {
        echo "เกิดข้อผิดพลาดในการเพิ่มข้อมูลสินค้า";
    }
}

?>
