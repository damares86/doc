<?php
require 'inc/header.php';
require 'inc/sidebar.php';

$page_name = 'index';

?>

<div class="page-content-wrapper">

  <div>

    <div class="tiny-slider-one-wrapper" dir="ltr">
      <div class="tiny-slider-one">
        <!-- Single Hero Slide -->
        <div>
          <div class="single-hero-slide bg-overlay" style="background-image: url('assets/img/bg_visual.png'); background-position:center center">
            <div class="h-100 d-flex align-items-center text-center">
              <div class="container">
                <h3 class="text-white mb-1">Dottoressa Crosetto Maria Chiara </h3>
                <p class="text-white">Giaveno (TO)</p>
                <!-- <a class="btn btn-creative btn-warning" href="#">Get Started <i class="ti ti-arrow-right"></i></a> -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="pt-3"></div>

      <div id="home" class="container">

        <div class="card bg-info  mb-3">
          <div class="card-body text-white text-center p-5">
            <h2 class="mb-4 text-white">Orari e modalità accesso ambulatorio</h2>
            <ul>
              <li class="mb-2"><b>Lunedì:</b> 8-11 (accesso libero)</li>
              <li class="mb-2"><b>Martedì:</b> 8-11 (appuntamento)</li>
              <li class="mb-2"><b>Mercoledì:</b> 8-11 (appuntamento)</li>
              <li class="mb-2"><b>Giovedì:</b> 8-11 (appuntamento)</li>
              <li class="mb-2"><b>Venerdì:</b> 8-11 (appuntamento)</li>
            </ul>
          </div>
        </div>
      <a href="rubrica.php">
        <div class="card service-card bg-danger bg-gradient mb-3">
          <div class="card-body">
            <div class="d-flex gap-3 align-items-center justify-content-between">
              <div class="service-text">
                <h5>Prenotazione visita</h5>
                <!--<p class="mb-0">The write less, do more with JavaScript Library.</p>-->
              </div>
              <div class="service-img">
                <i class="ti ti-calendar text-white"></i>
              </div>
            </div>
          </div>
        </div>
      </a>

      <a href="portal/manual.php?prod=1">
        <div class="card service-card bg-success bg-gradient mb-3">
          <div class="card-body">
            <div class="d-flex gap-3 align-items-center justify-content-between">
              <div class="service-text">
                <h5>Informazioni utili</h5>
              </div>
              <div class="service-img">
                <i class="ti ti-info-square-rounded text-white"></i>
              </div>
            </div>
          </div>
        </div>
      </a>


      <div class="pb-3"></div>
    </div>

    <?php
    require 'inc/nav.php';
    require 'inc/footer.php';
    ?>