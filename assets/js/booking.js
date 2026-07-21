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

      if (data.free) {

        html = `
            <div class="alert alert-success">
                Accesso libero, nessuna prenotazione richiesta.
            </div>
        `;

      } else if (data.slots.length === 0) {



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
                                  <button
                                    class="btn btn-primary btn-sm"
                                    onclick="openBookingModal('${data.date}', '${slot}')">
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

function openBookingModal(date, time) {
  document.getElementById('bookingDate').value = date;
  document.getElementById('bookingTime').value = time;

  document.getElementById('bookingDateLabel').textContent =
    formatDateLabel(date) + ' alle ore ' + time;

  const modal = new bootstrap.Modal(
    document.getElementById('bookingModal')
  );

  modal.show();
}

document.getElementById('prevDay').addEventListener('click', function () {
  const d = getPreviousBusinessDay(new Date(currentDate));

  // Confronto sulle stringhe YYYY-MM-DD per evitare problemi di orario
  if (formatDateISO(d) >= formatDateISO(today)) {
    currentDate = formatDateISO(d);
    loadSlots(currentDate);
  }
});

document.getElementById('nextDay').addEventListener('click', function () {
  const d = getNextBusinessDay(new Date(currentDate));

  if (d <= maxDate) {
    currentDate = formatDateISO(d);
    loadSlots(currentDate);
  }
});

// Se oggi è sabato o domenica parto direttamente dal lunedì
let startDate = new Date();

if (!isBusinessDay(startDate)) {
    startDate = getNextBusinessDay(startDate);
}

currentDate = formatDateISO(startDate);

loadSlots(currentDate);


/* // Caricamento iniziale
while (!isBusinessDay(new Date(currentDate))) {
  currentDate = formatDateISO(
    getNextBusinessDay(new Date(currentDate))
  );
}

// Caricamento iniziale
loadSlots(currentDate); */