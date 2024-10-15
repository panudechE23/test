<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}
require "../db_connect.php";

if (isset($_POST['submit'])) {
    $name_advert = $_POST['name_advert'];
    $detail_advert = $_POST['detail_advert'];
    $start_date = $_POST['start_date_advert'];
    $end_date = $_POST['end_date_advert'];
    $active_advert = $_POST['active_advert']; // ตรวจสอบค่า active_advert

    // Prepare SQL for inserting advert information
    $sql = "INSERT INTO advert (name_advert, detail_advert, start_date_advert, end_date_advert, active_advert) 
            VALUES (:name_advert, :detail_advert, :start_date_advert, :end_date_advert, :active_advert )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name_advert' => $name_advert,
        ':detail_advert' => $detail_advert,
        ':start_date_advert' => $start_date,
        ':end_date_advert' => $end_date,
        ':active_advert' => $active_advert,
        // ':id_admin' => $id_admin // Include admin ID
    ]);

    // Get the ID of the newly inserted advert
    $id_advert = $pdo->lastInsertId();

    // Create directory for uploading images
    $upload_dir = "../images/advert/" . $id_advert . "/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Check if the directory is writable
    if (!is_writable($upload_dir)) {
        echo "Directory is not writable: " . $upload_dir;
        exit;
    }

    // Upload main image
    $img_advert = '';
    if (isset($_FILES['img_advert']) && $_FILES['img_advert']['error'] == UPLOAD_ERR_OK) {
        $img_advert = basename($_FILES['img_advert']['name']);
        $target_path = $upload_dir . $img_advert;
        if (!move_uploaded_file($_FILES['img_advert']['tmp_name'], $target_path)) {
            echo "Error uploading main image.";
            exit;
        }
    } else {
        echo 'Error uploading main image: ' . $_FILES['img_advert']['error'];
        exit;
    }

    // Upload main image banner
    $img_banner_advert = ''; // for banner image
    if (isset($_FILES['img_banner_advert']) && $_FILES['img_banner_advert']['error'] == UPLOAD_ERR_OK) {
        $img_banner_advert = basename($_FILES['img_banner_advert']['name']);
        $target_path = $upload_dir . $img_banner_advert;
        if (!move_uploaded_file($_FILES['img_banner_advert']['tmp_name'], $target_path)) {
            echo "Error uploading banner image.";
            exit;
        }
    } else {
        echo 'Error uploading banner image: ' . $_FILES['img_banner_advert']['error'];
        exit;
    }

    // Handle additional images
    $img_detail_advert = [];
    if (isset($_FILES['img_detail_advert']) && is_array($_FILES['img_detail_advert']['name'])) {
        $file_count = count($_FILES['img_detail_advert']['name']);
        for ($i = 0; $i < $file_count; $i++) {
            if ($_FILES['img_detail_advert']['error'][$i] === UPLOAD_ERR_OK) {
                $file_name = basename($_FILES['img_detail_advert']['name'][$i]);
                $target_path = $upload_dir . $file_name;
                if (move_uploaded_file($_FILES['img_detail_advert']['tmp_name'][$i], $target_path)) {
                    $img_detail_advert[] = $file_name; // Store file names in array if upload is successful
                } else {
                    echo "Failed to move file: " . $file_name;
                    exit;
                }
            } else {
                echo "Error uploading file: " . $_FILES['img_detail_advert']['name'][$i] . " - Error code: " . $_FILES['img_detail_advert']['error'][$i];
                exit;
            }
        }
    }

    // Serialize additional image file names
    $img_detail_advert_serialized = serialize($img_detail_advert);

    // Update advert record with image file names
    $sql_update = "UPDATE advert SET img_advert = :img_advert, img_banner_advert = :img_banner_advert, img_detail_advert = :img_detail_advert 
               WHERE id_advert = :id_advert";
$stmt_update = $pdo->prepare($sql_update);
$stmt_update->execute([
    ':img_advert' => $img_advert,
    ':img_banner_advert' => $img_banner_advert,
    ':img_detail_advert' => $img_detail_advert_serialized,
    ':id_advert' => $id_advert
]);

    header("Location: list_advert.php");
    exit;
}
