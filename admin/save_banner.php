<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}
require "../db_connect.php";

$id_admin = $_SESSION['id_admin'];

if (isset($_POST['submit'])) {
    $name_banner = $_POST['name_banner'];
    $detail_banner = $_POST['detail_banner'];
    $start_date = $_POST['start_date_banner'];
    $end_date = $_POST['end_date_banner'];
    $active_banner = $_POST['active_banner'] ; // ตรวจสอบค่า active_banner

    // Prepare SQL for inserting banner information
    $sql = "INSERT INTO banner (name_banner, detail_banner, start_date_banner, end_date_banner, active_banner) 
            VALUES (:name_banner, :detail_banner, :start_date_banner, :end_date_banner, :active_banner )";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name_banner' => $name_banner,
        ':detail_banner' => $detail_banner,
        ':start_date_banner' => $start_date,
        ':end_date_banner' => $end_date,
        ':active_banner' => $active_banner,
        // ':id_admin' => $id_admin // Include admin ID
    ]);

    // Get the ID of the newly inserted banner
    $id_banner = $pdo->lastInsertId();

    // Create directory for uploading images
    $upload_dir = "../images/banner/" . $id_banner . "/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Check if the directory is writable
    if (!is_writable($upload_dir)) {
        echo "Directory is not writable: " . $upload_dir;
        exit;
    }

    // Upload main image
    $img_banner = '';
    if (isset($_FILES['img_banner']) && $_FILES['img_banner']['error'] == UPLOAD_ERR_OK) {
        $img_banner = basename($_FILES['img_banner']['name']);
        $target_path = $upload_dir . $img_banner;
        if (!move_uploaded_file($_FILES['img_banner']['tmp_name'], $target_path)) {
            echo "Error uploading main image.";
            exit;
        }
    } else {
        echo 'Error uploading main image: ' . $_FILES['img_banner']['error'];
        exit;
    }

    // Handle additional images
    $img_detail_banner = [];
    if (isset($_FILES['img_detail_banner']) && is_array($_FILES['img_detail_banner']['name'])) {
        $file_count = count($_FILES['img_detail_banner']['name']);
        for ($i = 0; $i < $file_count; $i++) {
            if ($_FILES['img_detail_banner']['error'][$i] === UPLOAD_ERR_OK) {
                $file_name = basename($_FILES['img_detail_banner']['name'][$i]);
                $target_path = $upload_dir . $file_name;
                if (move_uploaded_file($_FILES['img_detail_banner']['tmp_name'][$i], $target_path)) {
                    $img_detail_banner[] = $file_name; // Store file names in array if upload is successful
                } else {
                    echo "Failed to move file: " . $file_name;
                    exit;
                }
            } else {
                echo "Error uploading file: " . $_FILES['img_detail_banner']['name'][$i] . " - Error code: " . $_FILES['img_detail_banner']['error'][$i];
                exit;
            }
        }
    }
    
    // Serialize additional image file names
    $img_detail_banner_serialized = serialize($img_detail_banner);

    // Update banner record with image file names
    $sql_update = "UPDATE banner SET img_banner = :img_banner, img_detail_banner = :img_detail_banner 
                   WHERE id_banner = :id_banner";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([
        ':img_banner' => $img_banner,
        ':img_detail_banner' => $img_detail_banner_serialized,
        ':id_banner' => $id_banner
    ]);

    header("Location: list_banner.php");
    exit;
}
?>
