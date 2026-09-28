<?php

declare(strict_types=1);

$userAvatar = htmlspecialchars((string) ($_SESSION['avatar'] ?? 'default.png'), ENT_QUOTES, 'UTF-8');
$userName = htmlspecialchars((string) ($_SESSION['username'] ?? 'Utente'), ENT_QUOTES, 'UTF-8');
$roleName = htmlspecialchars((string) ($_SESSION['rolename'] ?? ''), ENT_QUOTES, 'UTF-8');
$damaresVer = htmlspecialchars((string) ($damares_version ?? '1.0'), ENT_QUOTES, 'UTF-8');
?>
  <!-- # Sidenav Left -->
  <div class="offcanvas offcanvas-start" id="affanOffcanvas" data-bs-scroll="true" tabindex="-1"
    aria-labelledby="affanOffcanvsLabel">

    <button class="btn-close btn-close-white text-reset" type="button" data-bs-dismiss="offcanvas"
      aria-label="Close"></button>

    <div class="offcanvas-body p-0">
      <div class="sidenav-wrapper">
        <!-- Sidenav Profile -->
        <div class="sidenav-profile bg-gradient">
          <div class="user-profile">
            <img src="admin/uploads/avatar/<?= $userAvatar ?>" alt="Avatar">
          </div>

          <div class="user-info">
            <h6 class="user-name mb-0"><?= $userName ?></h6>
            <span><?= $roleName ?></span>
          </div>
        </div>

        <!-- Sidenav Nav -->
        <ul class="sidenav-nav ps-0">
          <li>
            <a href="index.php"><i class="ti ti-smart-home"></i> Home</a>
          </li>
          <li>
            <a href="admin"><i class="ti ti-settings"></i> Admin area</a>
          </li>
          <?php if (isset($_SESSION['loggedin'])): ?>
            <li>
              <a href="admin/core/logout.php"><i class="ti ti-logout"></i> Logout</a>
            </li>
          <?php endif; ?>
        </ul>

        <!-- Copyright Info -->
        <div class="copyright-info">
          <p>
            <img src="admin/assets/images/logo/damares_rid.png" alt="Damares"> Damares v.<?= $damaresVer ?>
          </p>
          <p class="pt-4">
            Developed by <a href="https://dmweblab.com/" target="_blank"><img src="admin/assets/images/logo/dmweblab_logo.png" alt="DM WebLab"></a>
          </p>
        </div>
      </div>
    </div>
  </div>