<?php
require 'inc/header.php';
require 'inc/sidebar.php';

$page_name = 'index';

?>

<div class="page-content-wrapper">

  <div>


    <div class="pt-3"></div>

    <div id="home" class="container">
      <a href="rubrica.php">
        <div class="card service-card bg-danger bg-gradient mb-3">
          <div class="card-body">
            <div class="d-flex gap-3 align-items-center justify-content-between">
              <div class="service-text">
                <h5>Rubrica</h5>
                <!--<p class="mb-0">The write less, do more with JavaScript Library.</p>-->
              </div>
              <div class="service-img">
                <i class="ti ti-address-book text-white"></i>
              </div>
            </div>
          </div>
        </div>
      </a>

      <a href="https://www.notion.so/2e8ed6d2a8bc80429267f65c2abf8188?v=2e8ed6d2a8bc80909f23000c97b35301" target="_blank">
        <div class="card service-card bg-dark bg-gradient mb-3">
          <div class="card-body">
            <div class="d-flex gap-3 align-items-center justify-content-between">
              <div class="service-text">
                <h5>Manutenzioni</h5>
                <!--<p class="mb-0">The write less, do more with JavaScript Library.</p>-->
              </div>
              <div class="service-img">
                <img src="assets/img/notion.png">
              </div>
            </div>
          </div>
        </div>
      </a>

      <?php
      if ($_SESSION['role_id'] < 4) {
      ?>
        <a href="portal/manual.php?prod=1">
          <div class="card service-card bg-info bg-gradient mb-3">
            <div class="card-body">
              <div class="d-flex gap-3 align-items-center justify-content-between">
                <div class="service-text">
                  <h5>Guida</h5>
                </div>
                <div class="service-img">
                  <i class="ti ti-info-square-rounded text-white"></i>
                </div>
              </div>
            </div>
          </div>
        </a>
      <?php
      }
      ?>

      <div class="pb-3"></div>
    </div>

    <?php
    require 'inc/nav.php';
    require 'inc/footer.php';
    ?>