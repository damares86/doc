<?php
$mc->table = 'mc_pages';
$mc->no_del = 1;
$pages = $mc->showAllWhere('id', ['no_del']);
?>

<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3><?= $alldefault_header ?></h3>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.php"><?= $common_dashboard ?></a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <?= $alldefault_header ?>
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
    <div class="card-header"><?= $alldefault_title ?> &nbsp; &nbsp; &nbsp;
    </div>
    <div class="card-body">

      <table class="table" id="table">
        <thead>
          <tr>
            <th><?= $alldefault_name ?></th>
            <th><?= $alldefault_link ?></th>
            <th><?= $common_actions ?></th>
          </tr>
        </thead>
        <tbody>

          <?php
          while ($row = $pages->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            if ($row['id'] !== 1) {
              $str = $row['page_name'];
              $str = str_replace('_', ' ', $str);

              $str = ucfirst($str);
          ?>
              <tr>
                <td><?= $str  ?></td>
                <td>
                  <a href="../<?= $row['page_name'] ?>.php"><?= $common_link ?></a>

                </td>

                <td>
                  <a href="index.php?p=editDefaultPage&idToMod=<?= $row['id'] ?>" class="btn icon btn-warning shadow edit-link" data-base-url="index.php?p=editDefaultPage&idToMod=<?= $row['id'] ?>">
                    <i class="bi bi-pencil-square"></i>
                  </a>

                </td>
              </tr>




          <?php
            }
          }
          ?>



        </tbody>
      </table>
    </div>
  </div>
</section>