<script src='script/index.global.js'></script>
<script src='script/locales-all.global.js'></script>
<style>
    @media print {

        body * {
            visibility: hidden;
        }

        #calendar,
        #calendar * {
            visibility: visible;
        }

        #calendar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }

        /* nascondi toolbar */
        .fc-header-toolbar {
            display: none;
        }
    }
</style>
<div class="card">

    <div class="card-header text-center">
        <div id='calendar'></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            locale: 'it',
            initialView: 'timeGridWeek',
            firstDay: 1,
            slotMinTime: '08:00:00',
            slotMaxTime: '20:00:00',
            allDaySlot: false,
            customButtons: {
                printDay: {
                    text: 'Stampa',
                    click: function() {
                        window.print();
                    }
                }
            },

            headerToolbar: {
                left: 'prev,next today printDay',
                center: 'title',
                right: 'timeGridDay,timeGridWeek,dayGridMonth'
            },
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