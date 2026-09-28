<?php

declare(strict_types=1);

$token = (string) filter_input(INPUT_GET, 'token');
$email = (string) filter_input(INPUT_GET, 'email');

if ($token === '' || $email === '') {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/inc/header.php';
require_once __DIR__ . '/inc/sidebar.php';

if (!isset($doc) || !($doc instanceof Doc)) {
    $doc = new Doc($db);
    if (!empty($prefix)) {
        $doc->prx = $prefix . '_';
    }
}

$page_name = 'index';

$doc->table = 'doc_bookings';
$doc->token = $token;
$doc->email = $email;

$stmt = $doc->showAllWhere('id', ['email', 'token']);
$row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;

if (!is_array($row) || empty($row['id'])) {
    header('Location: index.php?err=noBooking');
    exit;
}

$patientName = htmlspecialchars((string) ($row['name'] ?? ''), ENT_QUOTES, 'UTF-8');
$slotFormatted = !empty($row['slot_booked']) ? date('d/m/Y H:i', (int) strtotime((string) $row['slot_booked'])) : '';
?>

<style>
  @media (max-width: 768px) {
    .container.delete {
      --bs-gutter-x: 0;
    }
  }
</style>
<div class="page-content-wrapper">
  <div>
    <div class="pt-3"></div>

    <div id="home" class="container delete">
      <div class="card bg-warning mb-3">
        <div class="card-body text-dark p-5">
          <h4 class="mb-4 text-center">Attenzione!</h4>
          <div class="text-start text-dark">
            <p>State per cancellare l'appuntamento. Di seguito i dati:</p>
            <ul>
              <li>Nome paziente: <b><?= $patientName ?></b></li>
              <li>Appuntamento: <b><?= htmlspecialchars($slotFormatted, ENT_QUOTES, 'UTF-8') ?></b></li>
            </ul>
            <p>Si è sicuri di voler procedere?</p>
          </div>
          <div class="text-center">
            <form action="admin/core/mngBooking.php" method="POST">
              <input type="hidden" name="operation" value="deleteEmail">
              <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
              <input type="hidden" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">

              <button type="submit" class="btn btn-danger text-center mt-3 px-4">
                Conferma cancellazione
              </button>
            </form>
          </div>
        </div>
      </div>

      <div class="pb-3"></div>
    </div>
  </div>
</div>

<?php
require_once __DIR__ . '/inc/nav.php';
require_once __DIR__ . '/inc/footer.php';