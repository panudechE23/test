<?php 
include 'db_connect.php'; // ตรวจสอบให้แน่ใจว่าเชื่อมต่อฐานข้อมูลเรียบร้อยแล้ว

// คำสั่ง SQL สำหรับการดึงข้อมูลจากตาราง banner
$sqlBanners = "SELECT id_banner, img_banner FROM banner";
$resultBanners = $pdo->query($sqlBanners);

// ดึงข้อมูลแบนเนอร์ทั้งหมด
$bannerData = $resultBanners->fetchAll(PDO::FETCH_ASSOC);
?>


<section class="u-carousel u-slide u-block-d434-1" id="carousel-881e" data-interval="5000" data-u-ride="carousel">
    <ol class="u-absolute-hcenter u-carousel-indicators u-block-d434-2">
        <?php foreach ($bannerData as $index => $banner): ?>
            <li data-u-target="#carousel-881e" data-u-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'u-active u-grey-30' : 'u-grey-30'; ?>"></li>
        <?php endforeach; ?>
    </ol>
    <div class="u-carousel-inner" role="listbox">
        <?php foreach ($bannerData as $index => $banner): ?>
            <div class="u-carousel-item u-container-style u-section-10-<?php echo $index + 1; ?> <?php echo $index === 0 ? 'u-active' : ''; ?>">
                <div class="u-clearfix u-sheet u-sheet-1">
                    <img class="u-expanded-width u-image u-image-1" src="images/banner/<?php echo htmlspecialchars($banner['id_banner']); ?>/<?php echo htmlspecialchars($banner['img_banner']); ?>" data-image-width="2846"
                        data-image-height="830" alt="Banner Image">
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <a class="u-absolute-vcenter u-carousel-control u-carousel-control-prev u-text-grey-30 u-block-d434-3"
      href="#carousel-881e" role="button" data-u-slide="prev">
      <span aria-hidden="true">
        <svg class="u-svg-link" viewBox="0 0 477.175 477.175">
          <path d="M145.188,238.575l215.5-215.5c5.3-5.3,5.3-13.8,0-19.1s-13.8-5.3-19.1,0l-225.1,225.1c-5.3,5.3-5.3,13.8,0,19.1l225.1,225
                    c2.6,2.6,6.1,4,9.5,4s6.9-1.3,9.5-4c5.3-5.3,5.3-13.8,0-19.1L145.188,238.575z"></path>
        </svg>
      </span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="u-absolute-vcenter u-carousel-control u-carousel-control-next u-text-grey-30 u-block-d434-4"
      href="#carousel-881e" role="button" data-u-slide="next">
      <span aria-hidden="true">
        <svg class="u-svg-link" viewBox="0 0 477.175 477.175">
          <path
            d="M360.731,229.075l-225.1-225.1c-5.3-5.3-13.8-5.3-19.1,0s-5.3,13.8,0,19.1l215.5,215.5l-215.5,215.5
                    c-5.3,5.3-5.3,13.8,0,19.1c2.6,2.6,6.1,4,9.5,4c3.4,0,6.9-1.3,9.5-4l225.1-225.1C365.931,242.875,365.931,234.275,360.731,229.075z">
          </path>
        </svg>
      </span>
      <span class="sr-only">Next</span>
    </a>
</section>
