<?php

    if(filter_input(INPUT_GET,"msg")){
    ?>
    <div class="alert custom-alert-2 alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle"></i>
      <?php
      $msg = filter_input(INPUT_GET,"msg");
      $alert_label = "msg_$msg";
      echo $$alert_label;
      ?>
      <button class="btn btn-close position-relative p-1 ms-auto" type="button" data-bs-dismiss="alert"
        aria-label="Close"></button>
    </div>
    <?php
    }

    if(filter_input(INPUT_GET,"err")){
      ?>
      <div class="alert custom-alert-2 alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-circle"></i>
        <?php
        $err= filter_input(INPUT_GET,"err");
        $alert_label = "err_$err";
        echo $$alert_label;
        ?>
        <button class="btn btn-close position-relative p-1 ms-auto" type="button" data-bs-dismiss="alert"
          aria-label="Close"></button>
      </div>
      <?php
      }
?>