<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}
require "../db_connect.php";

if (isset($_POST['submit'])) {
    $product_id = $_POST['product_id'];
    $name_product = $_POST['name_product'];
    $id_type = $_POST['id_type'];
    $detail_product = $_POST['detail_product'];
    $detail_2_product =$_POST['detail_2_product'];
    $date_product = $_POST['date_product'];
    $vdo_detail_product = $_POST['vdo_detail_product'];

    // Prepare params array
    $params = [
        'name_product' => $name_product,
        'id_type' => $id_type,
        'detail_product' => $detail_product,
        'detail_2_product'=>$detail_2_product,
        'date_product' => $date_product,
        'vdo_detail_product' => $vdo_detail_product,
        'id_product' => $product_id
    ];

    // Handling single file upload
    if (isset($_FILES['img_product']) && $_FILES['img_product']['error'] == UPLOAD_ERR_OK) {
        $img_product = $_FILES['img_product']['name'];
        move_uploaded_file($_FILES['img_product']['tmp_name'], "../images/" . $img_product);
        $params['img_product'] = $img_product; // Include in params
    }
       // banner single file upload
       if (isset($_FILES['banner_product']) && $_FILES['banner_product']['error'] == UPLOAD_ERR_OK) {
        $banner_product = $_FILES['banner_product']['name'];
        move_uploaded_file($_FILES['banner_product']['tmp_name'], "../images/" . $banner_product);
        $params['banner_product'] = $banner_product; // Include in params
    }

       // img2 single file upload
       if (isset($_FILES['img_2_product']) && $_FILES['img_2_product']['error'] == UPLOAD_ERR_OK) {
        $img_2_product = $_FILES['img_2_product']['name'];
        move_uploaded_file($_FILES['img_2_product']['tmp_name'], "../images/" . $img_2_product);
        $params['img_2_product'] = $img_2_product; // Include in params
    }


    // Handling multiple file uploads
    if (isset($_FILES['img_detail_product']) && !empty($_FILES['img_detail_product']['name'][0])) {
        $images = [];
        foreach ($_FILES['img_detail_product']['name'] as $key => $name) {
            if ($_FILES['img_detail_product']['error'][$key] == UPLOAD_ERR_OK) {
                $image = $name;
                move_uploaded_file($_FILES['img_detail_product']['tmp_name'][$key], "../images/" . $image);
                $images[] = $image;
            }
        }
        $img_detail_product = serialize($images);
        $params['img_detail_product'] = $img_detail_product; // Include in params
    }

    // Building the SQL query dynamically
    $sql = "UPDATE product SET
        name_product = :name_product,
        id_type = :id_type,
        detail_product = :detail_product,
        detail_2_product =:detail_2_product,
        date_product = :date_product,
        vdo_detail_product = :vdo_detail_product";

    // Add img_product if set
    if (isset($params['img_product'])) {
        $sql .= ", img_product = :img_product";
    }
    // Add banner_product if set
    if (isset($params['banner_product'])) {
        $sql .= ", banner_product = :banner_product";
    }
    if (isset($params['img_2_product'])) {
        $sql .= ", img_2_product = :img_2_product";
    }

    // Add img_detail_product if set
    if (isset($params['img_detail_product'])) {
        $sql .= ", img_detail_product = :img_detail_product";
    }

    // Complete the SQL statement
    $sql .= " WHERE id_product = :id_product";

    // Prepare and execute the statement
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute($params)) {
        header("Location: list_product.php");
        exit;
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูลสินค้า";
    }
} else {
    echo "Invalid request";
    exit;
}
