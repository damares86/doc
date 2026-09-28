<?php

declare(strict_types=1);

if (!isset($doc) || !($doc instanceof Doc)) {
    $doc = new Doc($db);
    if (!empty($prefix)) {
        $doc->prx = $prefix . '_';
    }
}

$selectedDate = (string) ($_GET['date'] ?? date('Y-m-d'));
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
    $selectedDate = date('Y-m-d');
}

if ((int) date('N', (int) strtotime($selectedDate)) > 5) {
    $selectedDate = date('Y-m-d');
}

$days = [
    1 => 'Lunedì',
    2 => 'Martedì',
    3 => 'Mercoledì',
    4 => 'Giovedì',
    5 => 'Venerdì',
    6 => 'Sabato',
    7 => 'Domenica',
];

$prx = !empty($prefix) ? $prefix . '_' : '';
$today = date('Y-m-d');
$prevDate = date('Y-m-d', (int) strtotime($selectedDate . ' -1 weekday'));
$nextDate = date('Y-m-d', (int) strtotime($selectedDate . ' +1 weekday'));
$maxDate = date('Y-m-d', (int) strtotime('+10 days'));
$weekday = (int) date('N', (int) strtotime($selectedDate));

$stmt = $db->prepare("
    SELECT b.*, r.name AS reason_name
    FROM {$prx}doc_bookings b
    LEFT JOIN {$prx}doc_reason r ON b.reason_id = r.id
    WHERE DATE(b.slot_booked) = ?
    ORDER BY b.slot_booked ASC
");
$stmt->execute([$selectedDate]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
?>

<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Visualizzazione prenotazioni</h3>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.php"><?= htmlspecialchars((string) ($common_dashboard ?? 'Dashboard'), ENT_QUOTES, 'UTF-8') ?></a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            Visualizzazione prenotazioni
          </li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<br>

<!-- Basic Tables start -->
<section class="section">
  <div class="card shadow">
    <div class="card-header">
      <h3>Prenotazioni</h3>
    </div>
    <div class="card-body">
      <div class="row">

        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <?php if ($selectedDate > $today): ?>
              <a href="?p=allBooking&date=<?= htmlspecialchars($prevDate, ENT_QUOTES, 'UTF-8') ?>"
                class="btn btn-outline-primary">
                ←
              </a>
            <?php else: ?>
              <span></span>
            <?php endif; ?>

            <h4 class="mb-0">
              <?= htmlspecialchars(($days[$weekday] ?? '') . ' ' . date('d/m/Y', (int) strtotime($selectedDate)), ENT_QUOTES, 'UTF-8') ?>
            </h4>

            <?php if ($selectedDate < $maxDate): ?>
              <a href="?p=allBooking&date=<?= htmlspecialchars($nextDate, ENT_QUOTES, 'UTF-8') ?>"
                class="btn btn-outline-primary">
                →
              </a>
            <?php else: ?>
              <span></span>
            <?php endif; ?>
          </div>
        </div>

        <div class="col-12">
          <?php if (empty($bookings)): ?>
            <div class="alert alert-warning">
              Nessuna prenotazione per questa data.
            </div>
          <?php else: ?>
            <div class="list-group">
              <?php foreach ($bookings as $booking): ?>
                <div class="list-group-item">
                  <div class="row">
                    <div class="col-3">
                      <strong>
                        <?= htmlspecialchars(date('H:i', (int) strtotime((string) $booking['slot_booked'])), ENT_QUOTES, 'UTF-8') ?>
                      </strong>
                    </div>
                    <div class="col-9">
                      <div class="row">
                        <div class="col-7 pb-3">
                          <span>
                            <strong>Paziente:</strong> <?= htmlspecialchars((string) ($booking['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?><br>
                            <small><strong>Telefono: </strong>
                              <?= htmlspecialchars((string) ($booking['phone'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                              <br>
                              <strong>Email:</strong>
                              <?= htmlspecialchars((string) ($booking['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                            </small>
                          </span>
                        </div>
                        <div class="col-5 pb-3">
                          <strong>Motivazione:</strong> <?= htmlspecialchars((string) ($booking['reason_name'] ?? '-'), ENT_QUOTES, 'UTF-8') ?>
                        </div>
                        <?php if (!empty($booking['notes'])): ?>
                          <div class="col-12 border-top pt-3">
                            <strong>Note aggiuntive:</strong><br> <small>
                              <?= nl2br(htmlspecialchars((string) $booking['notes'], ENT_QUOTES, 'UTF-8')) ?>
                            </small>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>