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
$doc->weekday = $weekday;
$doc->table = 'doc_timetable';
$stmt = $doc->showAllWhere('id', ['weekday']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
extract($row);

$free = ($row['booking'] == 0);

$start_time = date('H:i', strtotime($row['start_time']));
$end_time = date('H:i', strtotime($row['end_time']));
$slots = $doc->generateSlots($start_time, $end_time);


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

    <div class="container py-3">

      <!-- Navigazione giorni -->
      <div class="d-flex justify-content-between align-items-center mb-3">

        <button type="button"
          class="btn btn-outline-primary"
          id="prevDay">
          ←
        </button>

        <h5 class="mb-0" id="currentDate"></h5>

        <button type="button"
          class="btn btn-outline-primary"
          id="nextDay">
          →
        </button>

      </div>

      <!-- Lista slot -->
      <div id="slots"></div>

      <div class="pb-3"></div>
    </div>


    <script src="assets/js/booking.js"></script>


    <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

          <form action="admin/core/mngBooking.php" method="post">

            <div class="modal-header">
              <h5 class="modal-title">Prenota appuntamento</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
            </div>

            <div class="modal-body">

              <p class="mb-3">
                <strong id="bookingDateLabel"></strong>
              </p>

              <!-- Campi nascosti -->
              <input type="hidden" name="date" id="bookingDate">
              <input type="hidden" name="time" id="bookingTime">
              <input type="hidden" name="operation" value="booking">

              <!-- Nome -->
              <div class="mb-3">
                <label class="form-label">Nome e cognome <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required>
              </div>

              <!-- Email -->
              <div class="mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" required>
              </div>

              <!-- Telefono -->
              <div class="mb-3">
                <label class="form-label">Telefono <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control" required>
              </div>
              <!-- Motivazione -->
              <div class="mb-3">
                <label class="form-label">Motivo della visita <span class="text-danger">*</span></label>
                <select class="form-select form-control-clicked" id="defaultSelect" name="reason_id" aria-label="Default select example" required>
                  <option value="" selected disabled>---</option>
                  <?php
                  $doc->table = "doc_reason";
                  $stmt = $doc->showAll('id');
                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                  ?>
                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                  <?php
                  }
                  ?>
                </select>
              </div>

              <!-- Note -->
              <div class="mb-3">
                <label class="form-label">Eventuali note</label>
                <textarea name="notes" id="notes" class="form-control" rows="3 maxlength=" 140"></textarea>
                <small id="notesCounter" class="text-muted">
                  0 / 140 caratteri
                </small>
              </div>

              <script>
                const textarea = document.getElementById('notes');
                const counter = document.getElementById('notesCounter');
                const maxLength = textarea.maxLength;

                textarea.addEventListener('input', function() {
                  counter.textContent =
                    `${this.value.length} / ${maxLength} caratteri`;
                });
              </script>
            </div>

            <div class="modal-footer">
              <button type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal">
                Annulla
              </button>

              <button type="submit" class="btn btn-primary">
                Prenota
              </button>
            </div>

          </form>

        </div>
      </div>
    </div>
    <?php
    require 'inc/nav.php';
    require 'inc/footer.php';
    ?>