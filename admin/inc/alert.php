<?php

declare(strict_types=1);

$msg = filter_input(INPUT_GET, 'msg', FILTER_DEFAULT);
if ($msg) {
    $msg = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $msg);
    $alert_label = "msg_{$msg}";
    $displayText = isset($$alert_label) ? (string) $$alert_label : $msg;
    ?>
    <div class="alert alert-success alert-dismissible fade show shadow" role="alert">
      <i class="bi bi-check-circle"></i>
      <?= htmlspecialchars($displayText, ENT_QUOTES, 'UTF-8') ?>
      <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
}

$err = filter_input(INPUT_GET, 'err', FILTER_DEFAULT);
if ($err) {
    $err = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $err);
    $alert_label = "err_{$err}";
    $displayText = isset($$alert_label) ? (string) $$alert_label : $err;
    ?>
    <div class="alert alert-danger alert-dismissible fade show shadow" role="alert">
      <i class="bi bi-x-circle"></i>
      <?= htmlspecialchars($displayText, ENT_QUOTES, 'UTF-8') ?>
      <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
}