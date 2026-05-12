<?php
require 'inc/header.php';
require 'inc/sidebar.php';

$page_name = 'booking';

$date = $_GET['date'] ?? date('d-m-Y');
$today = date('d-m-Y');
// massimo 10 giorni avanti
$maxDate = date('d-m-Y', strtotime('+10 days'));

// gestione frecce
$canGoBack = ($date > $today);
$canGoForward = ($date < $maxDate);

$prev = date('Y-m-d', strtotime('-1 day', strtotime($date)));
$next = date('Y-m-d', strtotime('+1 day', strtotime($date)));

// giorno della settimana
$weekday = date('N', strtotime($date));
$doc->weekday = $weekday ;
$doc->table = 'doc_timetable';
$stmt = $doc->showAllWhere('id',['weekday']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
extract($row);
$start_time = date('H:i', strtotime($row['start_time'])) ;
$end_time = date('H:i', strtotime($row['end_time'])) ;
$slots = $doc->generateSlots($start_time,$end_time);

?>

<div class="page-content-wrapper">

  <div>

    <!--     <div class="tiny-slider-one-wrapper" dir="ltr">
      <div class="tiny-slider-one">
        <div>
          <div class="single-hero-slide bg-overlay" style="background-image: url('assets/img/bg_visual.png'); background-position:center center">
            <div class="h-100 d-flex align-items-center text-center">
              <div class="container">
                <h3 class="text-white mb-1">Dottoressa Crosetto Maria Chiara </h3>
                <p class="text-white">Giaveno (TO)</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> -->
    <div class="pt-3"></div>

    <div id="home" class="container">
    <?php
    print_r($slots);
    ?>
      <div class="card booking mb-3">
        <div class="card-body text-center p-5">
          <h2 class="mb-4">Prenotazioni disponibili</h2>
          <div class="d-flex justify-content-between align-items-center mb-3">

            <a href="?date=<?= $prev ?>" class="btn btn-outline-primary <?= !$canGoBack ? 'disabled' : '' ?>">
              ←
            </a>

            <h5 class="mb-0">
              <?= date('d M Y', strtotime($date)) ?>
            </h5>

            <a href="?date=<?= $next ?>" class="btn btn-outline-primary <?= !$canGoForward ? 'disabled' : '' ?>">
              →
            </a>

          </div>
        </div>
      </div>

      <div class="pb-3"></div>
    </div>

    <?php
    require 'inc/nav.php';
    require 'inc/footer.php';
    ?>