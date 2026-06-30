<?php

spl_autoload_register(function ($class) {
    require "../admin/class/$class.php";
});

$database = new Database();
$db = $database->getConnection();

/*
|--------------------------------------------------------------------------
| CONFIGURAZIONE
|--------------------------------------------------------------------------
*/

$from = "prenotazioni@dottoressacrosetto.it";

$to = [
    "davidemasera@gmail.com"
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
    FROM doc_bookings b
    LEFT JOIN doc_reason r
        ON b.reason_id = r.id
    WHERE DATE(b.slot_booked) = CURDATE()
    ORDER BY b.slot_booked ASC
");

$stmt->execute();

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| OGGETTO
|--------------------------------------------------------------------------
*/

$subject = "Agenda appuntamenti - " . date('d/m/Y');

/*
|--------------------------------------------------------------------------
| HEADER EMAIL
|--------------------------------------------------------------------------
*/

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "From: {$from}\r\n";
$headers .= "Reply-To: {$from}\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

/*
|--------------------------------------------------------------------------
| BODY EMAIL
|--------------------------------------------------------------------------
*/

$output = '
<html>
<head>
<meta charset="utf-8">
</head>
<body style="font-family:Arial,Helvetica,sans-serif;background:#f5f5f5;padding:30px;">

<div style="max-width:700px;margin:auto;background:#ffffff;padding:30px;border-radius:8px;">

<h2 style="margin-top:0;">
Agenda appuntamenti del ' . date('d/m/Y') . '
</h2>

<p>
Totale appuntamenti:
<strong>' . count($bookings) . '</strong>
</p>

<hr>
';

if (count($bookings) == 0) {

    $output .= '
    <p>
        Non risultano appuntamenti per la giornata odierna.
    </p>
    ';
} else {

    foreach ($bookings as $booking) {

        $output .= '

        <div style="
            border:1px solid #ddd;
            border-radius:6px;
            padding:15px;
            margin-bottom:20px;
        ">

            <h3 style="margin:0 0 10px 0;color:#0d6efd;">
                ' . date('H:i', strtotime($booking['slot_booked'])) . '
            </h3>

            <p><strong>Paziente:</strong> ' . htmlspecialchars($booking['name']) . '</p>

            <p><strong>Telefono:</strong> ' . htmlspecialchars($booking['phone']) . '</p>

            <p><strong>Email:</strong> ' . htmlspecialchars($booking['email']) . '</p>
            
            <p><strong>Motivazione:</strong> ' .
            htmlspecialchars($booking['reason'] ?? '-') .
            '</p>

            <p><strong>Note:</strong><br>' .
            nl2br(htmlspecialchars($booking['notes'])) .
            '</p>

        </div>

        ';
    }
}

$output .= '

</div>

</body>
</html>
';

/*
|--------------------------------------------------------------------------
| INVIO EMAIL
|--------------------------------------------------------------------------
*/

$error = false;

foreach ($to as $email) {

    if (!mail($email, $subject, $output, $headers)) {
        $error = true;
    }
}

/*
|--------------------------------------------------------------------------
| LOG
|--------------------------------------------------------------------------
*/

if ($error) {

    error_log("Errore invio agenda appuntamenti del " . date('Y-m-d'));
}

exit;
