<?php
$selectedDate = $_GET['date'] ?? date('Y-m-d');

if (date('N', strtotime($selectedDate)) > 5) {
  $selectedDate = date('Y-m-d');
}

$days = [
  1 => 'Lunedì',
  2 => 'Martedì',
  3 => 'Mercoledì',
  4 => 'Giovedì',
  5 => 'Venerdì',
  6 => 'Sabato',
  7 => 'Domenica'
];

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
            <a href="index.php"><?= $common_dashboard ?></a>
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
          <?php

          $today = date('Y-m-d');

          $prevDate = date('Y-m-d', strtotime($selectedDate . ' -1 weekday'));
          $nextDate = date('Y-m-d', strtotime($selectedDate . ' +1 weekday'));

          $maxDate = date('Y-m-d', strtotime('+10 days'));

          ?>

          <div class="d-flex justify-content-between align-items-center mb-4">

            <?php if ($selectedDate > $today): ?>
              <a href="?p=allBooking&module=bookings&date=<?= $prevDate ?>"
                class="btn btn-outline-primary">
                ←
              </a>
            <?php else: ?>
              <span></span>
            <?php endif; ?>
            <?php
            $weekday = date('N', strtotime($selectedDate));
            ?>
            <h4 class="mb-0">
              <?php
              echo $days[$weekday] . ' ' . date('d/m/Y', strtotime($selectedDate));
              ?>
            </h4>

            <?php if ($selectedDate < $maxDate): ?>
              <a href="?p=allBooking&module=bookings&date=<?= $nextDate ?>"
                class="btn btn-outline-primary">
                →
              </a>
            <?php else: ?>
              <span></span>
            <?php endif; ?>
            <?php

            $stmt = $db->prepare("
              SELECT *
              FROM doc_bookings
              WHERE DATE(slot_booked) = ?
              ORDER BY slot_booked ASC
               ");

            $stmt->execute([$selectedDate]);

            $bookings = $stmt->fetchAll();
            ?>
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
                        <?= date('H:i', strtotime($booking['slot_booked'])); ?>
                      </strong>
                    </div>
                    <div class="col-9">
                      <div class="row">
                        <div class="col-7 pb-3">
                          <span>
                            <strong>Paziente:</strong> <?= htmlspecialchars($booking['name']); ?><br>
                            <small><strong>Telefono: </strong>
                              <?= htmlspecialchars($booking['phone']); ?>
                              <br>
                              <strong>Email:</strong>
                              <?= htmlspecialchars($booking['email']); ?>
                            </small>
                          </span>

                        </div>
                        <div class="col-5 pb-3">
                          <?php
                          $doc->table = "doc_reason";
                          $doc->id = $booking['reason_id'];
                          $stmt = $doc->showAllWhere('id', ['id']);
                          $row = $stmt->fetch(PDO::FETCH_ASSOC);
                          extract($row);
                          ?>
                          <strong>Motivazione:</strong> <?= $row['name'] ?>
                        </div>
                        <?php if (!empty($booking['notes'])): ?>
                          <div class="col-12 border-top pt-3">
                            <strong>Note aggiuntive:</strong><br> <small>
                              <?= nl2br(htmlspecialchars($booking['notes'])); ?>
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