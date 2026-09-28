<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Doc extends Common
{
    public string $table = 'doc_bookings';

    public ?string $name = null;
    public ?string $value = null;
    public ?int $weekday = null;
    public ?string $start_time = null;
    public ?string $end_time = null;
    public int $booking = 1;
    public ?string $email = null;
    public ?string $phone = null;
    public int|string|null $reason_id = null;
    public ?string $notes = null;
    public ?string $slot_booked = null;
    public ?string $token = null;
    public ?string $msg = null;

    public function updateValue(): bool
    {
        $tableName = $this->prx . $this->table;
        $query = "UPDATE {$tableName} SET value = :value WHERE name = :name";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':value', $this->value);
        $stmt->bindValue(':name', $this->name);

        return $stmt->execute();
    }

    /**
     * Genera gli slot tra due orari.
     * Esempio: 09:00 - 10:00 con durata 15 minuti -> 09:00, 09:15, 09:30, 09:45
     *
     * @return string[]
     */
    public function generateSlots(string $start, string $end, int $duration = 15): array
    {
        $slots = [];

        try {
            $startTime = new DateTime($start);
            $endTime = new DateTime($end);

            if ($duration <= 0) {
                $duration = 15;
            }

            while ($startTime < $endTime) {
                $slots[] = $startTime->format('H:i');
                $startTime->modify("+{$duration} minutes");
            }
        } catch (\Throwable) {
            return [];
        }

        return $slots;
    }

    /**
     * Recupera le fasce orarie settimanali.
     * Tabella: doc_timetable
     */
    public function getDayRules(PDO $db, int $weekday): array
    {
        $table = $this->prx . 'doc_timetable';
        $stmt = $db->prepare("SELECT * FROM {$table} WHERE weekday = ? ORDER BY start_time");
        $stmt->execute([$weekday]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Recupera gli slot già prenotati per una data.
     * Tabella: doc_bookings
     *
     * @return string[]
     */
    public function getBookedSlots(PDO $db, string $date): array
    {
        $table = $this->prx . 'doc_bookings';
        $stmt = $db->prepare("SELECT TIME(slot_booked) AS booked_time FROM {$table} WHERE DATE(slot_booked) = ?");
        $stmt->execute([$date]);

        $rows = $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
        return array_map(static fn($t) => is_string($t) ? substr($t, 0, 5) : '', $rows);
    }

    /**
     * Verifica se un giorno è chiuso tramite eccezioni.
     * Tabella: doc_timetable_exception
     */
    public function isClosedByException(PDO $db, string $date): bool
    {
        $table = $this->prx . 'doc_timetable_exception';
        try {
            $stmt = $db->prepare("SELECT available FROM {$table} WHERE day = ? LIMIT 1");
            $stmt->execute([$date]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                return false;
            }

            return (int) ($row['available'] ?? 1) === 0;
        } catch (\PDOException) {
            return false;
        }
    }

    /**
     * Restituisce gli slot disponibili per una data specificata (YYYY-MM-DD).
     *
     * @return string[]
     */
    public function getAvailableSlots(PDO $db, string $date): array
    {
        date_default_timezone_set('Europe/Rome');

        // Giorno della settimana (1=Lunedì ... 7=Domenica)
        $weekday = (int) date('N', (int) strtotime($date));

        // Solo lunedì-venerdì (1-5)
        if ($weekday > 5) {
            return [];
        }

        if ($this->isClosedByException($db, $date)) {
            return [];
        }

        // Recupera regole settimanali
        $rules = $this->getDayRules($db, $weekday);
        if (empty($rules)) {
            return [];
        }

        // Recupera slot già prenotati (formato HH:MM)
        $booked = $this->getBookedSlots($db, $date);

        // Recupera durata slot da doc_settings
        $duration = 15;
        try {
            $settingsTable = $this->prx . 'doc_settings';
            $stmt = $db->prepare("SELECT value FROM {$settingsTable} WHERE name = 'slot_length' LIMIT 1");
            $stmt->execute();
            $val = $stmt->fetchColumn();
            if ($val !== false && (int) $val > 0) {
                $duration = (int) $val;
            }
        } catch (\Throwable) {
            $duration = 15;
        }

        // Genera tutti gli slot teorici
        $allSlots = [];
        foreach ($rules as $rule) {
            if ((int) ($rule['booking'] ?? 1) === 0) {
                // Accesso libero, non si generano slot di prenotazione
                continue;
            }

            $slots = $this->generateSlots(
                (string) ($rule['start_time'] ?? '08:00'),
                (string) ($rule['end_time'] ?? '12:00'),
                $duration
            );

            $allSlots = array_merge($allSlots, $slots);
        }

        // Rimuove duplicati e ordina
        $allSlots = array_values(array_unique($allSlots));
        sort($allSlots);

        // Elimina gli slot già prenotati
        $available = array_filter($allSlots, static function (string $slot) use ($booked): bool {
            return !in_array($slot, $booked, true);
        });

        // Se la data è oggi, rimuove gli orari già passati
        $today = date('Y-m-d');
        if ($date === $today) {
            $now = date('H:i');
            $available = array_filter($available, static function (string $slot) use ($now): bool {
                return $slot > $now;
            });
        }

        return array_values($available);
    }

    /**
     * Valida il file allegato dal form di contatto.
     *
     * @param array<string, mixed> $file
     * @return true|string Restituisce true se valido, oppure il codice di errore
     */
    public function validateAttachment(array $file): true|string
    {
        // Nessun allegato caricato: valido perché opzionale
        if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return true;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return 'uploadError';
        }

        // Dimensione massima 10 MB
        if ((int) ($file['size'] ?? 0) > 10 * 1024 * 1024) {
            return 'fileTooLarge';
        }

        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'heic', 'heif'];
        $extension = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            return 'invalidFileType';
        }

        $tmpName = (string) ($file['tmp_name'] ?? '');
        if (!is_file($tmpName)) {
            return 'uploadError';
        }

        $allowedMime = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/heic',
            'image/heif',
            'application/octet-stream',
        ];

        $mime = mime_content_type($tmpName);
        if ($mime === false || !in_array($mime, $allowedMime, true)) {
            return 'invalidFileType';
        }

        return true;
    }
}
