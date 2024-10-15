<style>
  .limited-text {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    /* จำกัดให้แสดงเพียง 3 บรรทัด */
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal;
    /* ให้ข้อความถูกตัดตามปกติ */
    max-height: 4.5em;
    /* กำหนดความสูงของบรรทัด (ประมาณ 3 บรรทัด) */
    line-height: 1.5em;
    /* ความสูงของแต่ละบรรทัด */
  }
</style>

<section class="u-align-center u-clearfix u-container-align-center u-section-12" id="sec-cadf">
  <div class="u-clearfix u-sheet u-sheet-1">
    <h2 class="u-align-center u-text u-text-default u-text-1">ข่าวสารและกจิกรรม</h2>
    <div class="u-expanded-width u-layout-horizontal u-list u-list-1">
      <div class="u-repeater u-repeater-1">
        <div class="u-align-left u-container-align-left u-container-style u-list-item u-repeater-item">
          <div class="u-container-layout u-similar-container u-valign-bottom u-container-layout-1">
            <img class="u-expanded-width u-image u-image-default u-image-1" alt="" data-image-width="800"
              data-image-height="800" src="images/455677786_1060352112762630_7623464069394519342_n.jpg">
            <h4 class="u-align-left u-text u-text-2">สรรพคุณของ MX Protein</h4>
            <p class="u-align-left u-text u-text-3">สรรพคุณของ MX Protein สูตรพืชและไข่ขาวอัลบูแพลนท์
              มีดีที่ไข่ขาวและอะมิโนที่เข้มข้นกว่าพืชหลายชนิด</p>
            <a href="Newsdetails.php"
              class="u-btn u-btn-round u-button-style u-custom-color-7 u-hover-custom-color-3 u-radius u-btn-1">เพิ่มเติม
            </a>
          </div>
        </div>
        <div class="u-align-left u-container-align-left u-container-style u-list-item u-repeater-item">
          <div class="u-container-layout u-similar-container u-valign-bottom u-container-layout-2">
            <img class="u-expanded-width u-image u-image-default u-image-2" alt="" data-image-width="800"
              data-image-height="800" src="images/460421448_1085482346916273_3470836905375370233_n.jpg">
            <h4 class="u-align-left u-text u-text-4">ใจฟูทุกครั้ง ที่ได้ทราบว่า ปีที่ 3 ของ MX Protein&nbsp;</h4>
            <p class="u-align-left u-text u-text-5">เป็นแหล่งโปรตีนที่จำเป็น ในการสร้างอัลบูมินในกระแสเลือด serum</p>
            <a href="Newsdetails.php"
              class="u-btn u-btn-round u-button-style u-custom-color-7 u-hover-custom-color-3 u-radius u-btn-2">เพิ่มเติม
            </a>
          </div>
        </div>
        <div class="u-align-left u-container-align-left u-container-style u-list-item u-repeater-item">
          <div class="u-container-layout u-similar-container u-valign-bottom u-container-layout-3">
            <img class="u-expanded-width u-image u-image-default u-image-3" alt="" data-image-width="800"
              data-image-height="800" src="images/433016247_953325723465270_3048670837084630341_n.jpg">
            <h4 class="u-align-left u-text u-text-6">ประโยชน์ของโปรตีน</h4>
            <p class="u-align-left u-text u-text-7">เป็นแหล่งโปรตีนที่จำเป็น ในการสร้างอัลบูมินในกระแสเลือด serum
              albumin หรือ อัลบูมิน ใน กระแสเลือด</p>
            <a href="Newsdetails.php"
              class="u-btn u-btn-round u-button-style u-custom-color-7 u-hover-custom-color-3 u-radius u-btn-3">เพิ่มเติม
            </a>
          </div>
        </div>
        <div class="u-align-left u-container-align-left u-container-style u-list-item u-repeater-item">
          <div class="u-container-layout u-similar-container u-valign-bottom u-container-layout-4">
            <img class="u-expanded-width u-image u-image-default u-image-4" alt="" data-image-width="800"
              data-image-height="800" src="images/460078040_1085482323582942_2969083708137299112_n.jpg">
            <h4 class="u-align-left u-text u-text-8">ประโยชน์ของโปรตีน</h4>
            <p class="u-align-left u-text u-text-9">เป็นแหล่งโปรตีนที่จำเป็น ในการสร้างอัลบูมินในกระแสเลือด serum
              albumin หรือ อัลบูมิน ใน กระแสเลือด</p>
            <a href="Newsdetails.php"
              class="u-btn u-btn-round u-button-style u-custom-color-7 u-hover-custom-color-3 u-radius u-btn-4">เพิ่มเติม
            </a>
          </div>
        </div>
        <!-- <div class="u-container-layout u-similar-container u-valign-bottom u-container-layout-4">
          <img class="u-expanded-width u-image u-image-default u-image-4" alt=""
            data-image-width="800" data-image-height="800"
            src="images/advert/<?php echo htmlspecialchars($advert['id_advert']); ?>/<?php echo htmlspecialchars($advert['img_advert']); ?>">
          <h4 class="u-align-left u-text u-text-8"><?php echo htmlspecialchars(strip_tags($advert['name_advert'])); ?></h4>
          <p class="u-align-left u-text u-text-9 limited-text">
            <?php echo (htmlspecialchars(strip_tags($advert['detail_advert']))); ?>
          </p>
          <a href="Newsdetails.php" class="u-btn u-btn-round u-button-style u-custom-color-7 u-hover-custom-color-3 u-radius u-btn-4">เพิ่มเติม</a>
        </div> -->

      </div>
      <a class="u-absolute-vcenter u-gallery-nav u-gallery-nav-prev u-grey-70 u-icon-circle u-opacity u-opacity-70 u-spacing-10 u-text-white u-gallery-nav-1"
        href="#" role="button">
        <span aria-hidden="true">
          <svg viewBox="0 0 451.847 451.847">
            <path d="M97.141,225.92c0-8.095,3.091-16.192,9.259-22.366L300.689,9.27c12.359-12.359,32.397-12.359,44.751,0
