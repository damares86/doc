<?php

?>

<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3>Visualizzazione prenotazioni</h3>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.php"><?= $common_dashboard ?></a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            Visualizzazione prenotazioni
          </li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<br>

<!-- Basic Tables start -->
<section class="section">
  <div class="card shadow">
    <div class="card-header">
      <h3>Prenotazioni</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <?php
        $monday = strtotime('monday this week');

        for ($i = 0; $i < 5; $i++) {

          $day = strtotime("+$i day", $monday);
        ?>
          
        <?php
          echo date('d/m', $day);
          echo "<br>";
        }
        ?>
      </div>
    </div>
  </div>
</section>