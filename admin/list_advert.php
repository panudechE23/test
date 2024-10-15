<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}
require("../db_connect.php");

// ดึงข้อมูลโฆษณาทั้งหมด
$sql = "SELECT * FROM advert";
$stmt = $pdo->query($sql);
$adverts = $stmt->fetchAll(PDO::FETCH_ASSOC);
$rank=1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>รายการโฆษณา</title>
    <style>
        .container {

            flex-direction: column;
        }
        .dropdown-toggle {
            cursor: pointer;
            font-size: 26px;
        }
        .list-product {
            padding-top: 30px;
            text-align: center;
        }
       
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="container ">

            <h2 >รายการโฆษณา</h2>
        <!-- <a href="ad_advert.php" class="btn btn-primary mb-3">เพิ่มโฆษณาใหม่</a> -->

        <table class="table table-striped " id="advertTable">
            <thead>
                <tr>
                    <!-- <th>ลำดับ</th> -->
                    <th>รหัสโฆษณา</th>
                    <th>ชื่อโฆษณา</th>
                    <th>รูปภาพโฆษณา</th>
                    <th>รูปภาพbannerโฆษณา</th>
                    <th>รายละเอียด</th>
                    <th>วันที่เริ่มแสดง</th>
                    <th>วันที่สิ้นสุดการแสดง</th>
                    <th>สถานะการแสดงผล</th>
                    <th>แก้ไข</th>
                    <th>ลบรายการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($adverts as $advert): ?>
                <tr>
                    <!-- <td><?php echo $rank++ ?></td> -->
                    <td><?php echo htmlspecialchars($advert['id_advert']); ?></td>
                    <td><p class="limited-text"><?php echo htmlspecialchars($advert['name_advert']); ?></p></td>
                    <td>
                    <img src="../images/advert/<?php echo htmlspecialchars($advert['id_advert']) . '/' . htmlspecialchars($advert['img_advert']); ?>" alt="advert Image">

                    </td>
                    <td>
                    <img src="../images/advert/<?php echo htmlspecialchars($advert['id_advert']) . '/' . htmlspecialchars($advert['img_banner_advert']); ?>" alt="advert Image">

                    </td>
                    <td><?php echo '<p class="detail_advert limited-text">'.strip_tags($advert['detail_advert']).'</p>'; ?></td>
                    <td><?php echo htmlspecialchars($advert['start_date_advert']); ?></td>
                    <td><?php echo htmlspecialchars($advert['end_date_advert']); ?></td>
                    <td><?php echo ($advert['active_advert']) ? 'แสดง' : 'ไม่แสดง'; ?></td>
                    <td>
                        <a href="edit_advert.php?id=<?php echo $advert['id_advert']; ?>" class="edit-button">Edit</a>
                        
                    </td>
                    <td>
                    <a href="delete_advert.php?id=<?php echo $advert['id_advert']; ?>" class="delete-button "
                    onclick="return confirm('จะลบรายการนี้ใช่ไหม');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <script>
    $(document).ready(function() {
    $('#advertTable').DataTable({
        "scrollCollapse":false,
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true
    });
});

    </script>
</body>

</html>
