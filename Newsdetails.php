
<?php
include 'db_connect.php'; // เชื่อมต่อฐานข้อมูล
$id_advert = isset($_GET['id_advert']) ? (int)$_GET['id_advert'] : 0;
// ดึงข้อมูล advert ตาม id_advert ที่ได้รับ
$sql = "SELECT * FROM advert WHERE id_advert = :id_advert";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_advert', $id_advert, PDO::PARAM_INT);
$stmt->execute();

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($stmt->rowCount() > 0) {
  $advert = $stmt->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูล advert ออกมา
} else {
  echo "ไม่พบข้อมูล advert";
  exit;
}

// ดึงข้อมูลรูปภาพจาก img_detail_advert
$img_ids = [];
if (isset($advert['img_detail_advert'])) {
  $img_data = $advert['img_detail_advert'];

  // ลอง unserialize ครั้งแรก
  $first_unserialize = @unserialize($img_data);

  // ตรวจสอบว่า unserialize สำเร็จหรือไม่
  if ($first_unserialize !== false && is_array($first_unserialize)) {
    // unserialize อีกครั้งในกรณีที่ข้อมูลยังคงเป็น serialized string
    foreach ($first_unserialize as $data) {
      $second_unserialize = @unserialize($data);
      if ($second_unserialize !== false && is_array($second_unserialize)) {
        $img_ids = array_merge($img_ids, $second_unserialize);
      } else {
        $img_ids[] = $data; // ถ้า unserialize ครั้งที่สองไม่ได้ ให้เพิ่มเป็นข้อมูลธรรมดา
      }
    }
  } else {
    // ถ้า unserialize ครั้งแรกไม่ได้ ให้ถือว่าเป็น string ธรรมดาและเพิ่มใน array
    $img_ids[] = $img_data;
  }
} else {
  echo "ไม่พบข้อมูล img_detail_advert";
  exit;
}
?>
<!DOCTYPE html>
<html style="font-size: 16px;" lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <meta name="keywords" content="INTUITIVE, Your design is your passion, Key Features, Pricing Plan, Empire State Building">
  <meta name="description" content="">
  <title>Newsdetails</title>
  <link rel="stylesheet" href="nicepage.css" media="screen">
  <link rel="stylesheet" href="Newsdetails.css" media="screen">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
  <script class="u-script" type="text/javascript" src="jquery.js" defer=""></script>
  <script class="u-script" type="text/javascript" src="nicepage.js" defer=""></script>
  <meta name="generator" content="Nicepage 6.18.5, nicepage.com">
  <link id="u-theme-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i">
  <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "Organization",
      "name": "",
      "logo": "images/MX.png"
    }
  </script>
  <meta name="theme-color" content="#478ac9">
  <meta property="og:title" content="Newsdetails">
  <meta property="og:type" content="website">
  <meta data-intl-tel-input-cdn-path="intlTelInput/">
  <style>
    .u-section-1 {
      background-image: url("images/advert/<?php echo htmlspecialchars($advert['id_advert']); ?>/<?php echo htmlspecialchars($advert['img_banner_advert']); ?>");
      background-position: 50% 50%;
    }
  </style>
</head>

