<script src='script/index.global.js'></script>
<script src='script/locales-all.global.js'></script>

<!-- Modale dettaglio evento -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventDetailLabel"><?=$cal_details?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
      </div>
      <div class="modal-body">
        <p><strong><?=$cal_cat?>: </strong> <span id="detailCatName"></span> <span id="detailColorPreview" style="display:inline-block; padding:0.5em; width:40px; height:20px; border-radius:4px;"></span></p>

        <div class="mb-2">
          <label class="form-label"><strong><?=$cal_title?>:</strong></label>
          <input type="text" class="form-control" id="detailTitleInput">
        </div>
        <div class="mb-2">
          <label class="form-label"><strong><?=$cal_start?>:</strong></label>
          <input type="datetime-local" class="form-control" id="detailStartInput">
        </div>
        <div class="mb-2">
          <label class="form-label"><strong><?=$cal_end?>:</strong></label>
          <input type="datetime-local" class="form-control" id="detailEndInput">
        </div>
        <div class="mb-2">
          <label class="form-label"><strong><?=$cal_notes?><?=$cal_option?>:</strong></label>
          <textarea class="form-control" id="detailNoteInput" rows="2"></textarea>
        </div>
        <div class="mb-2">
          <label class="form-label"><strong><?=$common_link?><?=$cal_option?>:</strong></label>
          <input type="url" class="form-control" id="detailUrlInput">
        </div>
      </div>
      <div class="modal-footer">
        <button id="updateEventBtn" type="button" class="btn btn-primary"><?=$common_update?></button>
        <button id="deleteEventBtn" type="button" class="btn btn-danger"><?=$common_delete?></button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?=$common_close?></button>
      </div>
    </div>
  </div>
</div>

<!-- Modale conferma eliminazione -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body">
        <?=$cal_modal_delete_body?>
      </div>
      <div class="modal-footer">
        <button id="confirmDeleteBtn" type="button" class="btn btn-danger"><?=$common_delete?></button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?=$common_modal_cancel?></button>
      </div>
    </div>
  </div>
</div>

