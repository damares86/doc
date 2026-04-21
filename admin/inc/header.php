<?php

// check if database is configured

if (!is_file('class/Database.php')) {
  require "inc/dbdata.php";
  exit;
}

session_start();

// check if the user is logged in
require __DIR__ . "/config.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $common_dashboard ?> - damares</title>

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

  foreach (glob("assets/css/*.css") as $row) {
  ?>
    <link rel="stylesheet" href="<?= $row ?>" />
  <?php
  }
  ?>
  <script src="assets/extensions/jquery/jquery.min.js"></script>

</head>