<body data-path-to-root="./" data-include-products="false" class="u-body u-xl-mode" data-lang="en">
  <?php include('inc_header.php'); ?>

  <section class=" u-clearfix u-image u-section-1 " id="carousel_6570" data-image-width="5000" data-image-height="3750">
    <div class="u-clearfix u-sheet u-valign-bottom u-sheet-1">
      <div class="u-align-left u-container-align-left u-container-style u-group u-radius u-shape-round u-white u-group-1">
        <div class="u-container-layout u-valign-middle u-container-layout-1">
          <h2 class="u-align-left u-text u-text-1"><?php echo htmlspecialchars($advert['name_advert']); ?></h2>
          <a href="#" class="u-active-grey-70 u-align-center u-border-none u-btn u-btn-round u-button-style u-custom-color-7 u-hover-grey-70 u-radius-50 u-text-body-alt-color u-btn-1">Book a Babysitter</a>
        </div>
      </div>
    </div>
  </section>

  <section class="container col-md-6" id="sec-25ad">
    <div class="row ">
      <div class="col-md-6">
      <div class="banner-details">
        <?php echo '<p class="banner-description">' . ($advert['detail_advert']) . '</p>'; ?>
      </div>
    </div></div>
  </section>
  <section class="u-clearfix u-section-2" id="sec-25ad">
    <div class="u-clearfix u-sheet">
      <!-- Carousel for images -->
      <?php
      // ดึงข้อมูลรูปภาพจาก img_detail_advert
      $img_ids = [];
      if (isset($advert['img_detail_advert'])) {
        $img_data = $advert['img_detail_advert'];
        $unserialized_data = @unserialize($img_data);
        if ($unserialized_data !== false && is_array($unserialized_data)) {
          $img_ids = $unserialized_data;
        } else {
          $img_ids[] = $img_data;
        }
      } else {
        echo "ไม่พบข้อมูล img_detail_advert";
        exit;
      }
      ?>

      <!-- Carousel for images -->
      <div id="carousel-7080" data-interval="5000" data-u-ride="carousel" class="u-carousel u-expanded-width u-slider u-slider-1">
        <ol class="u-absolute-hcenter u-carousel-indicators u-carousel-indicators-1">
          <?php for ($i = 0; $i < ceil(count($img_ids) / 3); $i++): ?>
            <li data-u-target="#carousel-7080" class="<?php echo $i === 0 ? 'u-active ' : ''; ?>u-grey-30 u-shape-circle" data-u-slide-to="<?php echo $i; ?>" style="height: 10px; width: 10px;"></li>
          <?php endfor; ?>
        </ol>
        <div class="u-carousel-inner" role="listbox">
          <?php
          $slideIndex = 0;
          foreach (array_chunk($img_ids, 3) as $slideImages):
          ?>
            <div class="u-align-center u-carousel-item u-container-align-center u-container-style u-slide <?php echo $slideIndex === 0 ? 'u-active' : ''; ?>">
              <div class="u-container-layout u-container-layout-2">
                <div class="custom-expanded u-gallery u-layout-grid u-lightbox u-no-transition u-show-text-on-hover u-gallery-1">
                  <div class="u-gallery-inner u-gallery-inner-1">
                    <?php foreach ($slideImages as $img): ?>
                      <div class="u-effect-fade u-gallery-item">
                        <div class="u-back-slide">
                          <img class="u-back-image u-expanded" src="images/advert/<?php echo htmlspecialchars($advert['id_advert']); ?>/<?php echo htmlspecialchars($img); ?>">
                        </div>
                        <div class="u-over-slide u-shading u-over-slide-1"></div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php
            $slideIndex++;
          endforeach;
          ?>
        </div>
        <a class="u-absolute-vcenter u-carousel-control u-carousel-control-prev u-custom-color-7 u-shape-circle u-spacing-10 u-text-body-alt-color u-carousel-control-1" href="#carousel-7080" role="button" data-u-slide="prev">
          <span aria-hidden="true">
            <svg viewBox="0 0 477.175 477.175">
              <path d="M145.188,238.575l215.5-215.5c5.3-5.3,5.3-13.8,0-19.1s-13.8-5.3-19.1,0l-225.1,225.1c-5.3,5.3-5.3,13.8,0,19.1l225.1,225c2.6,2.6,6.1,4,9.5,4s6.9-1.3,9.5-4c5.3-5.3,5.3-13.8,0-19.1L145.188,238.575z"></path>
            </svg>
          </span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="u-absolute-vcenter u-carousel-control u-carousel-control-next u-custom-color-7 u-shape-circle u-spacing-10 u-text-body-alt-color u-carousel-control-2" href="#carousel-7080" role="button" data-u-slide="next">
          <span aria-hidden="true">
            <svg viewBox="0 0 477.175 477.175">
              <path d="M360.731,229.075l-225.1-225.1c-5.3-5.3-13.8-5.3-19.1,0s-5.3,13.8,0,19.1l215.5,215.5l-215.5,215.5c-5.3,5.3-5.3,13.8,0,19.1c2.6,2.6,6.1,4,9.5,4c3.4,0,6.9-1.3,9.5-4l225.1-225.1C365.931,242.875,365.931,234.275,360.731,229.075z"></path>
            </svg>
          </span>
          <span class="sr-only">Next</span>
        </a>
      </div>
      <a href="index.php"
        class="u-btn u-btn-round u-button-style u-custom-color-7 u-hover-custom-color-3 u-radius u-btn-3">กลับสู่หน้าหลัก
      </a>
    </div>
  </section>

  <?php include('footer.php'); ?>
</body>

</html>