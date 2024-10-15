
<?php
include 'db_connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่ง id_product มาหรือไม่
$id_product = isset($_GET['id_product']) ? $_GET['id_product'] : 20
; // กำหนดค่า id_product เป็น 20 ถ้าไม่มีการส่งค่าเข้ามา

// ฟังก์ชันดึง IP Address ของผู้เข้าชม// ฟังก์ชันดึง IP Address ของผู้เข้าชม และรวมกับวันที่วันนี้
function getUserIPWithDate() {
    $ip_address = 'UNKNOWN';

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }

    $current_date = date('Y-m-d'); // ใช้เฉพาะวันที่
    return $ip_address . '+' . $current_date; // รวม IP Address กับวันที่
}

// บันทึกการเข้าชมลงในฐานข้อมูล view_product
$ip_address_with_date = getUserIPWithDate();
$view_date = date('Y-m-d'); // ใช้เฉพาะวันที่

// ตรวจสอบว่ามีการบันทึกแล้วหรือไม่
$check_sql = "SELECT COUNT(*) FROM view_product WHERE id_product = :id_product AND view_date = :view_date AND ip_address = :ip_address_with_date";
$check_stmt = $pdo->prepare($check_sql);
$check_stmt->execute([
    ':id_product' => $id_product,
    ':view_date' => $view_date,
    ':ip_address_with_date' => $ip_address_with_date
]);
$existing_views = $check_stmt->fetchColumn();

if ($existing_views == 0) {
    // หากยังไม่มีการเข้าชม ให้บันทึกใหม่
    $insert_view_sql = "INSERT INTO view_product (id_product, view_date, ip_address) VALUES (:id_product, :view_date, :ip_address_with_date)";
    $view_stmt = $pdo->prepare($insert_view_sql);
    $view_stmt->execute([
        ':id_product' => $id_product,
        ':view_date' => $view_date,
        ':ip_address_with_date' => $ip_address_with_date
    ]);
}


// ดึงข้อมูล product ตาม id_product ที่ได้รับ
$sql = "SELECT * FROM product WHERE id_product = :id_product";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_product', $id_product, PDO::PARAM_INT);
$stmt->execute();

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($stmt->rowCount() > 0) {
    $product = $stmt->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูล product ออกมา
} else {
    echo "ไม่พบข้อมูล product";
    exit;
}

// ดึงข้อมูลรูปภาพจาก img_detail_product
$img_ids = [];
if (isset($product['img_detail_product'])) {
    $img_data = $product['img_detail_product'];

    // ลอง unserialize ครั้งแรก
    $first_unserialize = @unserialize($img_data);

    // ตรวจสอบว่า unserialize สำเร็จหรือไม่
    if ($first_unserialize !== false && is_array($first_unserialize)) {
        foreach ($first_unserialize as $data) {
            $second_unserialize = @unserialize($data);
            if ($second_unserialize !== false && is_array($second_unserialize)) {
                $img_ids = array_merge($img_ids, $second_unserialize);
            } else {
                $img_ids[] = $data; // ถ้า unserialize ครั้งที่สองไม่ได้ ให้เพิ่มเป็นข้อมูลธรรมดา
            }
        }
    } else {
        $img_ids[] = $img_data; // ถ้า unserialize ครั้งแรกไม่ได้ ให้ถือว่าเป็น string ธรรมดาและเพิ่มใน array
    }
} else {
    echo "ไม่พบข้อมูล img_detail_product";
    exit;
}
?>




<!DOCTYPE html>
<html style="font-size: 16px;" lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="keywords" content="About Us, ​We make a small, intimate, and inviting space for an unforgettable meal, ​We’re not just a restaurant. We’re a cultural experience, ​Keep up to date with us, 40%, Our Contact">
    <meta name="description" content="">
    <title>Productdetails</title>
    <link rel="stylesheet" href="nicepage.css" media="screen">
    <link rel="stylesheet" href="Productdetails.css" media="screen">
    <script class="u-script" type="text/javascript" src="jquery.js" defer=""></script>
    <script class="u-script" type="text/javascript" src="nicepage.js" defer=""></script>
    <meta name="generator" content="Nicepage 6.18.5, nicepage.com">
    <link id="u-theme-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i">



    <style>

.u-section-4-1 .u-image-1 {
  background-image: url("images/product/<?php echo htmlspecialchars($product['id_product']); ?>/<?php echo htmlspecialchars($product['img_detail_product']); ?>");
  background-position: 50% 50%;
}

  .u-section-2 .u-image-1 {
  min-height: 411px;
  margin-top: 75px;
  margin-bottom: 75px;
  height: auto;
  background-image: url("images/product/<?php echo htmlspecialchars($product['id_product']); ?>/<?php echo htmlspecialchars($product['banner_product']); ?>");
  background-position: 50% 39.39%;
}
  </style>




    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "Organization",
            "name": "",
            "logo": "images/MX.png"
        }
    </script>
    <meta name="theme-color" content="#478ac9">
    <meta property="og:title" content="Productdetails">
    <meta property="og:type" content="website">
    <meta data-intl-tel-input-cdn-path="intlTelInput/">
</head>

