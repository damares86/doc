<?php

declare(strict_types=1);

require __DIR__ . '/coreConfig.php';

header('Content-Type: application/json; charset=utf-8');

$prx = $prx ?? '';

try {
    $query = "
    SELECT 
        e.id, 
        e.title, 
        e.start, 
        e.end, 
        e.note, 
        e.url, 
        e.cat_id,
        e.is_recurring,
        e.recurrence_freq,
        e.recurrence_interval,
        e.recurrence_days,
        e.recurrence_until,
        e.rrule,
        c.cat_color AS color,
        c.cat_name AS cat_name
    FROM {$prx}calendar_events e
    LEFT JOIN {$prx}calendar_cat c ON e.cat_id = c.id
    ";

    try {
        $stmt = $db->query($query);
    } catch (PDOException) {
        // Fallback for older database before ALTER TABLE
        $queryFallback = "
        SELECT 
            e.id, 
            e.title, 
            e.start, 
            e.end, 
            e.note, 
            e.url, 
            e.cat_id,
            c.cat_color AS color,
            c.cat_name AS cat_name
        FROM {$prx}calendar_events e
        LEFT JOIN {$prx}calendar_cat c ON e.cat_id = c.id
        ";
        $stmt = $db->query($queryFallback);
    }

    $events = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $isRecurring = (int) ($row['is_recurring'] ?? 0);
        $color = (string) ($row['color'] ?? '#008db1');
        $catName = (string) ($row['cat_name'] ?? '');

        if ($isRecurring === 1) {
            $startTs = strtotime((string) $row['start']);
            $endTs = strtotime((string) $row['end']);
            $diffSec = max(0, $endTs - $startTs);
            $hours = (int) floor($diffSec / 3600);
            $mins = (int) floor(($diffSec % 3600) / 60);
            $duration = sprintf('%02d:%02d', $hours, $mins);
            if ($duration === '00:00') {
                $duration = '01:00';
            }

            $rruleObj = [
                'freq'     => strtolower((string) ($row['recurrence_freq'] ?: 'weekly')),
                'interval' => max(1, (int) ($row['recurrence_interval'] ?: 1)),
                'dtstart'  => date('Y-m-d\TH:i:s', $startTs),
            ];

            if (!empty($row['recurrence_days'])) {
                $daysArr = array_filter(array_map('trim', explode(',', (string) $row['recurrence_days'])));
                if (!empty($daysArr)) {
                    $rruleObj['byweekday'] = array_values($daysArr);
                }
            }

            if (!empty($row['recurrence_until'])) {
                $rruleObj['until'] = date('Y-m-d\TH:i:s', strtotime((string) $row['recurrence_until']));
            }

            $events[] = [
                'id'                  => (int) $row['id'],
                'title'               => $row['title'],
                'rrule'               => $rruleObj,
                'duration'            => $duration,
                'note'                => $row['note'] ?? '',
                'url'                 => $row['url'] ?? '',
                'cat_id'              => $row['cat_id'],
                'color'               => $color,
                'cat_name'            => $catName,
                'is_recurring'        => 1,
                'recurrence_freq'     => $row['recurrence_freq'] ?: 'WEEKLY',
                'recurrence_interval' => max(1, (int) ($row['recurrence_interval'] ?: 1)),
                'recurrence_days'     => $row['recurrence_days'] ?? '',
                'recurrence_until'    => !empty($row['recurrence_until']) ? date('Y-m-d\TH:i', strtotime((string) $row['recurrence_until'])) : '',
                'original_start'      => date('Y-m-d\TH:i', $startTs),
                'original_end'        => date('Y-m-d\TH:i', $endTs),
            ];
        } else {
            $events[] = [
                'id'           => (int) $row['id'],
                'title'        => $row['title'],
                'start'        => $row['start'],
                'end'          => $row['end'],
                'note'         => $row['note'] ?? '',
                'url'          => $row['url'] ?? '',
                'cat_id'       => $row['cat_id'],
                'color'        => $color,
                'cat_name'     => $catName,
                'is_recurring' => 0,
            ];
        }
    }

    echo json_encode($events ?: []);
} catch (PDOException $e) {
    echo json_encode([]);
}
