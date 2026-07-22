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
                <h2 class="text-white mb-1">Dottoressa Crosetto Maria Chiara </h2>
                <h5 class="text-white">Via Stazione 2, Giaveno (TO)</h5>
                <!-- <h5 class="text-white">3450579662</h5> -->
                <!-- <a class="btn btn-creative btn-warning" href="#">Get Started <i class="ti ti-arrow-right"></i></a> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="pt-3"></div>

    <div id="home" class="container">
    <?php
      require "inc/alert.php";
    ?>
      <div class="card bg-info mb-3">
        <div class="card-body text-white text-center p-2">
          <h2 class="mb-3 text-white">Orari e modalità accesso ambulatorio</h2>
<!--           <div class="card bg-warning mb-3">
            Attenzione
          </div> -->
          <ul>
            <?php
            // legenda per i giorni
            $days = [
              1 => 'Lunedì',
              2 => 'Martedì',
              3 => 'Mercoledì',
              4 => 'Giovedì',
              5 => 'Venerdì'
            ];

            $doc->table = 'doc_timetable';
            $time_stmt = $doc->showAll('id');
            while ($time_row = $time_stmt->fetch(PDO::FETCH_ASSOC)) {

              $access = '';
              $time_row['booking'] == 1 ? $access = 'Prenotazione' : $access = 'Accesso libero';
            ?>
              <li class="mb-2"><b><?= $days[$time_row['weekday']] ?>:</b> <?= date('H:i', strtotime($time_row['start_time'])) ?> - <?= date('H:i', strtotime($time_row['end_time'])) ?> (<?= $access ?>)</li>
            <?php
            }
            ?>
          </ul>
        </div>
      </div>
      <a href="booking.php">
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

      <a href="info.php">
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