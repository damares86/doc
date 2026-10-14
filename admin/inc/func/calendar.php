<?php

declare(strict_types=1);

if (!isset($calendar) || !($calendar instanceof Calendar)) {
    $calendar = new Calendar($db);
    if (!empty($prefix)) {
        $calendar->prx = $prefix . '_';
    }
}
?>
<script src='script/index.global.js'></script>
<script src='script/locales-all.global.js'></script>
<script src='script/rrule.min.js'></script>
<script src='script/fullcalendar.rrule.global.min.js'></script>

<!-- Modale dettaglio evento -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventDetailLabel"><?= htmlspecialchars((string) ($cal_details ?? 'Event Details'), ENT_QUOTES, 'UTF-8') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="detailRecurringBadgeBox" class="alert alert-info py-2 px-3 mb-3" style="display: none;">
          <div class="fw-bold"><i class="bi bi-arrow-repeat"></i> <?= htmlspecialchars((string) ($cal_recurring_badge ?? 'Recurring event (Series)'), ENT_QUOTES, 'UTF-8') ?></div>
          <small class="text-muted"><?= htmlspecialchars((string) ($cal_recurring_notice ?? 'Changes and deletion will apply to all occurrences of this series.'), ENT_QUOTES, 'UTF-8') ?></small>
        </div>

        <p><strong><?= htmlspecialchars((string) ($cal_cat ?? 'Category'), ENT_QUOTES, 'UTF-8') ?>: </strong> <span id="detailCatName"></span> <span id="detailColorPreview" style="display:inline-block; padding:0.5em; width:40px; height:20px; border-radius:4px;"></span></p>

        <div class="mb-2">
          <label class="form-label"><strong><?= htmlspecialchars((string) ($cal_title ?? 'Title'), ENT_QUOTES, 'UTF-8') ?>:</strong></label>
          <input type="text" class="form-control" id="detailTitleInput">
        </div>
        <div class="row g-2 mb-2">
          <div class="col-6">
            <label class="form-label"><strong><?= htmlspecialchars((string) ($cal_start ?? 'Start'), ENT_QUOTES, 'UTF-8') ?>:</strong></label>
            <input type="datetime-local" class="form-control" id="detailStartInput">
          </div>
          <div class="col-6">
            <label class="form-label"><strong><?= htmlspecialchars((string) ($cal_end ?? 'End'), ENT_QUOTES, 'UTF-8') ?>:</strong></label>
            <input type="datetime-local" class="form-control" id="detailEndInput">
          </div>
        </div>

        <!-- Sezione ricorrenza modifica -->
        <div class="mb-3 mt-3 form-check form-switch">
          <input class="form-check-input" type="checkbox" id="detailIsRecurring" value="1">
          <label class="form-check-label fw-bold" for="detailIsRecurring"><?= htmlspecialchars((string) ($cal_repeat ?? 'Repeat event'), ENT_QUOTES, 'UTF-8') ?></label>
        </div>

        <div id="detailRecurrenceBox" class="border rounded p-3 mb-3 bg-light" style="display: none;">
          <div class="row g-2 mb-2">
            <div class="col-md-6">
              <label class="form-label small fw-bold"><?= htmlspecialchars((string) ($cal_freq ?? 'Frequency'), ENT_QUOTES, 'UTF-8') ?></label>
              <select class="form-select form-select-sm" id="detailRecurrenceFreq">
                <option value="DAILY"><?= htmlspecialchars((string) ($cal_freq_daily ?? 'Daily'), ENT_QUOTES, 'UTF-8') ?></option>
                <option value="WEEKLY" selected><?= htmlspecialchars((string) ($cal_freq_weekly ?? 'Weekly'), ENT_QUOTES, 'UTF-8') ?></option>
                <option value="MONTHLY"><?= htmlspecialchars((string) ($cal_freq_monthly ?? 'Monthly'), ENT_QUOTES, 'UTF-8') ?></option>
                <option value="YEARLY"><?= htmlspecialchars((string) ($cal_freq_yearly ?? 'Yearly'), ENT_QUOTES, 'UTF-8') ?></option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold"><?= htmlspecialchars((string) ($cal_repeat_every ?? 'Repeat every'), ENT_QUOTES, 'UTF-8') ?></label>
              <div class="input-group input-group-sm">
                <input type="number" class="form-control" id="detailRecurrenceInterval" value="1" min="1" max="99">
                <span class="input-group-text" id="detailRecurrenceIntervalLabel"><?= htmlspecialchars((string) ($cal_unit_weeks ?? 'week(s)'), ENT_QUOTES, 'UTF-8') ?></span>
              </div>
            </div>
          </div>

          <div id="detailDaysContainer" class="mb-2">
            <label class="form-label small fw-bold d-block"><?= htmlspecialchars((string) ($cal_days_label ?? 'On days'), ENT_QUOTES, 'UTF-8') ?></label>
            <div class="btn-group btn-group-sm w-100" role="group">
              <?php foreach (['MO' => ($cal_day_mo ?? 'Lun'), 'TU' => ($cal_day_tu ?? 'Mar'), 'WE' => ($cal_day_we ?? 'Mer'), 'TH' => ($cal_day_th ?? 'Gio'), 'FR' => ($cal_day_fr ?? 'Ven'), 'SA' => ($cal_day_sa ?? 'Sab'), 'SU' => ($cal_day_su ?? 'Dom')] as $dKey => $dLabel): ?>
                <input type="checkbox" class="btn-check detail-day-check" id="detail_day_<?= $dKey ?>" value="<?= $dKey ?>" autocomplete="off">
                <label class="btn btn-outline-primary" for="detail_day_<?= $dKey ?>"><?= $dLabel ?></label>
              <?php endforeach; ?>
            </div>
            <input type="hidden" id="detailRecurrenceDays" value="">
          </div>

          <div class="mb-1">
            <label class="form-label small fw-bold"><?= htmlspecialchars((string) ($cal_end_repeat ?? 'End repeat'), ENT_QUOTES, 'UTF-8') ?></label>
            <div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="detail_end_type" id="detailEndNever" value="never" checked>
                <label class="form-check-label small" for="detailEndNever"><?= htmlspecialchars((string) ($cal_end_never ?? 'Never'), ENT_QUOTES, 'UTF-8') ?></label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="detail_end_type" id="detailEndUntil" value="until">
                <label class="form-check-label small" for="detailEndUntil"><?= htmlspecialchars((string) ($cal_end_until ?? 'Until'), ENT_QUOTES, 'UTF-8') ?></label>
              </div>
            </div>
            <div id="detailUntilDateBox" class="mt-2" style="display: none;">
              <input type="datetime-local" class="form-control form-control-sm" id="detailRecurrenceUntil">
            </div>
          </div>
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
      <div class="modal-body" id="confirmDeleteText">
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
        <div class="row g-2 mb-3">
          <div class="col-6">
            <label for="eventStart" class="form-label"><?= htmlspecialchars((string) ($cal_start ?? 'Start'), ENT_QUOTES, 'UTF-8') ?></label>
            <input type="datetime-local" class="form-control" id="eventStart" name="start" required>
          </div>
          <div class="col-6">
            <label for="eventEnd" class="form-label"><?= htmlspecialchars((string) ($cal_end ?? 'End'), ENT_QUOTES, 'UTF-8') ?></label>
            <input type="datetime-local" class="form-control" id="eventEnd" name="end" required>
          </div>
        </div>

        <!-- Opzioni di ricorrenza -->
        <div class="mb-3 form-check form-switch">
          <input class="form-check-input" type="checkbox" id="eventIsRecurring" name="is_recurring" value="1">
          <label class="form-check-label fw-bold" for="eventIsRecurring"><?= htmlspecialchars((string) ($cal_repeat ?? 'Repeat event'), ENT_QUOTES, 'UTF-8') ?></label>
        </div>

        <div id="addRecurrenceBox" class="border rounded p-3 mb-3 bg-light" style="display: none;">
          <div class="row g-2 mb-2">
            <div class="col-md-6">
              <label class="form-label small fw-bold"><?= htmlspecialchars((string) ($cal_freq ?? 'Frequency'), ENT_QUOTES, 'UTF-8') ?></label>
              <select class="form-select form-select-sm" id="eventRecurrenceFreq" name="recurrence_freq">
                <option value="DAILY"><?= htmlspecialchars((string) ($cal_freq_daily ?? 'Daily'), ENT_QUOTES, 'UTF-8') ?></option>
                <option value="WEEKLY" selected><?= htmlspecialchars((string) ($cal_freq_weekly ?? 'Weekly'), ENT_QUOTES, 'UTF-8') ?></option>
                <option value="MONTHLY"><?= htmlspecialchars((string) ($cal_freq_monthly ?? 'Monthly'), ENT_QUOTES, 'UTF-8') ?></option>
                <option value="YEARLY"><?= htmlspecialchars((string) ($cal_freq_yearly ?? 'Yearly'), ENT_QUOTES, 'UTF-8') ?></option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold"><?= htmlspecialchars((string) ($cal_repeat_every ?? 'Repeat every'), ENT_QUOTES, 'UTF-8') ?></label>
              <div class="input-group input-group-sm">
                <input type="number" class="form-control" id="eventRecurrenceInterval" name="recurrence_interval" value="1" min="1" max="99">
                <span class="input-group-text" id="addRecurrenceIntervalLabel"><?= htmlspecialchars((string) ($cal_unit_weeks ?? 'week(s)'), ENT_QUOTES, 'UTF-8') ?></span>
              </div>
            </div>
          </div>

          <div id="addDaysContainer" class="mb-2">
            <label class="form-label small fw-bold d-block"><?= htmlspecialchars((string) ($cal_days_label ?? 'On days'), ENT_QUOTES, 'UTF-8') ?></label>
            <div class="btn-group btn-group-sm w-100" role="group">
              <?php foreach (['MO' => ($cal_day_mo ?? 'Lun'), 'TU' => ($cal_day_tu ?? 'Mar'), 'WE' => ($cal_day_we ?? 'Mer'), 'TH' => ($cal_day_th ?? 'Gio'), 'FR' => ($cal_day_fr ?? 'Ven'), 'SA' => ($cal_day_sa ?? 'Sab'), 'SU' => ($cal_day_su ?? 'Dom')] as $dKey => $dLabel): ?>
                <input type="checkbox" class="btn-check add-day-check" id="add_day_<?= $dKey ?>" value="<?= $dKey ?>" autocomplete="off">
                <label class="btn btn-outline-primary" for="add_day_<?= $dKey ?>"><?= $dLabel ?></label>
              <?php endforeach; ?>
            </div>
            <input type="hidden" id="eventRecurrenceDays" name="recurrence_days" value="">
          </div>

          <div class="mb-1">
            <label class="form-label small fw-bold"><?= htmlspecialchars((string) ($cal_end_repeat ?? 'End repeat'), ENT_QUOTES, 'UTF-8') ?></label>
            <div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="add_end_type" id="addEndNever" value="never" checked>
                <label class="form-check-label small" for="addEndNever"><?= htmlspecialchars((string) ($cal_end_never ?? 'Never'), ENT_QUOTES, 'UTF-8') ?></label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="add_end_type" id="addEndUntil" value="until">
                <label class="form-check-label small" for="addEndUntil"><?= htmlspecialchars((string) ($cal_end_until ?? 'Until'), ENT_QUOTES, 'UTF-8') ?></label>
              </div>
            </div>
            <div id="addUntilDateBox" class="mt-2" style="display: none;">
              <input type="datetime-local" class="form-control form-control-sm" id="eventRecurrenceUntil" name="recurrence_until">
            </div>
          </div>
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

    const unitLabels = {
      'DAILY': '<?= htmlspecialchars((string) ($cal_unit_days ?? 'day(s)'), ENT_QUOTES, 'UTF-8') ?>',
      'WEEKLY': '<?= htmlspecialchars((string) ($cal_unit_weeks ?? 'week(s)'), ENT_QUOTES, 'UTF-8') ?>',
      'MONTHLY': '<?= htmlspecialchars((string) ($cal_unit_months ?? 'month(s)'), ENT_QUOTES, 'UTF-8') ?>',
      'YEARLY': '<?= htmlspecialchars((string) ($cal_unit_years ?? 'year(s)'), ENT_QUOTES, 'UTF-8') ?>'
    };

    function updateRecurrenceUI(prefix) {
      const isRec = document.getElementById(prefix + 'IsRecurring').checked;
      const box = document.getElementById(prefix + 'RecurrenceBox');
      box.style.display = isRec ? 'block' : 'none';

      const freqSelect = document.getElementById(prefix + 'RecurrenceFreq');
      const freq = freqSelect.value;
      const unitLabel = document.getElementById(prefix + 'RecurrenceIntervalLabel');
      if (unitLabel && unitLabels[freq]) {
        unitLabel.textContent = unitLabels[freq];
      }

      const daysContainer = document.getElementById(prefix + 'DaysContainer');
      if (daysContainer) {
        daysContainer.style.display = (freq === 'WEEKLY') ? 'block' : 'none';
      }

      const endUntilRadio = document.getElementById(prefix + 'EndUntil');
      const untilBox = document.getElementById(prefix + 'UntilDateBox');
      if (untilBox && endUntilRadio) {
        untilBox.style.display = endUntilRadio.checked ? 'block' : 'none';
      }
    }

    // Event listeners per Add Recurrence
    document.getElementById('eventIsRecurring').addEventListener('change', () => updateRecurrenceUI('event'));
    document.getElementById('eventRecurrenceFreq').addEventListener('change', () => updateRecurrenceUI('event'));
    document.getElementById('addEndNever').addEventListener('change', () => updateRecurrenceUI('event'));
    document.getElementById('addEndUntil').addEventListener('change', () => updateRecurrenceUI('event'));

    // Event listeners per Detail/Edit Recurrence
    document.getElementById('detailIsRecurring').addEventListener('change', () => updateRecurrenceUI('detail'));
    document.getElementById('detailRecurrenceFreq').addEventListener('change', () => updateRecurrenceUI('detail'));
    document.getElementById('detailEndNever').addEventListener('change', () => updateRecurrenceUI('detail'));
    document.getElementById('detailEndUntil').addEventListener('change', () => updateRecurrenceUI('detail'));

    function formatLocalDateTime(date) {
      const pad = n => n.toString().padStart(2, '0');
      return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
    }

    const isMobile = window.matchMedia('(max-width: 767.98px)').matches;

    var calendar = new FullCalendar.Calendar(calendarEl, {
      locale: '<?= htmlspecialchars((string) ($lang ?? 'en'), ENT_QUOTES, 'UTF-8') ?>',
      initialView: isMobile ? 'timeGridDay' : 'timeGridWeek',
      headerToolbar: isMobile ? {
        left: 'prev,next',
        center: 'title',
        right: 'today,timeGridDay,timeGridWeek,dayGridMonth'
      } : {
        left: 'prev,next today',
        center: 'title',
        right: 'timeGridWeek,timeGridDay,dayGridMonth'
      },
      selectable: true,
      events: 'core/get_events.php',

      eventClick: function(info) {
        info.jsEvent.preventDefault();

        var event = info.event;
        document.getElementById('detailTitleInput').value = event.title || '';
        document.getElementById('detailStartInput').value = event.start ? formatLocalDateTime(event.start) : '';
        document.getElementById('detailEndInput').value = event.end ? formatLocalDateTime(event.end) : '';
        document.getElementById('detailNoteInput').value = event.extendedProps.note || '';
        document.getElementById('detailUrlInput').value = event.url || '';

        const color = event.backgroundColor || event.color || '#008db1';
        document.getElementById('detailColorPreview').style.backgroundColor = color;
        document.getElementById('detailCatName').textContent = event.extendedProps.cat_name || '—';

        window.currentEventId = event.id;

        const isRecurring = parseInt(event.extendedProps.is_recurring || 0, 10) === 1;
        document.getElementById('detailRecurringBadgeBox').style.display = isRecurring ? 'block' : 'none';
        document.getElementById('detailIsRecurring').checked = isRecurring;

        if (isRecurring) {
          document.getElementById('detailRecurrenceFreq').value = event.extendedProps.recurrence_freq || 'WEEKLY';
          document.getElementById('detailRecurrenceInterval').value = event.extendedProps.recurrence_interval || 1;

          const activeDays = (event.extendedProps.recurrence_days || '').split(',').map(s => s.trim().toUpperCase());
          document.querySelectorAll('.detail-day-check').forEach(cb => {
            cb.checked = activeDays.includes(cb.value.toUpperCase());
          });

          const untilVal = event.extendedProps.recurrence_until || '';
          if (untilVal) {
            document.getElementById('detailEndUntil').checked = true;
            document.getElementById('detailRecurrenceUntil').value = untilVal;
          } else {
            document.getElementById('detailEndNever').checked = true;
            document.getElementById('detailRecurrenceUntil').value = '';
          }
        } else {
          document.querySelectorAll('.detail-day-check').forEach(cb => { cb.checked = false; });
          document.getElementById('detailEndNever').checked = true;
          document.getElementById('detailRecurrenceUntil').value = '';
        }

        updateRecurrenceUI('detail');

        var eventModal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
        eventModal.show();
      },

      dateClick: function(info) {
        var startInput = document.getElementById('eventStart');
        var endInput = document.getElementById('eventEnd');

        startInput.value = info.dateStr + 'T12:00';
        endInput.value = info.dateStr + 'T13:00';

        // Preseleziona il giorno della settimana cliccato
        const clickedDate = new Date(info.dateStr + 'T12:00');
        const dayMap = ['SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA'];
        const dayCode = dayMap[clickedDate.getDay()];

        document.querySelectorAll('.add-day-check').forEach(cb => {
          cb.checked = (cb.value === dayCode);
        });

        document.getElementById('eventIsRecurring').checked = false;
        document.getElementById('eventRecurrenceInterval').value = 1;
        document.getElementById('eventRecurrenceFreq').value = 'WEEKLY';
        document.getElementById('addEndNever').checked = true;
        document.getElementById('eventRecurrenceUntil').value = '';
        updateRecurrenceUI('event');

        var addModal = new bootstrap.Modal(document.getElementById('addEventModal'));
        addModal.show();
      }
    });

    calendar.render();

    document.getElementById('addEventForm').addEventListener('submit', function(e) {
      e.preventDefault();

      // Raccoglie i giorni selezionati
      const selectedDays = [];
      document.querySelectorAll('.add-day-check:checked').forEach(cb => {
        selectedDays.push(cb.value);
      });
      document.getElementById('eventRecurrenceDays').value = selectedDays.join(',');

      var formData = new FormData(this);
      if (!document.getElementById('eventIsRecurring').checked) {
        formData.set('is_recurring', '0');
        formData.delete('recurrence_freq');
        formData.delete('recurrence_interval');
        formData.delete('recurrence_days');
        formData.delete('recurrence_until');
      } else if (document.getElementById('addEndNever').checked) {
        formData.delete('recurrence_until');
      }

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
      const isRecurring = document.getElementById('detailIsRecurring').checked ? 1 : 0;

      const selectedDays = [];
      document.querySelectorAll('.detail-day-check:checked').forEach(cb => {
        selectedDays.push(cb.value);
      });

      const formData = new URLSearchParams();
      formData.append('id', id);
      formData.append('title', document.getElementById('detailTitleInput').value);
      formData.append('start', document.getElementById('detailStartInput').value);
      formData.append('end', document.getElementById('detailEndInput').value);
      formData.append('note', document.getElementById('detailNoteInput').value);
      formData.append('url', document.getElementById('detailUrlInput').value);
      formData.append('is_recurring', isRecurring.toString());

      if (isRecurring === 1) {
        formData.append('recurrence_freq', document.getElementById('detailRecurrenceFreq').value);
        formData.append('recurrence_interval', document.getElementById('detailRecurrenceInterval').value);
        formData.append('recurrence_days', selectedDays.join(','));
        const untilVal = document.getElementById('detailEndUntil').checked ? document.getElementById('detailRecurrenceUntil').value : '';
        formData.append('recurrence_until', untilVal);
      }

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

<div class="card shadow-sm border-0">
  <div class="card-body p-2 p-md-3">
    <div id='calendar'></div>
  </div>
</div>