<!-- Modale inserimento evento -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="addEventForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addEventLabel"><?=$cal_add?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="eventTitle" class="form-label"><?=$cal_title?></label>
          <input type="text" class="form-control" id="eventTitle" name="title" required>
        </div>
        <div class="mb-3">
          <label for="eventStart" class="form-label"><?=$cal_start?></label>
          <input type="datetime-local" class="form-control" id="eventStart" name="start" required>
        </div>
        <div class="mb-3">
          <label for="eventEnd" class="form-label"><?=$cal_end?></label>
          <input type="datetime-local" class="form-control" id="eventEnd" name="end" required>
        </div>
        <div class="mb-3">
          <label for="eventUrl" class="form-label"><?=$cal_notes?><?=$cal_option?></label>
          <input type="text" class="form-control" id="eventNote" name="note">
        </div>
        <div class="mb-3">
          <label for="eventUrl" class="form-label"><?=$common_link?><?=$cal_option?></label>
          <input type="url" class="form-control" id="eventUrl" name="url">
        </div>
        <div class="mb-3">
          <label class="form-label"><?=$cal_cat?></label><br>
          <div class="row">

            <?php
            $calendar->table = "calendar_cat";
            $stmt = $calendar->showAll('id');
            $calArray = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              $isDefault = $row['id'] == 1 ? 'checked' : '';
            ?>
              <div class="col-2 text-center">
                <input type="radio" class="btn-check" name="calendar_color" value="<?= $row['id'] ?>" id="cal_<?= $row['id'] ?>" <?= $isDefault ?> autocomplete="off" hidden>
                <label class="color-label shadow my-1" for="cal_<?= $row['id'] ?>" style="background-color: <?= $row['cat_color'] ?>;">
                  <span class="checkmark">✔</span>
                </label>
                <span style="color:<?= $row['cat_color'] ?>; font-weight:bold"><?= $row['cat_name'] ?></span>
              </div>
            <?php
            }
            ?>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><?=$common_submit?></button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?=$common_modal_cancel?></button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      locale: '<?= $lang ?>',
      headerToolbar: {
        left: 'prevYear,prev,next,nextYear today',
        center: 'title',
        right: 'dayGridMonth,dayGridWeek,dayGridDay'
      },
      initialView: 'dayGridMonth',
      selectable: true,
      events: 'core/get_events.php',

      eventClick: function(info) {
        info.jsEvent.preventDefault(); // previene apertura link

        var event = info.event;
        document.getElementById('detailTitleInput').value = event.title;

        function formatLocalDateTime(date) {
          const pad = n => n.toString().padStart(2, '0');
          return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
        }

        document.getElementById('detailStartInput').value = formatLocalDateTime(event.start);
        document.getElementById('detailEndInput').value = event.end ? formatLocalDateTime(event.end) : '';

        document.getElementById('detailNoteInput').value = event.extendedProps.note || '';
        document.getElementById('detailUrlInput').value = event.url || '';

        const color = event.backgroundColor || event.color || '##008db1';
        document.getElementById('detailColorPreview').style.backgroundColor = color;
        document.getElementById('detailCatName').textContent = event.extendedProps.cat_name || '—';


        // ID per eliminazione
        window.currentEventId = event.id;

        var eventModal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
        eventModal.show();
      },


      dateClick: function(info) {
        // imposto il form per inserire nuovo evento
        var startInput = document.getElementById('eventStart');
        var endInput = document.getElementById('eventEnd');

        // default ora di inizio = mezzogiorno
        startInput.value = info.dateStr + 'T12:00';
        endInput.value = info.dateStr + 'T13:00';

        var addModal = new bootstrap.Modal(document.getElementById('addEventModal'));
        addModal.show();
      }
    });

    calendar.render();

    // Gestione submit inserimento evento
    document.getElementById('addEventForm').addEventListener('submit', function(e) {
      e.preventDefault();

      var formData = new FormData(this);

      fetch('core/add_event.php', {
          method: 'POST',
          body: formData
        }).then(response => response.json())
        .then(data => {
          if (data.success) {
            calendar.refetchEvents();
            bootstrap.Modal.getInstance(document.getElementById('addEventModal')).hide();
          } else {
            alert("<?=$err_noEvent?>: " + (data.error || ''));
          }
        });
    });

    // Gestione eliminazione evento
    document.getElementById('deleteEventBtn').addEventListener('click', function() {
      var confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
      confirmModal.show();
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
      fetch('core/delete_event.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'id=' + encodeURIComponent(window.currentEventId)
        }).then(response => response.json())
        .then(data => {
          if (data.success) {
            calendar.refetchEvents();
            bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal')).hide();
            bootstrap.Modal.getInstance(document.getElementById('eventDetailModal')).hide();
          } else {
            alert("<?=$err_noDelEvent?>: " + (data.error || ''));
          }
        });
    });

    document.getElementById('updateEventBtn').addEventListener('click', function() {
      const id = window.currentEventId;

      const formData = new URLSearchParams();
      formData.append('id', id);
      formData.append('title', document.getElementById('detailTitleInput').value);
      formData.append('start', document.getElementById('detailStartInput').value);
      formData.append('end', document.getElementById('detailEndInput').value);
      formData.append('note', document.getElementById('detailNoteInput').value);
      formData.append('url', document.getElementById('detailUrlInput').value);

      fetch('core/update_event.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: formData.toString()
        }).then(response => response.json())
        .then(data => {
          if (data.success) {
            calendar.refetchEvents();
            bootstrap.Modal.getInstance(document.getElementById('eventDetailModal')).hide();
          } else {
            alert("<?=$err_noEditEvent?>: " + (data.error || ''));
          }
        });
    });

  });
</script>


<div class="card">

  <div class="card-header text-center">
    <div id='calendar'></div>
  </div>
</div>