<body data-path-to-root="./" data-include-products="false" class="u-body u-xl-mode" data-lang="en">
<?php include('inc_header.php'); ?>

<section class="u-clearfix u-section-1" id="sec-3d95">
        <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
            <div class="u-container-align-center-xs u-container-style u-expanded-width u-group u-radius-50 u-shape-round u-white u-group-1">
                <div class="u-container-layout u-container-layout-1">
                    <img class="u-image u-image-round u-radius-50 u-image-1" src="images/product/<?php echo htmlspecialchars($product['id_product']); ?>/<?php echo htmlspecialchars($product['img_product']); ?>" alt="" data-image-width="5118" data-image-height="3417">
                    <h6 class="u-align-center-xs u-text u-text-default u-text-1">Our mission</h6>
                    <h2 class="u-align-center-xs u-text u-text-default u-text-2"><?php echo htmlspecialchars ($product['name_product']); ?></h2>
                    <p class="u-align-center-xs u-text u-text-3"> <?php echo strip_tags ($product['detail_product']); ?> </p>
                </div>
            </div>
        </div>
    </section>
    <section class="u-clearfix u-custom-color-3 u-section-2" id="sec-0121">
        <div class="u-clearfix u-sheet u-sheet-1">
            <div class="u-container-align-center u-container-style u-group u-image u-radius-50 u-shape-round u-image-1" data-image-width="3010" data-image-height="3010">
                <div class="u-container-layout u-container-layout-1"></div>
            </div>
        </div>
    </section>
    <section class="u-clearfix u-section-3" id="carousel_4715">
        <div class="u-clearfix u-sheet u-valign-middle-xs u-sheet-1">
            <div class="u-container-align-center-xs u-container-style u-expanded-width u-group u-radius-50 u-shape-round u-white u-group-1">
                <div class="u-container-layout u-valign-top-xs u-container-layout-1">
                    <img class="u-image u-image-round u-radius-50 u-image-1"  src="images/product/<?php echo htmlspecialchars($product['id_product']); ?>/<?php echo htmlspecialchars($product['img_2_product']); ?>" alt="" data-image-width="1040" data-image-height="1040">
                    <h6 class="u-align-center-xs u-align-left-lg u-align-left-md u-align-left-sm u-text u-text-default u-text-1">Our mission</h6>
                    <h2 class="u-align-center-xs u-align-left-lg u-align-left-md u-align-left-sm u-text u-text-default-xl u-text-2"> We’re not just a restaurant. We’re a cultural experience</h2>
                    <p class="u-align-center-xs u-align-left-lg u-align-left-md u-align-left-sm u-text u-text-3"><?php echo  htmlspecialchars(strip_tags(string: $product['detail_2_product'])); ?></p>
                </div>
            </div>
        </div>
    </section>
    <section class="u-carousel u-slide u-block-87d7-1" id="carousel-7532" data-interval="5000" data-u-ride="carousel">
    <ol class="u-carousel-indicators u-block-87d7-5">
    <?php foreach (array_chunk($img_ids, 3) as $slide): ?>
            <li data-u-target="#carousel-7532" class="<?= ($index === 0 ? 'u-active ' : '') . 'u-grey-30' ?>" data-u-slide-to="<?= $index ?>"></li>
        <?php endforeach; ?>
    </ol>
    <div class="u-carousel-inner" role="listbox">
    <?php foreach (array_chunk($img_ids, 3) as $slideIndex => $slideImages): ?>
<div class="u-carousel-item u-clearfix u-white u-section-4-1 <?= $slideIndex === 0 ? 'u-active' : '' ?>">
    <div class="u-clearfix u-sheet u-sheet-1">
        <div class="u-expanded-width u-list u-list-1">
            <div class="u-repeater u-repeater-1">
                <?php foreach ($slideImages as $img_id): ?>
                <div class="u-container-align-center u-container-style u-image u-image-round u-list-item u-radius-50 u-repeater-item u-image-1" style="background-image: url('images/product/<?= htmlspecialchars($product['id_product']) ?>/<?= htmlspecialchars($img_id) ?>');">
                    <div class="u-container-layout u-similar-container u-container-layout-1"></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

    </div>
    <a class="u-absolute-vcenter u-carousel-control u-carousel-control-prev u-text-custom-color-7 u-block-87d7-3" href="#carousel-7532" role="button" data-u-slide="prev">
        <span aria-hidden="true">
            <svg viewBox="0 0 8 8">
                <path d="M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z"></path>
            </svg>
        </span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="u-absolute-vcenter u-carousel-control u-carousel-control-next u-text-custom-color-7 u-block-87d7-4" href="#carousel-7532" role="button" data-u-slide="next">
        <span aria-hidden="true">
            <svg viewBox="0 0 8 8">
                <path d="M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z"></path>
            </svg>
        </span>
        <span class="sr-only">Next</span>
    </a>
</section>

    <section class="u-clearfix u-section-5" id="sec-97d2">
        <div class="u-clearfix u-sheet u-sheet-1">
            <a href="index.php" class="u-btn u-btn-round u-button-style u-custom-color-7 u-hover-custom-color-3 u-radius u-btn-1">กลับสู่หน้าหลัก </a>
        </div>
    </section>



    <?php include('footer.php'); ?>

</body>

</html>