<?php
session_start();
require "../db_connect.php";  // Adjust the path if necessary

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}

// Retrieve view counts
$queryToday = $pdo->query("SELECT COUNT(*) as views_today FROM view_product WHERE DATE(view_date) = CURDATE()");
$viewsToday = $queryToday->fetch(PDO::FETCH_ASSOC)['views_today'];

$queryMonth = $pdo->query("SELECT COUNT(*) as views_month FROM view_product WHERE MONTH(view_date) = MONTH(CURDATE()) AND YEAR(view_date) = YEAR(CURDATE())");
$viewsMonth = $queryMonth->fetch(PDO::FETCH_ASSOC)['views_month'];

$queryYear = $pdo->query("SELECT COUNT(*) as views_year FROM view_product WHERE YEAR(view_date) = YEAR(CURDATE())");
$viewsYear = $queryYear->fetch(PDO::FETCH_ASSOC)['views_year'];

$queryTotal = $pdo->query("SELECT COUNT(*) as views_total FROM view_product");
$viewsTotal = $queryTotal->fetch(PDO::FETCH_ASSOC)['views_total'];

$rank = 1;

// Get selected filters from POST
$selected_year = $_POST['year'] ?? null;
$selected_month = $_POST['month'] ?? null;

// Prepare data for the chart
if ($selected_year && $selected_month) {
    // Year and month selected: Show views per day
    $query = $pdo->prepare("SELECT DAY(view_date) as view_day, COUNT(*) as views 
                            FROM view_product 
                            WHERE YEAR(view_date) = :year AND MONTH(view_date) = :month
                            GROUP BY view_day 
                            ORDER BY view_day");
    $query->execute(['year' => $selected_year, 'month' => $selected_month]);
    $data = $query->fetchAll(PDO::FETCH_ASSOC);
    $days_in_month = cal_days_in_month(CAL_GREGORIAN, $selected_month, $selected_year);
    $view_labels = range(1, $days_in_month);
    $view_counts = array_fill(0, $days_in_month, 0);
    foreach ($data as $row) {
        $index = $row['view_day'] - 1;
        $view_counts[$index] = $row['views'];
    }
} elseif ($selected_year) {
    // Only year selected: Show views per month
    $query = $pdo->prepare("SELECT MONTH(view_date) as view_month, COUNT(*) as views 
                            FROM view_product 
                            WHERE YEAR(view_date) = :year 
                            GROUP BY view_month 
                            ORDER BY view_month");
    $query->execute(['year' => $selected_year]);
    $data = $query->fetchAll(PDO::FETCH_ASSOC);
    $months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 
               'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
    $view_counts = array_fill(0, 12, 0);
    foreach ($data as $row) {
        $index = $row['view_month'] - 1;
        $view_counts[$index] = $row['views'];
    }
    $view_labels = $months;
} else {
    // No filters selected: Show views per year
    $query = $pdo->query("SELECT YEAR(view_date) as view_year, COUNT(*) as views 
                          FROM view_product 
                          GROUP BY view_year 
                          ORDER BY view_year");
    $data = $query->fetchAll(PDO::FETCH_ASSOC);
    $view_labels = array_column($data, 'view_year');
    $view_counts = array_column($data, 'views');
}
// Return the data as JSON
// echo json_encode(['labels' => $view_labels, 'counts' => $view_counts]);
// Top 5 products by views
$queryTopProducts = $pdo->query("SELECT p.id_product, p.name_product, p.img_product, COUNT(vp.id_view) AS total_views, MAX(vp.view_date) AS last_view_date 
                                 FROM product p
                                 JOIN view_product vp ON p.id_product = vp.id_product 
                                 GROUP BY p.id_product 
                                 ORDER BY total_views DESC 
                                 LIMIT 5");
$topProducts = $queryTopProducts->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
    <style>
        .body{
        background-color: antiquewhite;
        }
        .container {
            margin-top: 70px;
        }

        .card {

            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        .card .card-body1 {
            text-align: center;

        }

        .card-title {
            font-size: 2.5em;
            font-weight: bold;
            margin-top: 10px;
        }


        .card.text-white {
            color: white;
        }

        @media (min-width: 768px) {
            .card-title {
                font-size: 3em;
                /* ขนาดตัวอักษรใหญ่ขึ้นในหน้าจอใหญ่ */
            }
        }

        .row .col-6 {
            margin-bottom: 20px;
            /* เพิ่มระยะห่างระหว่าง card */
        }

        .table-responsive img {
            width: 50px;
            height: 50px;
        }

        .chart-container {
            width: 100%;
            height: 400px;
        }

        .list-group-item {
            background-color: #f8f9fa;
        }

        .form-control-date {
            width: 100%;
            max-width: none !important;
            padding: 10px;
            border-radius: 2px !important;
            border: 1px solid #FAF5F5;
            box-shadow: 0 2px 5px #050505;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }


        .table-striped {
            background-color: #f9f9f9;
            /* border-collapse: separate; */
            border-spacing: 1px;
            width: 100%;
            text-align: center;
            padding-bottom: 50px;
        }

        .table-striped thead th {
            color: black;
            padding: 10px;
            text-align: center;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .table-striped tbody td {
            padding: 10px;
            vertical-align: middle;
        }

        .table-striped img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
        }

        .viewsChart {
            max-width: auto;
            max-height: 1500px;
        }

        .cards-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            position: relative;
            gap: 20px;
        }

        .card-dashboard {
            flex: 1 1 calc(50% - 10px);
            border-radius: 20px;
            margin-bottom: 0px !important;
            position: relative;
            min-height: 200px;
            margin-top: 0px !important;
            /* กำหนดความสูงขั้นต่ำของ card */
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <h2>Dashboard</h2>
        <!-- สถิติยอดเข้าชม -->
        <div class="row row-cols-2">
            <div class="col ">
                <div class="card text-white bg-primary mb-4">
                    <div class="card-body1">
                        <h3>ยอดเข้าชมวันนี้</h3>
                        <h5 class=" card-title"><?php echo $viewsToday; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col ">
                <div class="card text-white bg-success mb-4">
                    <div class="card-body1">
                        <h3>ยอดเข้าชมเดือนนี้</h3>
                        <h5 class=" card-title"><?php echo $viewsMonth; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col ">
                <div class="card text-white bg-warning mb-4">
                    <div class="card-body1">
                        <h3>ยอดเข้าชมปีนี้</h3>
                        <h5 class=" card-title"><?php echo $viewsYear; ?></h5>
                    </div>
                </div>
            </div>
            <div class="col ">
                <div class="card text-white bg-danger mb-4">
                    <div class="card-body1">
                        <h3>ยอดเข้าชมทั้งหมด</h3>
                        <h5 class=" card-title"><?php echo $viewsTotal; ?></h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h2>กราฟยอดเข้าชม</h2>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row g-3">
                                <!-- Year selection -->
                                <div class="col-md-6">
                                    <label for="year" class="form-label">เลือกปี:</label>
                                    <select name="year" id="year" class="form-control-date ">
                                        <option value="">ทั้งหมด</option>
                                        <?php 
                                        $queryYears = $pdo->query("SELECT DISTINCT YEAR(view_date) as year FROM view_product ORDER BY year DESC");
                                        $years = $queryYears->fetchAll(PDO::FETCH_COLUMN);
                                        foreach ($years as $year): ?>
                                            <option value="<?php echo $year; ?>" <?php echo ($selected_year == $year) ? 'selected' : ''; ?>>
                                                <?php echo $year; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- Month selection -->
                                <div class="col-md-6">
                                    <label for="month" class="form-label">เลือกเดือน:</label>
                                    <select name="month" id="month" class="form-control-date" disabled>
                                        <option value="">เลือกเดือน</option>
                                        <?php
                                        $monthNames = [
                                            'มกราคม',
                                            'กุมภาพันธ์',
                                            'มีนาคม',
                                            'เมษายน',
                                            'พฤษภาคม',
                                            'มิถุนายน',
                                            'กรกฎาคม',
                                            'สิงหาคม',
                                            'กันยายน',
                                            'ตุลาคม',
                                            'พฤศจิกายน',
                                            'ธันวาคม'
                                        ];
                                        for ($month = 1; $month <= 12; $month++): ?>
                                            <option value="<?php echo $month; ?>" <?php echo ($selected_month == $month) ? 'selected' : ''; ?>>
                                                <?php echo $monthNames[$month - 1]; ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mt-4" href="">แสดงผล</button>
                            </div>
                            
                        </form>

                        <!-- Chart -->
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <canvas id="viewsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- สินค้าที่มียอดเข้าชมสูงสุด 5 อันดับ -->
        <div class="row ">

            <div class="col-md-12">
                <h2>สินค้าที่มียอดเข้าชมสูงสุด 5 อันดับ</h2>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>อันดับ</th>
                                <th>id</th>
                                <th>รูปสินค้า</th>
                                <th>ชื่อสินค้า</th>
                                <th>จำนวนคนดู</th>
                                <th>วันที่คนดูล่าสุด</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topProducts as $product): ?>
                                <tr>
                                    <td><?php echo $rank++; ?></td>
                                    <td><?php echo htmlspecialchars($product['id_product']); ?></td>
                                    <td><img src="../images/product/<?php echo htmlspecialchars($product['id_product']); ?>/<?php echo htmlspecialchars($product['img_product']); ?>" alt="Product Image">
                                    </td>
                                    <td><?php echo $product['name_product']; ?></td>
                                    <td><?php echo $product['total_views']; ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($product['last_view_date'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Create the chart
        var ctx = document.getElementById('viewsChart').getContext('2d');
        var viewsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($view_labels); ?>,
                datasets: [{
                    label: 'ยอดเข้าชม',
                    data: <?php echo json_encode($view_counts); ?>,
                    backgroundColor: 'rgba(204, 232, 231, 1)',
                    borderColor: 'rgba(16, 16, 16, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Handle the enabling/disabling of dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            const yearSelect = document.getElementById('year');
            const monthSelect = document.getElementById('month');

            // Disable month select initially if no year is selected
            if (!yearSelect.value) {
                monthSelect.disabled = true;
            }

            // Year change event
            yearSelect.addEventListener('change', function() {
                if (this.value) {
                    monthSelect.disabled = false;
                } else {
                    monthSelect.disabled = true;
                    monthSelect.value = "";
                }
            });
        });
    </script>
</body>

</html>
</body>

</html>