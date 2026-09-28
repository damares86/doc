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

$page_name = 'contact';

$doc->table = 'doc_contact_reason';
$stmtReasons = $doc->showAll('id');
$reasons = $stmtReasons ? $stmtReasons->fetchAll(PDO::FETCH_ASSOC) : [];
?>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<div class="page-content-wrapper">
  <div>
    <div class="pt-3"></div>

    <div class="container py-3">
      <form action="admin/core/mngContact.php" method="post" enctype="multipart/form-data" id="contactForm">
        <h4 class="text-center mb-4">Richieste studio medico</h4>

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
          <label class="form-label">Richiesta <span class="text-danger">*</span></label>
          <select class="form-select form-control-clicked" id="defaultSelect" name="reason_id" required>
            <option value="" selected disabled>---</option>
            <?php foreach ($reasons as $reason): ?>
              <option value="<?= (int) $reason['id'] ?>"><?= htmlspecialchars((string) $reason['name'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Allegato -->
        <div class="mb-3">
          <label class="form-label">
            Allegati <br>
            <small class="text-muted">Dimensione massima 10MB.<br>
              Formati consentiti: PDF, JPG, JPEG, PNG, WEBP, HEIC, HEIF.</small>
          </label>
          <input class="form-control" type="file" id="formFile" name="attachment" />
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

        <div class="mb-3">
          <div class="g-recaptcha" data-sitekey="6Lf5qX4tAAAAADpd_njyg0LyvcmbvqPFP0TCotiq"></div>
          <div id="recaptchaError" style="display:none; color:#c00; margin-top:8px;">
            Per inviare il modulo, completa la verifica "Non sono un robot".
          </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">
          Invia richiesta
        </button>
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

      <div class="pb-3"></div>
    </div>
  </div>
</div>

<?php
require_once __DIR__ . '/inc/nav.php';
require_once __DIR__ . '/inc/footer.php';