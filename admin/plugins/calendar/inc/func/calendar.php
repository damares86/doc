<?php

declare(strict_types=1);

?>
<script src='script/index.global.js'></script>
<script src='script/locales-all.global.js'></script>

<!-- Modale dettaglio evento -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventDetailLabel"><?= htmlspecialchars((string) ($cal_details ?? 'Event Details'), ENT_QUOTES, 'UTF-8') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong><?= htmlspecialchars((string) ($cal_cat ?? 'Category'), ENT_QUOTES, 'UTF-8') ?>: </strong> <span id="detailCatName"></span> <span id="detailColorPreview" style="display:inline-block; padding:0.5em; width:40px; height:20px; border-radius:4px;"></span></p>

        <div class="mb-2">
          <label class="form-label"><strong><?= htmlspecialchars((string) ($cal_title ?? 'Title'), ENT_QUOTES, 'UTF-8') ?>:</strong></label>
          <input type="text" class="form-control" id="detailTitleInput">
        </div>
        <div class="mb-2">
          <label class="form-label"><strong><?= htmlspecialchars((string) ($cal_start ?? 'Start'), ENT_QUOTES, 'UTF-8') ?>:</strong></label>
          <input type="datetime-local" class="form-control" id="detailStartInput">
        </div>
        <div class="mb-2">
          <label class="form-label"><strong><?= htmlspecialchars((string) ($cal_end ?? 'End'), ENT_QUOTES, 'UTF-8') ?>:</strong></label>
          <input type="datetime-local" class="form-control" id="detailEndInput">
        </div>
        <div class="mb-2">
          <label class="form-label"><strong><?= htmlspecialchars((string) ($cal_notes ?? 'Notes'), ENT_QUOTES, 'UTF-8') ?><?= htmlspecialchars((string) ($cal_option ?? ' (optional)'), ENT_QUOTES, 'UTF-8') ?>:</strong></label>
          <textarea class="form-control" id="detailNoteInput" rows="2"></textarea>
        </div>
        <div class="mb-2">
          <label class="form-label"><strong><?= htmlspecialchars((string) ($common_link ?? 'Link'), ENT_QUOTES, 'UTF-8') ?><?= htmlspecialchars((string) ($cal_option ?? ' (optional)'), ENT_QUOTES, 'UTF-8') ?>:</strong></label>
          <input type="url" class="form-control" id="detailUrlInput">
        </div>
      </div>
      <div class="modal-footer">
        <button id="updateEventBtn" type="button" class="btn btn-primary"><?= htmlspecialchars((string) ($common_update ?? 'Update'), ENT_QUOTES, 'UTF-8') ?></button>
        <button id="deleteEventBtn" type="button" class="btn btn-danger"><?= htmlspecialchars((string) ($common_delete ?? 'Delete'), ENT_QUOTES, 'UTF-8') ?></button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= htmlspecialchars((string) ($common_close ?? 'Close'), ENT_QUOTES, 'UTF-8') ?></button>
      </div>
    </div>
  </div>
</div>

<!-- Modale conferma eliminazione -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body">
        <?= htmlspecialchars((string) ($cal_modal_delete_body ?? 'Are you sure you want to delete this event?'), ENT_QUOTES, 'UTF-8') ?>
      </div>
      <div class="modal-footer">
        <button id="confirmDeleteBtn" type="button" class="btn btn-danger"><?= htmlspecialchars((string) ($common_delete ?? 'Delete'), ENT_QUOTES, 'UTF-8') ?></button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= htmlspecialchars((string) ($common_modal_cancel ?? 'Cancel'), ENT_QUOTES, 'UTF-8') ?></button>
      </div>
    </div>
  </div>
</div>

