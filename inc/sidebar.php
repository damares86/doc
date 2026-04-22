
  <!-- # Sidenav Left -->
  <div class="offcanvas offcanvas-start" id="affanOffcanvas" data-bs-scroll="true" tabindex="-1"
    aria-labelledby="affanOffcanvsLabel">

    <button class="btn-close btn-close-white text-reset" type="button" data-bs-dismiss="offcanvas"
      aria-label="Close"></button>

    <div class="offcanvas-body p-0">
      <div class="sidenav-wrapper">
        <!-- Sidenav Profile -->
        <div class="sidenav-profile bg-gradient">

          <!-- User Thumbnail -->
          <div class="user-profile">
            <img src="admin/uploads/avatar/<?= $_SESSION['avatar'] ?>" alt="">
          </div>

          <!-- User Info -->
          <div class="user-info">
            <h6 class="user-name mb-0"><?= $_SESSION['username'] ?></h6>
            <span><?= $_SESSION['rolename'] ?></span>
          </div>
        </div>

        <!-- Sidenav Nav -->
        <ul class="sidenav-nav ps-0">
          <li>
            <a href="index.php"><i class="ti ti-smart-home"></i> Home</a>
          </li>
          <li>
            <a href="rubrica.php"><i class="ti ti-address-book"></i> Rubrica
            </a>
          </li>
          <li>
            <a href="admin"><i class="ti ti-settings"></i> Admin area
            </a>
          </li>
          <li>
            <a href="admin/core/logout.php"><i class="ti ti-logout"></i> Logout</a>
          </li>
        </ul>


          <!-- Copyright Info -->
          <div class="copyright-info">
            <p>
              <img src="admin/assets/images/logo/damares_rid.png"> damares v.<?= $damares_version?>
            </p>
            <p class="pt-4">
              <!--<span id="copyrightYear"></span>-->
              Developed by <a href="https://dmweblab.com/" target="_blank"><img src="admin/assets/images/logo/dmweblab_logo.png"></a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>