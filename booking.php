<?php

declare(strict_types=1);

require_once __DIR__ . '/inc/header.php';
require_once __DIR__ . '/inc/sidebar.php';

$page_name = 'booking';

$date = (string) ($_GET['date'] ?? date('Y-m-d'));
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    $date = date('Y-m-d');
}

$weekday = (int) date('N', (int) strtotime($date));
if ($weekday >= 6) {
    $date = date('Y-m-d', (int) strtotime('next monday', (int) strtotime($date)));
}

$today = date('Y-m-d');
$maxDate = date('Y-m-d', (int) strtotime('+10 days'));

$weekday = (int) date('N', (int) strtotime($date));
$doc->weekday = $weekday;
$doc->table = 'doc_timetable';
$stmt = $doc->showAllWhere('id', ['weekday']);
$row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;

$free = false;
if ($row) {
    $free = ((int) ($row['booking'] ?? 1) === 0);
}

$doc->table = 'doc_reason';
$stmtReasons = $doc->showAll('id');
$reasons = $stmtReasons ? $stmtReasons->fetchAll(PDO::FETCH_ASSOC) : [];
?>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<div class="page-content-wrapper">
  <div>
    <div class="pt-3"></div>

    <div class="container py-3">
      <!-- Navigazione giorni -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <button type="button" class="btn btn-outline-primary" id="prevDay">
          ←
        </button>

        <h5 class="mb-0" id="currentDate"></h5>

        <button type="button" class="btn btn-outline-primary" id="nextDay">
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
          <form action="admin/core/mngBooking.php" method="post" id="contactForm">
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
                <select class="form-select form-control-clicked" id="defaultSelect" name="reason_id" required>
                  <option value="" selected disabled>---</option>
                  <?php foreach ($reasons as $reason): ?>
                    <option value="<?= (int) $reason['id'] ?>"><?= htmlspecialchars((string) $reason['name'], ENT_QUOTES, 'UTF-8') ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Note -->
              <div class="mb-3">
                <label class="form-label">Eventuali note</label>
                <textarea name="notes" id="notes" class="form-control" rows="3" maxlength="140"></textarea>
                <small id="notesCounter" class="text-muted">
                  0 / 140 caratteri
                </small>
              </div>

              <script>
                const textarea = document.getElementById('notes');
                const counter = document.getElementById('notesCounter');
                if (textarea && counter) {
                  const maxLength = textarea.maxLength;
                  textarea.addEventListener('input', function() {
                    counter.textContent = `${this.value.length} / ${maxLength} caratteri`;
                  });
                }
              </script>
            </div>

            <div class="px-3">
              <div class="g-recaptcha" data-sitekey="6Lf5qX4tAAAAADpd_njyg0LyvcmbvqPFP0TCotiq"></div>
              <div id="recaptchaError" style="display:none; color:#c00; margin-top:8px;">
                Per inviare il modulo, completa la verifica "Non sono un robot".
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Annulla
              </button>
              <button type="submit" class="btn btn-primary">
                Prenota
              </button>
            </div>
          </form>

          <script>
            const cForm = document.getElementById('contactForm');
            if (cForm) {
              cForm.addEventListener('submit', function(event) {
                const response = grecaptcha.getResponse();
                const error = document.getElementById('recaptchaError');
                if (response.length === 0) {
                  event.preventDefault();
                  if (error) error.style.display = 'block';
                  return false;
                }
                if (error) error.style.display = 'none';
              });
            }
          </script>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
require_once __DIR__ . '/inc/nav.php';
require_once __DIR__ . '/inc/footer.php';