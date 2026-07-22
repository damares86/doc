<?php
require 'inc/header.php';
require 'inc/sidebar.php';

$page_name = 'contact';

?>

<script src="https://www.google.com/recaptcha/api.js?render=6LetcWAtAAAAAFEFd9BGjolsSFagETtkK_TXhR7y"> </script>

<script>
  grecaptcha.ready(function() {
    grecaptcha.execute('6LetcWAtAAAAAFEFd9BGjolsSFagETtkK_TXhR7y', {
      action: 'submit'
    }).then(function(token) {
      var recaptchaResponse = document.getElementById('recaptchaResponse');
      recaptchaResponse.value = token;
    });
  });
</script>

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

      <form action="admin/core/mngContact.php" method="post" enctype="multipart/form-data">
        <h4 class="text-center">Richieste studio medico</h5>
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
            <label class="form-label">Richiesta <span class="text-danger">*</span></label>
            <select class="form-select form-control-clicked" id="defaultSelect" name="reason_id" aria-label="Default select example" required>
              <option value="" selected disabled>---</option>
              <?php
              $doc->table = "doc_contact_reason";
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
          <div class="mb-3">
            <label class="form-label">
              Allegati <br>
              <small>Dimensione massima 10MB<br>
                Sono permessi solo pdf, jpg, jpeg, png, webp, heic, heif.</small>
            </label>
            <input class="form-control" type="file" id="formFile" name="attachment" />
          </div>

          <!-- Note -->
          <div class="mb-3">
            <label class="form-label">Eventuali note</label>
            <textarea
              name="notes"
              id="notes"
              class="form-control"
              rows="3"
              maxlength="140"></textarea>
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

          <input type="hidden" name="recaptcha_response" id="recaptchaResponse">


          <button type="submit" class="btn btn-primary">
            Invia
          </button>


      </form>

    </div>
    <?php
    require 'inc/nav.php';
    require 'inc/footer.php';
    ?>