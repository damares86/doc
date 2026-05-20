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


    <script>
      let currentDate = new Date().toISOString().split('T')[0];

      // oggi e limite massimo (+10 giorni)
      const today = new Date();
      const maxDate = new Date();
      maxDate.setDate(maxDate.getDate() + 10);

      function formatDateISO(date) {
        return date.toISOString().split('T')[0];
      }

      function formatDateLabel(dateString) {
        const d = new Date(dateString);
        return d.toLocaleDateString('it-IT', {
          weekday: 'long',
          day: '2-digit',
          month: 'long',
          year: 'numeric'
        });
      }

      function isBusinessDay(date) {
        const day = date.getDay(); // 0 = domenica, 6 = sabato
        return day >= 1 && day <= 5;
      }

      function getNextBusinessDay(date) {
        const d = new Date(date);

        do {
          d.setDate(d.getDate() + 1);
        } while (!isBusinessDay(d));

        return d;
      }

      function getPreviousBusinessDay(date) {
        const d = new Date(date);

        do {
          d.setDate(d.getDate() - 1);
        } while (!isBusinessDay(d));

        return d;
      }

      function loadSlots(date) {
        fetch('admin/core/api_slots.php?date=' + date)
          .then(response => response.json())
          .then(data => {
            document.getElementById('currentDate').textContent =
              formatDateLabel(data.date);

            let html = '';

            if (data.slots.length === 0) {
              html = `
                    <div class="alert alert-warning">
                        Nessun appuntamento disponibile.
                    </div>
                `;
            } else {
              data.slots.forEach(slot => {
                html += `
                        <div class="card mb-2 border-bottom">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <span>${slot}</span>
                                <button class="btn btn-primary btn-sm">
                                    Prenota
                                </button>
                            </div>
                        </div>
                    `;
              });
            }

            document.getElementById('slots').innerHTML = html;
            updateNavigationButtons();
          });
      }

      function updateNavigationButtons() {
        const current = new Date(currentDate);

        // Disabilita "indietro" solo quando siamo esattamente su oggi
        document.getElementById('prevDay').disabled =
          formatDateISO(current) === formatDateISO(today);

        // Disabilita "avanti" se il prossimo giorno lavorativo supera maxDate
        const nextBusinessDay = getNextBusinessDay(current);

        document.getElementById('nextDay').disabled =
          nextBusinessDay > maxDate;
      }

      document.getElementById('prevDay').addEventListener('click', function() {
        const d = getPreviousBusinessDay(new Date(currentDate));

        // Confronto sulle stringhe YYYY-MM-DD per evitare problemi di orario
        if (formatDateISO(d) >= formatDateISO(today)) {
          currentDate = formatDateISO(d);
          loadSlots(currentDate);
        }
      });

      document.getElementById('nextDay').addEventListener('click', function() {
        const d = getNextBusinessDay(new Date(currentDate));

        if (d <= maxDate) {
          currentDate = formatDateISO(d);
          loadSlots(currentDate);
        }
      });

      // Caricamento iniziale
      while (!isBusinessDay(new Date(currentDate))) {
        currentDate = formatDateISO(
          getNextBusinessDay(new Date(currentDate))
        );
      }

      // Caricamento iniziale
      loadSlots(currentDate);
    </script>

    <?php
    require 'inc/nav.php';
    require 'inc/footer.php';
    ?>