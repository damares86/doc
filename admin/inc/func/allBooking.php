<script src='script/index.global.js'></script>
<script src='script/locales-all.global.js'></script>

<div class="card">

  <div class="card-header text-center">
    <div id='calendar'></div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',

        events: 'core/api_bookings.php',

        eventClick: function(info) {
            alert(
                "Prenotazione:\n" +
                info.event.title + "\n" +
                info.event.start.toLocaleString()
            );
        }
    });

    calendar.render();
});
</script>
