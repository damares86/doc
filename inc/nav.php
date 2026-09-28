<?php

declare(strict_types=1);

$currentPage = $page_name ?? 'index';
?>
<!-- Footer Nav -->
<div class="footer-nav-area" id="footerNav">
  <div class="container px-0">
    <div class="footer-nav position-relative">
      <ul class="h-100 d-flex align-items-center justify-content-between ps-0">
        <li <?= $currentPage === 'index' ? 'class="active"' : '' ?>>
          <a href="index.php">
            <i class="ti ti-home"></i>
            <span <?= $currentPage === 'index' ? 'class="active"' : '' ?>>Home</span>
          </a>
        </li>

        <li <?= $currentPage === 'booking' ? 'class="active"' : '' ?>>
          <a href="booking.php">
            <i class="ti ti-calendar"></i>
            <span <?= $currentPage === 'booking' ? 'class="active"' : '' ?>>Prenotazione</span>
          </a>
        </li>

        <li <?= $currentPage === 'info' ? 'class="active"' : '' ?>>
          <a href="info.php">
            <i class="ti ti-info-square-rounded"></i>
            <span <?= $currentPage === 'info' ? 'class="active"' : '' ?>>Informazioni</span>
          </a>
        </li>

        <li <?= $currentPage === 'contact' ? 'class="active"' : '' ?>>
          <a href="contact.php">
            <i class="ti ti-pencil"></i>
            <span <?= $currentPage === 'contact' ? 'class="active"' : '' ?>>Richieste</span>
          </a>
        </li>

        <?php if (!empty($adminUser)): ?>
          <li>
            <a href="admin">
              <i class="ti ti-settings"></i>
              <span>Admin area</span>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>