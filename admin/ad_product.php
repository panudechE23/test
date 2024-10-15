<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}
require "../db_connect.php";

// ดึงข้อมูลประเภทสินค้า
$sql = "SELECT id_type, name_type FROM type";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$types = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ดึงข้อมูลสินค้าที่ต้องการแก้ไข
$sqlProduct = "SELECT * FROM product WHERE id_product = :id_product";
$stmtProduct = $pdo->prepare($sqlProduct);
$stmtProduct->bindParam(':id_product', $id_product, PDO::PARAM_INT);
$stmtProduct->execute();
$product = $stmtProduct->fetch(PDO::FETCH_ASSOC);

// ตรวจสอบว่าข้อมูลถูกดึงมาและมีค่า 'type_product'
if (isset($product['type_product'])) {
    $type_product = $product['type_product'];
} else {
    $type_product = ''; // กำหนดค่าเริ่มต้นหากไม่มีข้อมูล
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>เพิ่มสินค้า</title>
    
    <style>.form-group-edit i {
    color: red;
}</style>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="container mt-5">
        <h1 class="mt-5">เพิ่มสินค้าใหม่</h1>
        <div class="card card-body">
            <form action="save_product.php" method="post" enctype="multipart/form-data">
            <div class="form-group-edit">
                    <label for="name_product">ชื่อสินค้า</label>
                    <input type="text" class="form-edit" id="name_product" name="name_product" required>
                </div>
                <div class="form-group-edit">
                    <label for="img_product">รูปภาพสินค้า</label>
                    <input type="file" class="form-edit" id="img_product" name="img_product" required>
                </div>
                
                <div class="form-group-edit">
                    <label for="img_product">banner สินค้า</label>
                    <input type="file" class="form-edit" id="banner_product" name="banner_product" required>
                </div>
                <div class="form-group-edit">
                    <label for="id_type">ประเภทสินค้า</label>
                    <div class="input-group">
                        <select class="form-edit" id="id_type" name="id_type" required>
                            <?php if (is_array($types) && !empty($types)) : ?>
                                <?php foreach ($types as $type) : ?>
                                    <option value="<?= $type['id_type']; ?>"
                                        <?= (isset($product['id_type']) && $type['id_type'] == $product['id_type']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($type['name_type']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">ไม่มีประเภทสินค้า</option>
                            <?php endif; ?>

                        </select>
                    </div>

                    
                    <!-- ปุ่มเปิด Modal เพิ่มประเภท -->
                    <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal"
                        data-bs-target="#addTypeModal">เพิ่มประเภทสินค้าใหม่</button>
                </div>

                <div class="form-group-edit">
                    <label for="detail_product">รายละเอียดสินค้า<i class='fas fa-exclamation'> การที่เพิ่มรูปในนี้ควรเป็นรูปที่นำมาจากเว็บ</i></label>
                    <textarea class="form-edit" id="detail" name="detail_product" rows="3"></textarea>
                </div>

                <div class="form-group-edit">
                    <label for="img_product">รูปภาพสินค้า2</label>
                    <input type="file" class="form-edit" id="img_2_product" name="img_2_product" required>
                </div>

                <div class="form-group-edit">
                    <label for="detail_product">รายละเอียดสินค้า2<i class='fas fa-exclamation'> การที่เพิ่มรูปในนี้ควรเป็นรูปที่นำมาจากเว็บ</i></label>
                    <textarea class="form-edit" id="detail" name="detail_2_product" rows="3"></textarea>
                </div>
                <div class="form-group-edit">
                    <label for="detail_img_product">ภาพรายละเอียด</label>
                    <input type="file" class="form-edit" id="detail_img_product" name="detail_img_product[]" multiple>
                </div>
                <div class="form-group-edit">
                    <label for="date_product">วันที่</label>
                    <input type="date" class="form-edit" id="date_product" name="date_product">
                </div>
                <div class="form-group-edit">
                    <label for="detail_vdo_product">ลิงก์วิดีโอรายละเอียด YouTube</label>
                    <input type="text" class="form-edit" id="detail_vdo_product" name="detail_vdo_product">
                </div>
                <button type="submit" name="submit" class="btn btn-primary">เพิ่มสินค้า</button>
            </form>

            <!-- Modal สำหรับเพิ่มประเภทสินค้า -->
            <div class="modal fade" id="addTypeModal" tabindex="-1" aria-labelledby="addTypeModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addTypeModalLabel">เพิ่มประเภทสินค้า</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addTypeForm">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name_type">ชื่อประเภทสินค้า</label>
                                    <input type="text" class="form-control" id="name_type" name="name_type" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#addTypeForm').on('submit', function(e) {
            e.preventDefault();
            var newType = $('#name_type').val();
            $.ajax({
                type: 'POST',
                url: 'add_product_type.php', // ไฟล์นี้จะรับข้อมูลประเภทสินค้าใหม่และเพิ่มลงในฐานข้อมูล
                data: {
                    name_type: newType
                },
                success: function(response) {
                    if (response === 'success') {
                        location.reload(); // โหลดหน้าใหม่เพื่ออัปเดต dropdown
                    } else if (response === 'exists') {
                        alert('ประเภทสินค้านี้มีอยู่แล้ว');
                    } else {
                        alert('เกิดข้อผิดพลาดในการเพิ่มประเภทสินค้า');
                    }
                }
            });
        });
    </script>
</body>

</html>