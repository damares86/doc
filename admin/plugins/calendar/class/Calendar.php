<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Calendar extends Common
{
    public string $table = 'calendar_events';
    public ?string $event_title = null;
    public ?string $page_origin = null;
    public int|string|null $cat_id = null;
    public ?string $color = null;
    public ?string $cat_name = null;
    public ?string $cat_color = null;
    public ?string $start = null;
    public ?string $end = null;
    public ?string $note = null;
    public ?string $url = null;
    public int $is_recurring = 0;
    public ?string $recurrence_freq = null;
    public int $recurrence_interval = 1;
    public ?string $recurrence_days = null;
    public ?string $recurrence_until = null;
    public ?int $recurrence_count = null;
    public ?string $rrule = null;

    /**
     * Costruisce una stringa RRULE standard RFC 5545 compatibile con iCalendar e FullCalendar.
     */
    public static function buildRruleString(
        string $freq,
        int $interval = 1,
        ?string $byDay = null,
        ?string $until = null,
        ?int $count = null
    ): string {
        $parts = ['FREQ=' . strtoupper($freq), 'INTERVAL=' . max(1, $interval)];
        if (!empty($byDay)) {
            $parts[] = 'BYDAY=' . strtoupper($byDay);
        }
        if (!empty($until)) {
            try {
                $untilDt = new DateTime($until);
                $parts[] = 'UNTIL=' . $untilDt->format('Ymd\THis\Z');
            } catch (Throwable) {
                // Ignore invalid datetime string
            }
        } elseif ($count !== null && $count > 0) {
            $parts[] = 'COUNT=' . $count;
        }
        return implode(';', $parts);
    }
}