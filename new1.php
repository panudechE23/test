<?php
include 'db_connect.php'; // เชื่อมต่อฐานข้อมูล

// ดึงข้อมูล advert ทั้งหมดจากตาราง
$sql = "SELECT * FROM advert";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($stmt->rowCount() > 0) {
    $adverts = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูล advert ทั้งหมด
} else {
    echo "ไม่พบข้อมูล advert";
    exit;
}
?>
<style>
.limited-text {
    display: -webkit-box;
    -webkit-line-clamp: 3; /* จำกัดให้แสดงเพียง 3 บรรทัด */
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal; /* ให้ข้อความถูกตัดตามปกติ */
    max-height: 4.5em; /* กำหนดความสูงของบรรทัด (ประมาณ 3 บรรทัด) */
    line-height: 1.5em; /* ความสูงของแต่ละบรรทัด */
}
</style>

<section class="u-align-center u-clearfix u-container-align-center u-section-12" id="sec-cadf">
  <div class="u-clearfix u-sheet u-sheet-1">
    <h2 class="u-align-center u-text u-text-default u-text-1">ข่าวสารและกิจกรรม</h2>
    <div class="u-expanded-width u-layout-horizontal u-list u-list-1">
      <div class="u-repeater u-repeater-1">
        <?php foreach ($adverts as $advert): ?>
          <div class="u-align-left u-container-align-left u-container-style u-list-item u-repeater-item">
            <div class="u-container-layout u-similar-container u-valign-bottom u-container-layout-1">
              <img class="u-expanded-width u-image u-image-default u-image-1" alt="<?php echo htmlspecialchars($advert['name_advert']); ?>"
                data-image-width="800" data-image-height="800"
                src="images/advert/<?php echo htmlspecialchars($advert['id_advert']); ?>/<?php echo htmlspecialchars($advert['img_advert']); ?>">
              <h4 class="u-align-left u-text u-text-2"><?php echo htmlspecialchars(strip_tags($advert['name_advert'])); ?></h4>
              <p class="u-align-left u-text u-text-3 limited-text">
                <?php echo htmlspecialchars(strip_tags($advert['detail_advert'])); ?>
              </p>
              <a href="Newsdetails.php?id_advert=<?php echo htmlspecialchars($advert['id_advert']); ?>"
                class="u-btn u-btn-round u-button-style u-custom-color-7 u-hover-custom-color-3 u-radius u-btn-1">เพิ่มเติม</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- ปุ่มเลื่อน -->
      <a class="u-absolute-vcenter u-gallery-nav u-gallery-nav-prev u-grey-70 u-icon-circle u-opacity u-opacity-70 u-spacing-10 u-text-white u-gallery-nav-1"
        href="#" role="button">
        <span aria-hidden="true">
          <svg viewBox="0 0 451.847 451.847">
            <path d="M97.141,225.92c0-8.095,3.091-16.192,9.259-22.366L300.689,9.27c12.359-12.359,32.397-12.359,44.751,0
          c12.354,12.354,12.354,32.388,0,44.748L173.525,225.92l171.903,171.909c12.354,12.354,12.354,32.391,0,44.744
          c-12.354,12.365-32.386,12.365-44.745,0l-194.29-194.281C100.226,242.115,97.141,234.018,97.141,225.92z"></path>
          </svg>
        </span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="u-absolute-vcenter u-gallery-nav u-gallery-nav-next u-grey-70 u-icon-circle u-opacity u-opacity-70 u-spacing-10 u-text-white u-gallery-nav-2"
        href="#" role="button">
        <span aria-hidden="true">
          <svg viewBox="0 0 451.846 451.847">
            <path d="M345.441,248.292L151.154,442.573c-12.359,12.365-32.397,12.365-44.75,0c-12.354-12.354-12.354-32.391,0-44.744
          L278.318,225.92L106.409,54.017c-12.354-12.359-12.354-32.394,0-44.748c12.354-12.359,32.391-12.359,44.75,0
          l194.287,194.284c6.177,6.18,9.262,14.271,9.262,22.366C354.708,234.018,351.617,242.115,345.441,248.292z"></path>
          </svg>
        </span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </div>
</section>
