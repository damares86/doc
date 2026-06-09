<?php

if (!filter_input(INPUT_GET, 'token') || !filter_input(INPUT_GET, 'email')) {
  header("Location: index.php");
  exit;
}

$token = filter_input(INPUT_GET, 'token');
$email = filter_input(INPUT_GET, 'email');

require 'inc/header.php';
require 'inc/sidebar.php';

$page_name = 'index';

$doc->table = 'doc_bookings';
$doc->token = $token ;
$doc->email = $email ;


$stmt = $doc->showAllWhere('id',['email','token']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if(!is_array($row)){
  header("Location: index.php?err=noBooking");
  exit;
}else{
  extract($row);
}

$slot =  date('d/m/Y H:i', strtotime($row['slot_booked']));

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
        <div class="card-body text-dark  p-5">
          <h4 class="mb-4  text-center">Attenzione!</h4>
          <span class="text-left text-dark">
            State per cancellare l'appuntamento. Di seguito i dati:<br><br>
            <ul>
              <li>Nome paziente: <b><?= $row['name'] ?></b></li>
              <li>Appuntamento: <b><?= $slot ?></b></li>
            </ul>
            <br>
            Si è sicuri di voler procedere?<br>
          </span>
          <span class="text-center">
            <form action="admin/core/mngBooking.php" method="POST">

              <input type="hidden" name="operation" value="deleteEmail">
              <input type="hidden" name="token" value="<?= $token ?>">
              <input type="hidden" name="email" value="<?= $email ?>">

              <button class="btn btn-danger text-center mt-3">
                Elimina
              </button>
            </form>
          </span>
        </div>
      </div>


      <div class="pb-3"></div>
    </div>

    <?php
    require 'inc/nav.php';
    require 'inc/footer.php';
    ?>