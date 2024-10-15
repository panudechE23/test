<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}
require "../db_connect.php";

// ดึงข้อมูลประเภทสินค้า (product types) จากฐานข้อมูล
$sql = "SELECT id_type, name_type FROM type";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$types = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ตรวจสอบว่ามีการส่ง ID ของสินค้ามาหรือไม่
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    // ดึงข้อมูลสินค้า (product) จากฐานข้อมูลโดยใช้ ID ที่ได้รับ
    $sql = "SELECT * FROM product WHERE id_product = :id_product";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_product' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบว่าข้อมูลถูกดึงมาและมีค่า 'type_product'
    if (isset($product['type_product'])) {
        $type_product = $product['type_product'];
    } else {
        $type_product = ''; // กำหนดค่าเริ่มต้นหากไม่มีข้อมูล
    }
} else {
    echo 'ID สินค้าไม่ถูกต้อง';
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <title>Edit Product</title>

    </script>
    <style>
        .body {
            padding-left: 50px;
        }
    </style>
</head>

<body>
    <div class="header_section header_bg">
        <div class="container-fluid">
            <?php include 'sidebar.php'; ?>
        </div>
    </div>
    <div class="container mt-5">
        <h2>แก้ไขข้อมูล</h2>
        <form action="edit_product_update.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id_product']); ?>">
            <div class="form-group-edit">
                <label for="name_product">ชื่อสินค้า</label>
                <input type="text" class="form-edit" id="name_product" name="name_product"
                    value="<?php echo htmlspecialchars($product['name_product']); ?>" required>
            </div>
            <div class="form-group-edit">
                <label for="img_product">รูปสินค้า</label>
                <input type="file" class="form-edit" id="img_product" name="img_product">
                <?php if ($product['img_product']): ?>
                        <img src="../images/product/<?php echo htmlspecialchars($product['id_product']); ?>/<?php echo htmlspecialchars($product['img_product']); ?>"
                            alt="product Image" width="200">
                        <?php endif; ?>
            </div>

           
            
            <div class="form-group-edit">
                <label for="banner_product">banner สินค้า</label>
                <input type="file" class="form-edit" id="banner_product" name="banner_product">
                <?php if ($product['banner_product']): ?>
                        <img src="../images/product/<?php echo htmlspecialchars($product['id_product']); ?>/<?php echo htmlspecialchars($product['banner_product']); ?>"
                            alt="product Image" width="200">
                        <?php endif; ?>
            </div>

            <div class="form-group-edit">
                <label for="id_type">Product Type</label>
                <select class="form-edit" id="id_type" name="id_type" required>
                    <?php if (is_array($types) && !empty($types)) : ?>
                        <?php foreach ($types as $type) : ?>
                            <option value="<?= htmlspecialchars($type['id_type']); ?>"
                                <?= (isset($product['id_type']) && $type['id_type'] == $product['id_type']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($type['name_type']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">ไม่มีประเภทสินค้า</option>
                    <?php endif; ?>
                </select>
            </div>



            <div class="form-group-edit">
                <label for="detail_product">รายละเอียดสินค้า</label>
                <textarea class="form-edit-product" id="detail" name="detail_product"
                    required><?php echo htmlspecialchars($product['detail_product']); ?></textarea>
            </div>

            <div class="form-group-edit">
                <label for="img_2_product">รูปภาพสินค้า2</label>
                <input type="file" class="form-edit" id="img_2_product" name="img_2_product">
                <?php if ($product['img_2_product']): ?>
                        <img src="../images/product/<?php echo htmlspecialchars($product['id_product']); ?>/<?php echo htmlspecialchars($product['img_2_product']); ?>"
                            alt="product Image" width="200">
                        <?php endif; ?>
            </div>

            <div class="form-group-edit">
                <label for="detail_2_product">รายละเอียดสินค้า2</label>
                <textarea class="form-edit-product" id="detail" name="detail_2_product"
                    required><?php echo htmlspecialchars($product['detail_2_product']); ?></textarea>
            </div>



            <div class="form-group-edit">
                <label for="img_detail_product">ภาพรายละเอียดรูป</label>
                <input type="file" class="form-edit" id="img_detail_product" name="img_detail_product[]" multiple
                    accept="image/*">

                <?php
                // แปลงข้อมูลจาก serialize กลับมาเป็น array
                $images = unserialize($product['img_detail_product']) ?: [];

                if (!empty($images)) {
                    echo '<div class="row">';
                    foreach ($images as $img) {
                        $img = trim($img);
                        if (!empty($img)) {
                            $img_path = "../images/" . htmlspecialchars($img);

                            // ตรวจสอบว่าไฟล์เป็นรูปภาพหรือไม่
                            if (file_exists($img_path) && @getimagesize($img_path)) {
                                echo '<div class="col-md-2 col-sm-3 mb-4">';
                                echo '<img src="' . $img_path . '" alt="Product Detail Image" class="img-fluid" style="width: 100%;">';
                                echo '</div>';
                            }
                        }
                    }
                    echo '</div>';
                } else {
                    echo '<p>No images available.</p>';
                }
                ?>
            </div>



            <div class="form-group-edit">
                <label for="date_product">Date</label>
                <input type="date" class="form-edit" id="date_product" name="date_product"
                    value="<?php echo htmlspecialchars($product['date_product']); ?>" required>
            </div>

            <div class="form-group-edit">
                <label for="vdo_detail_product">Video Details</label>
                <input type="text" class="form-edit" id="vdo_detail_product" name="vdo_detail_product"
                    value="<?php echo htmlspecialchars($product['vdo_detail_product']); ?>">
                <?php
                $video_id = htmlspecialchars($product['vdo_detail_product']);

                if (!empty($video_id)) {
                    echo '<div class="embed-responsive embed-responsive-16by9" style="margin-top: 10px;">';
                    echo '<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/' . $video_id . '" allowfullscreen></iframe>';
                    echo '</div>';
                }
                ?>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Save Changes</button>
            <button type="button" class=" btn-back-button" onclick="goBack()">ย้อนกลับ</button> <!-- ใช้ type="button" -->
        </form>
    </div>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>