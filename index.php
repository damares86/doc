<?php

declare(strict_types=1);

require_once __DIR__ . '/inc/header.php';
require_once __DIR__ . '/inc/sidebar.php';

if (!isset($doc) || !($doc instanceof Doc)) {
    $doc = new Doc($db);
    if (!empty($prefix)) {
        $doc->prx = $prefix . '_';
    }
}

$page_name = 'index';

$doc->table = 'doc_messages';
$stmtMsg = $doc->showAll('id');
$rowMsg = $stmtMsg ? $stmtMsg->fetch(PDO::FETCH_ASSOC) : null;
$today = date('Y-m-d');
$hasActiveNotice = false;

if ($rowMsg && !empty($rowMsg['msg']) && !empty($rowMsg['start_time']) && !empty($rowMsg['end_time'])) {
    if ($today >= $rowMsg['start_time'] && $today <= $rowMsg['end_time']) {
        $hasActiveNotice = true;
    }
}

$days = [
    1 => 'Lunedì',
    2 => 'Martedì',
    3 => 'Mercoledì',
    4 => 'Giovedì',
    5 => 'Venerdì',
];

$doc->table = 'doc_timetable';
$timeStmt = $doc->showAll('weekday');
$timetableList = $timeStmt ? $timeStmt->fetchAll(PDO::FETCH_ASSOC) : [];
?>

<div class="page-content-wrapper">
  <div>
    <div class="tiny-slider-one-wrapper" dir="ltr">
      <div class="tiny-slider-one">
        <div>
          <div class="single-hero-slide bg-overlay" style="background-image: url('assets/img/bg_visual.png'); background-position:center center">
            <div class="h-100 d-flex align-items-center text-center">
              <div class="container">
                <h2 class="text-white mb-1">Dottoressa Crosetto Maria Chiara</h2>
                <h5 class="text-white">Via Stazione 2, Giaveno (TO)</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="pt-3"></div>

    <div id="home" class="container">
      <?php require_once __DIR__ . '/inc/alert.php'; ?>

      <div class="card bg-info mb-3">
        <div class="card-body text-white text-center p-3">
          <h2 class="mb-3 text-white">Orari e modalità accesso ambulatorio</h2>

          <?php if ($hasActiveNotice && $rowMsg): ?>
            <div class="card bg-warning text-dark mb-3 p-3">
              <h5 class="text-dark">Avviso</h5>
              <div>
                <?= (string) $rowMsg['msg'] ?>
              </div>
            </div>
          <?php endif; ?>

          <ul class="list-unstyled mb-0">
            <?php foreach ($timetableList as $timeRow): ?>
              <?php
              $weekdayNum = (int) ($timeRow['weekday'] ?? 1);
              $dayLabel = $days[$weekdayNum] ?? "Giorno {$weekdayNum}";
              $startStr = !empty($timeRow['start_time']) ? date('H:i', (int) strtotime((string) $timeRow['start_time'])) : '';
              $endStr = !empty($timeRow['end_time']) ? date('H:i', (int) strtotime((string) $timeRow['end_time'])) : '';
              $access = ((int) ($timeRow['booking'] ?? 1) === 1) ? 'Prenotazione' : 'Accesso libero';
              ?>
              <li class="mb-2">
                <b><?= htmlspecialchars($dayLabel, ENT_QUOTES, 'UTF-8') ?>:</b> <?= htmlspecialchars($startStr, ENT_QUOTES, 'UTF-8') ?> - <?= htmlspecialchars($endStr, ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($access, ENT_QUOTES, 'UTF-8') ?>)
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>

      <a href="booking.php" class="text-decoration-none">
        <div class="card service-card bg-danger bg-gradient mb-3">
          <div class="card-body">
            <div class="d-flex gap-3 align-items-center justify-content-between">
              <div class="service-text">
                <h5 class="text-white mb-0">Prenotazione visita</h5>
              </div>
              <div class="service-img">
                <i class="ti ti-calendar text-white" style="font-size: 1.8rem;"></i>
              </div>
            </div>
          </div>
        </div>
      </a>

      <a href="info.php" class="text-decoration-none">
        <div class="card service-card bg-success bg-gradient mb-3">
          <div class="card-body">
            <div class="d-flex gap-3 align-items-center justify-content-between">
              <div class="service-text">
                <h5 class="text-white mb-0">Informazioni utili</h5>
              </div>
              <div class="service-img">
                <i class="ti ti-info-square-rounded text-white" style="font-size: 1.8rem;"></i>
              </div>
            </div>
          </div>
        </div>
      </a>

      <div class="pb-3"></div>
    </div>
  </div>
</div>

<?php
require_once __DIR__ . '/inc/nav.php';
require_once __DIR__ . '/inc/footer.php';