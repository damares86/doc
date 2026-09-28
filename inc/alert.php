<?php

declare(strict_types=1);

$msg = (string) filter_input(INPUT_GET, 'msg');
if ($msg !== '') {
    $alertLabel = "msg_{$msg}";
    $msgText = isset($$alertLabel) ? (string) $$alertLabel : htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
?>
    <div class="alert alert-success alert-dismissible fade show shadow my-3 text-center" role="alert">
      <i class="bi bi-check-circle"></i>
      <?= htmlspecialchars($msgText, ENT_QUOTES, 'UTF-8') ?>
      <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}

$err = (string) filter_input(INPUT_GET, 'err');
if ($err !== '') {
    $alertLabel = "err_{$err}";
    $errText = isset($$alertLabel) ? (string) $$alertLabel : htmlspecialchars($err, ENT_QUOTES, 'UTF-8');
?>
    <div class="alert alert-danger alert-dismissible fade show shadow my-3 text-center" role="alert">
      <i class="bi bi-x-circle"></i>
      <?= htmlspecialchars($errText, ENT_QUOTES, 'UTF-8') ?>
      <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}