<?php
require "inc/header.php";

$selVert = '';
$setting->name = "layout";
$stmt = $setting->showAllWhere('id', ['name']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$layout = $row['value'];

?>

<body>
  <style>
    /* Nascondi la tabella inizialmente */
    #table_wrapper {
      display: none;
    }
  </style>
  <!-- Overlay con lo spinner -->
  <div id="preloader">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden"><?= $common_loading ?>...</span>
    </div>
  </div>
  <script>
    // Nascondi l'overlay e mostra il contenuto una volta che la pagina è completamente caricata
    window.addEventListener('load', function() {
      document.getElementById('preloader').style.display = 'none';
      document.getElementById('app').style.display = 'block';
    });
  </script>
  <div id="app">
    <script src="assets/js/initTheme.js"></script>

    <?php

    $classHoriz = '';

    if ($layout == 'h') {
      $classHoriz = ' class="layout-horizontal"';
    } else if ($layout == 'v') {
      include "inc/sidebar.php";
    }
    ?>

    <div id="main" <?= $classHoriz ?>>
      <?php
      if ($layout == 'h') {
        include "inc/topbar.php";
      ?>
        <div class="content-wrapper container">
        <?php
      }
        ?>

        <?php
        if ($page == 'index') {
        ?>
          <div class="page-heading">
            <h3 class="d-inline">Damares <?= $common_dashboard ?></h3>
          </div>
        <?php
        }
        ?>
        <div class="page-content">

          <?php

          require "inc/alert.php";
          
          if ($page != 'index') {
            include "inc/func/$page.php";
          } else {

          ?>

            <section class="row">
              <?php
              $homeBlocks = $home->showAll('id');
              foreach ($homeBlocks as $block) {
              ?>
                <div class="col-12 col-lg-<?= $block['size'] ?>">
                  <div class="card shadow">
                    <?php
                    require "inc/home/" . $block['content'];
                    ?>
                  </div>
                </div>

              <?php
              }
              ?>
            </section>

          <?php
          }
          ?>
        </div>
        </div>
        <?php
        if ($layout == 'h') {
        ?>
    </div>
  <?php
        }
  ?>

  <?php
  include "inc/footer.php";
  ?>