<!-- Modale inserimento evento -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="addEventForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addEventLabel"><?= htmlspecialchars((string) ($cal_add ?? 'Add Event'), ENT_QUOTES, 'UTF-8') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="eventTitle" class="form-label"><?= htmlspecialchars((string) ($cal_title ?? 'Title'), ENT_QUOTES, 'UTF-8') ?></label>
          <input type="text" class="form-control" id="eventTitle" name="title" required>
        </div>
        <div class="mb-3">
          <label for="eventStart" class="form-label"><?= htmlspecialchars((string) ($cal_start ?? 'Start'), ENT_QUOTES, 'UTF-8') ?></label>
          <input type="datetime-local" class="form-control" id="eventStart" name="start" required>
        </div>
        <div class="mb-3">
          <label for="eventEnd" class="form-label"><?= htmlspecialchars((string) ($cal_end ?? 'End'), ENT_QUOTES, 'UTF-8') ?></label>
          <input type="datetime-local" class="form-control" id="eventEnd" name="end" required>
        </div>
        <div class="mb-3">
          <label for="eventNote" class="form-label"><?= htmlspecialchars((string) ($cal_notes ?? 'Notes'), ENT_QUOTES, 'UTF-8') ?><?= htmlspecialchars((string) ($cal_option ?? ' (optional)'), ENT_QUOTES, 'UTF-8') ?></label>
          <input type="text" class="form-control" id="eventNote" name="note">
        </div>
        <div class="mb-3">
          <label for="eventUrl" class="form-label"><?= htmlspecialchars((string) ($common_link ?? 'Link'), ENT_QUOTES, 'UTF-8') ?><?= htmlspecialchars((string) ($cal_option ?? ' (optional)'), ENT_QUOTES, 'UTF-8') ?></label>
          <input type="url" class="form-control" id="eventUrl" name="url">
        </div>
        <div class="mb-3">
          <label class="form-label"><?= htmlspecialchars((string) ($cal_cat ?? 'Category'), ENT_QUOTES, 'UTF-8') ?></label><br>
          <div class="row">
            <?php
            $calendar->table = 'calendar_cat';
            $stmt = $calendar->showAll('id');
            if ($stmt instanceof PDOStatement) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $catId = (int) ($row['id'] ?? 0);
                    $catColor = (string) ($row['cat_color'] ?? '#008db1');
                    $catName = (string) ($row['cat_name'] ?? '');
                    $isDefault = $catId === 1 ? 'checked' : '';
                    ?>
                    <div class="col-2 text-center">
                      <input type="radio" class="btn-check" name="calendar_color" value="<?= $catId ?>" id="cal_<?= $catId ?>" <?= $isDefault ?> autocomplete="off" hidden>
                      <label class="color-label shadow my-1" for="cal_<?= $catId ?>" style="background-color: <?= htmlspecialchars($catColor, ENT_QUOTES, 'UTF-8') ?>;">
                        <span class="checkmark">✔</span>
                      </label>
                      <span style="color:<?= htmlspecialchars($catColor, ENT_QUOTES, 'UTF-8') ?>; font-weight:bold"><?= htmlspecialchars($catName, ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <?php
                }
            }
            ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><?= htmlspecialchars((string) ($common_submit ?? 'Submit'), ENT_QUOTES, 'UTF-8') ?></button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= htmlspecialchars((string) ($common_modal_cancel ?? 'Cancel'), ENT_QUOTES, 'UTF-8') ?></button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      locale: '<?= htmlspecialchars((string) ($lang ?? 'en'), ENT_QUOTES, 'UTF-8') ?>',
      headerToolbar: {
        left: 'prevYear,prev,next,nextYear today',
        center: 'title',
        right: 'dayGridMonth,dayGridWeek,dayGridDay'
      },
      initialView: 'dayGridMonth',
      selectable: true,
      events: 'core/get_events.php',

      eventClick: function(info) {
        info.jsEvent.preventDefault();

        var event = info.event;
        document.getElementById('detailTitleInput').value = event.title || '';

        function formatLocalDateTime(date) {
          const pad = n => n.toString().padStart(2, '0');
          return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
        }

        document.getElementById('detailStartInput').value = event.start ? formatLocalDateTime(event.start) : '';
        document.getElementById('detailEndInput').value = event.end ? formatLocalDateTime(event.end) : '';
        document.getElementById('detailNoteInput').value = event.extendedProps.note || '';
        document.getElementById('detailUrlInput').value = event.url || '';

        const color = event.backgroundColor || event.color || '#008db1';
        document.getElementById('detailColorPreview').style.backgroundColor = color;
        document.getElementById('detailCatName').textContent = event.extendedProps.cat_name || '—';

        window.currentEventId = event.id;

        var eventModal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
        eventModal.show();
      },

      dateClick: function(info) {
        var startInput = document.getElementById('eventStart');
        var endInput = document.getElementById('eventEnd');

        startInput.value = info.dateStr + 'T12:00';
        endInput.value = info.dateStr + 'T13:00';

        var addModal = new bootstrap.Modal(document.getElementById('addEventModal'));
        addModal.show();
      }
    });

    calendar.render();

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
            alert("<?= htmlspecialchars((string) ($err_noEvent ?? 'Error creating event'), ENT_QUOTES, 'UTF-8') ?>: " + (data.error || ''));
          }
        });
    });

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
            alert("<?= htmlspecialchars((string) ($err_noDelEvent ?? 'Error deleting event'), ENT_QUOTES, 'UTF-8') ?>: " + (data.error || ''));
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
            alert("<?= htmlspecialchars((string) ($err_noEditEvent ?? 'Error updating event'), ENT_QUOTES, 'UTF-8') ?>: " + (data.error || ''));
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