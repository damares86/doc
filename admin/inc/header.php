<?php

declare(strict_types=1);

// check if database is configured
if (!is_file(__DIR__ . '/../class/Database.php')) {
    require_once __DIR__ . '/dbdata.php';
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// check if the user is logged in
require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars((string) ($common_dashboard ?? 'Dashboard'), ENT_QUOTES, 'UTF-8') ?> - damares</title>

  <!--
    ##############    Damares    ###############
    #                                          #
    #    A backend project by DM WebLab        #
    #   Website: https://www.dmweblab.com      #
    #   GitHub: https://github.com/damares86   #
    #                                          #
    ############################################
    -->

  <link rel="stylesheet" href="assets/css/main/app.css" />
  <link rel="stylesheet" href="assets/css/main/app-dark.css" />
  <link rel="shortcut icon" href="assets/images/logo/favicon.ico" type="image/x-icon" />
  <link rel="shortcut icon" href="assets/images/logo/favicon.ico" type="image/png" />
  <link rel="stylesheet" href="assets/extensions/choices.js/public/assets/styles/choices.css" />
  <link rel="stylesheet" href="assets/css/pages/buttons.dataTables.min.css">

  <?php
  $cssFiles = glob('assets/css/*.css') ?: [];
  foreach ($cssFiles as $row) {
      ?>
      <link rel="stylesheet" href="<?= htmlspecialchars($row, ENT_QUOTES, 'UTF-8') ?>" />
      <?php
  }
  ?>
  <script src="assets/extensions/jquery/jquery.min.js"></script>

</head>