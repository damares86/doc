<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $file = __DIR__ . "/../admin/class/{$class}.php";
    if (is_file($file)) {
        require_once $file;
    }
});

require_once __DIR__ . '/../admin/core/prefix.php';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    error_log('Database connection failed in checkScadenze cron.');
    exit(1);
}

$prx = !empty($prefix) ? $prefix . '_' : '';

/*
|--------------------------------------------------------------------------
| CONFIGURAZIONE
|--------------------------------------------------------------------------
*/

$from = 'info@dmweblab.com';
$to = [
    'dottoressa.crosetto@gmail.com',
];

/*
|--------------------------------------------------------------------------
| RECUPERO APPUNTAMENTI DI OGGI
|--------------------------------------------------------------------------
*/

$stmt = $db->prepare("
    SELECT
        b.slot_booked,
        b.name,
        b.phone,
        b.email,
        b.notes,
        r.name AS reason
    FROM {$prx}doc_bookings b
    LEFT JOIN {$prx}doc_reason r
        ON b.reason_id = r.id
    WHERE DATE(b.slot_booked) = CURDATE()
    ORDER BY b.slot_booked ASC
");

$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

/*
|--------------------------------------------------------------------------
| OGGETTO E HEADER
|--------------------------------------------------------------------------
*/

$subject = 'Agenda appuntamenti - ' . date('d/m/Y');

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "From: {$from}\r\n";
$headers .= "Reply-To: {$from}\r\n";
$headers .= 'X-Mailer: PHP/' . phpversion();

/*
|--------------------------------------------------------------------------
| BODY EMAIL
|--------------------------------------------------------------------------
*/

$totalCount = count($bookings);
$todayFormatted = date('d/m/Y');

$output = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body style="font-family:Arial,Helvetica,sans-serif;background:#f5f5f5;padding:30px;">

<div style="max-width:700px;margin:auto;background:#ffffff;padding:30px;border-radius:8px;">
    <h2 style="margin-top:0; color:#333;">Agenda appuntamenti del {$todayFormatted}</h2>
    <p>Totale appuntamenti: <strong>{$totalCount}</strong></p>
    <hr style="border:none; border-top:1px solid #ddd; margin:20px 0;">
HTML;

if ($totalCount === 0) {
    $output .= '<p style="color:#666;">Non risultano appuntamenti per la giornata odierna.</p>';
} else {
    foreach ($bookings as $booking) {
        $slotTime = date('H:i', (int) strtotime((string) $booking['slot_booked']));
        $patientName = htmlspecialchars((string) ($booking['name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $phone = htmlspecialchars((string) ($booking['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars((string) ($booking['email'] ?? ''), ENT_QUOTES, 'UTF-8');
        $reason = htmlspecialchars((string) ($booking['reason'] ?? '-'), ENT_QUOTES, 'UTF-8');
        $notes = nl2br(htmlspecialchars((string) ($booking['notes'] ?? ''), ENT_QUOTES, 'UTF-8'));

        $output .= <<<HTML
        <div style="border:1px solid #ddd; border-radius:6px; padding:15px; margin-bottom:20px;">
            <h3 style="margin:0 0 10px 0; color:#0d6efd;">{$slotTime}</h3>
            <p style="margin:4px 0;"><strong>Paziente:</strong> {$patientName}</p>
            <p style="margin:4px 0;"><strong>Telefono:</strong> {$phone}</p>
            <p style="margin:4px 0;"><strong>Email:</strong> {$email}</p>
            <p style="margin:4px 0;"><strong>Motivazione:</strong> {$reason}</p>
            <p style="margin:4px 0;"><strong>Note:</strong><br>{$notes}</p>
        </div>
HTML;
    }
}

$output .= <<<HTML
</div>
</body>
</html>
HTML;

/*
|--------------------------------------------------------------------------
| INVIO EMAIL
|--------------------------------------------------------------------------
*/

$error = false;
foreach ($to as $destEmail) {
    if (!mail($destEmail, $subject, $output, $headers)) {
        $error = true;
    }
}

if ($error) {
    error_log('Errore invio agenda appuntamenti del ' . date('Y-m-d'));
}

exit(0);