c12.354,12.354,12.354,32.388,0,44.748L173.525,225.92l171.903,171.909c12.354,12.354,12.354,32.391,0,44.744
c-12.354,12.365-32.386,12.365-44.745,0l-194.29-194.281C100.226,242.115,97.141,234.018,97.141,225.92z"></path>
          </svg>
        </span>
        <span class="sr-only">
          <svg viewBox="0 0 451.847 451.847">
            <path d="M97.141,225.92c0-8.095,3.091-16.192,9.259-22.366L300.689,9.27c12.359-12.359,32.397-12.359,44.751,0
c12.354,12.354,12.354,32.388,0,44.748L173.525,225.92l171.903,171.909c12.354,12.354,12.354,32.391,0,44.744
c-12.354,12.365-32.386,12.365-44.745,0l-194.29-194.281C100.226,242.115,97.141,234.018,97.141,225.92z"></path>
          </svg>
        </span>
      </a>
      <a class="u-absolute-vcenter u-gallery-nav u-gallery-nav-next u-grey-70 u-icon-circle u-opacity u-opacity-70 u-spacing-10 u-text-white u-gallery-nav-2"
        href="#" role="button">
        <span aria-hidden="true">
          <svg viewBox="0 0 451.846 451.847">
            <path d="M345.441,248.292L151.154,442.573c-12.359,12.365-32.397,12.365-44.75,0c-12.354-12.354-12.354-32.391,0-44.744
L278.318,225.92L106.409,54.017c-12.354-12.359-12.354-32.394,0-44.748c12.354-12.359,32.391-12.359,44.75,0l194.287,194.284
c6.177,6.18,9.262,14.271,9.262,22.366C354.708,234.018,351.617,242.115,345.441,248.292z"></path>
          </svg>
        </span>
        <span class="sr-only">
          <svg viewBox="0 0 451.846 451.847">
            <path d="M345.441,248.292L151.154,442.573c-12.359,12.365-32.397,12.365-44.75,0c-12.354-12.354-12.354-32.391,0-44.744
L278.318,225.92L106.409,54.017c-12.354-12.359-12.354-32.394,0-44.748c12.354-12.359,32.391-12.359,44.75,0l194.287,194.284
c6.177,6.18,9.262,14.271,9.262,22.366C354.708,234.018,351.617,242.115,345.441,248.292z"></path>
          </svg>
        </span>
      </a>
    </div>
  </div>
</section>