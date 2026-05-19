<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Doc extends Common
{

    public $name;
    public $value;
    public $weekday;
    public $start_time;
    public $end_time;
    public $booking;


    function updateValue()
    {


        $query = "UPDATE " . $this->prx . $this->table . "
        SET  value = :value  WHERE name = :name";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":value", $this->value);
        $stmt->bindParam(":name", $this->name);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
 /**
     * Genera gli slot tra due orari.
     * Esempio: 09:00 - 10:00 con durata 15 minuti
     * -> 09:00, 09:15, 09:30, 09:45
     */
    public function generateSlots($start, $end, $duration = 15)
    {
        $slots = [];

        $startTime = new DateTime($start);
        $endTime   = new DateTime($end);

        while ($startTime < $endTime) {
            $slots[] = $startTime->format('H:i');
            $startTime->modify("+{$duration} minutes");
        }

        return $slots;
    }

    /**
     * Recupera le fasce orarie settimanali.
     * Tabella: doc_settings
     *
     * Struttura:
     * - weekday     (1=Lunedì ... 7=Domenica)
     * - start_time  (TIME)
     * - end_time    (TIME)
     * - booking     (durata slot in minuti)
     */
    public function getDayRules(PDO $db, $weekday)
    {
        $stmt = $db->prepare("
            SELECT *
            FROM doc_timetable
            WHERE weekday = ?
            ORDER BY start_time
        ");
        $stmt->execute([$weekday]);

        return $stmt->fetchAll();
    }

    /**
     * Recupera gli slot già prenotati.
     * Tabella: doc_bookings
     * Campo: slot_booked (DATETIME)
     */
    public function getBookedSlots(PDO $db, $date)
    {
        $stmt = $db->prepare("
            SELECT TIME(slot_booked) AS booked_time
            FROM doc_bookings
            WHERE DATE(slot_booked) = ?
        ");
        $stmt->execute([$date]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Verifica se un giorno è chiuso tramite eccezioni.
     * Tabella: doc_timetable_exception
     *
     * Si assume la presenza di:
     * - exception_date DATE
     * - is_closed TINYINT(1)
     */
    public function isClosedByException(PDO $db, $date)
    {
        // Se la tabella non è ancora strutturata, puoi commentare questo metodo.
        $stmt = $db->prepare("
            SELECT is_closed
            FROM doc_timetable_exception
            WHERE exception_date = ?
            LIMIT 1
        ");
        $stmt->execute([$date]);

        $row = $stmt->fetch();

        if (!$row) {
            return false;
        }

        return (int)$row['is_closed'] === 1;
    }

    /**
     * Restituisce gli slot disponibili per una data.
     */
    public function getAvailableSlots(PDO $db, $date)
    {
        date_default_timezone_set('Europe/Rome');

        // Giorno della settimana (1=Lunedì ... 7=Domenica)
        $weekday = date('N', strtotime($date));

        // Solo lunedì-venerdì
        if ($weekday > 5) {
            return [];
        }

        // Se il giorno è chiuso da eccezione
        // (commenta questa riga se la tabella non è ancora pronta)
        // if ($this->isClosedByException($db, $date)) {
        //     return [];
        // }

        // Recupera regole settimanali
        $rules = $this->getDayRules($db, $weekday);

        if (empty($rules)) {
            return [];
        }

        // Recupera slot già prenotati
        $booked = $this->getBookedSlots($db, $date);

        // Genera tutti gli slot teorici
        $allSlots = [];

        foreach ($rules as $rule) {
            // Il campo booking contiene la durata dello slot in minuti.
            // Se nullo o <= 0, usa 15 minuti.
            $duration = (int)$rule['booking'];
            if ($duration <= 0) {
                $duration = 15;
            }

            $slots = $this->generateSlots(
                $rule['start_time'],
                $rule['end_time'],
                $duration
            );

            $allSlots = array_merge($allSlots, $slots);
        }

        // Rimuove duplicati e ordina
        $allSlots = array_unique($allSlots);
        sort($allSlots);

        // Elimina gli slot già prenotati
        $available = array_filter($allSlots, function ($slot) use ($booked) {
            // Nel DB TIME() restituisce HH:MM:SS
            return !in_array($slot . ':00', $booked, true);
        });

        // Se la data è oggi, rimuove gli orari già passati
        $today = date('Y-m-d');

        if ($date === $today) {
            $now = date('H:i');

            $available = array_filter($available, function ($slot) use ($now) {
                return $slot > $now;
            });
        }

        return array_values($available);
    }
}
