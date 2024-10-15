<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}
require("../db_connect.php");

// ดึงข้อมูลแบนเนอร์ทั้งหมด
$sql = "SELECT * FROM banner";
$stmt = $pdo->query($sql);
$banners = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>รายการแบนเนอร์</title>
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
        }.table img {
            width: 100px;
            height: 100px;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="container ">
        
            <h2 >รายการแบนเนอร์</h2>
    
        <!-- <a href="ad_banner.php" class="btn btn-primary mb-3">เพิ่มแบนเนอร์ใหม่</a> -->

        <table class="table  table-striped" id="bannerTable">
            <thead>
                <tr>
                    <th>รหัสแบนเนอร์</th>
                    <th>ชื่อแบนเนอร์</th>
                    <th>รูปภาพแบนเนอร์</th>
                    <th>รายละเอียด</th>
                    <th>วันที่เริ่มแสดง</th>
                    <th>วันที่สิ้นสุดการแสดง</th>
                    <th>สถานะการแสดงผล</th>
                    <th>แก้ไข</th>
                    <th>ลบรายการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($banners as $banner): ?>
                <tr>
                    <td><?php echo htmlspecialchars($banner['id_banner']); ?></td>
                    <td><?php echo htmlspecialchars($banner['name_banner']); ?></td>
                    <td>
                    <img src="../images/banner/<?php echo htmlspecialchars($banner['id_banner']) . '/' . htmlspecialchars($banner['img_banner']); ?>" alt="Banner Image">

                    </td>
                    <td><?php echo '<p class="detail_banner">'.strip_tags($banner['detail_banner']).'</p>'; ?></td>
                    <td><?php echo htmlspecialchars($banner['start_date_banner']); ?></td>
                    <td><?php echo htmlspecialchars($banner['end_date_banner']); ?></td>
                    <td><?php echo ($banner['active_banner']) ? 'แสดง' : 'ไม่แสดง'; ?></td>
                    <td>
                        <a href="edit_banner.php?id=<?php echo $banner['id_banner']; ?>" class="edit-button">Edit</a>
                        
                    </td>
                    <td>
                    <a href="delete_banner.php?id=<?php echo $banner['id_banner']; ?>" class="delete-button "
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
        $('#bannerTable').DataTable({
            autoFill: false,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true
    });
    });
    </script>
</body>

</html>
