<?php

declare(strict_types=1);



if (!isset($calendar) || !($calendar instanceof Calendar)) {
    $calendar = new Calendar($db);
    if (!empty($prefix)) {
        $calendar->prx = $prefix . '_';
    }
}$calendar->table = 'calendar_cat';
$events_cat = $calendar->showAll('id');
?>
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3><?= htmlspecialchars((string) ($cal_event_cat_header ?? 'Calendar Categories'), ENT_QUOTES, 'UTF-8') ?></h3>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.php"><?= htmlspecialchars((string) ($common_dashboard ?? 'Dashboard'), ENT_QUOTES, 'UTF-8') ?></a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <?= htmlspecialchars((string) ($cal_event_cat_all_header ?? 'All Categories'), ENT_QUOTES, 'UTF-8') ?>
          </li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<br>

<section class="section">
  <div class="card shadow">
    <div class="card-header"><?= htmlspecialchars((string) ($cal_event_cat_all_header ?? 'All Categories'), ENT_QUOTES, 'UTF-8') ?> &nbsp; &nbsp; &nbsp;
      <a href="index.php?p=addCalendar" class="btn icon icon-left btn-success shadow"><i data-feather="plus-circle"></i> <?= htmlspecialchars((string) ($cal_event_add_cat_header ?? 'Add Category'), ENT_QUOTES, 'UTF-8') ?></a>
    </div>
    <div class="card-body">
      <table class="table" id="table">
        <thead>
          <tr>
            <th><?= htmlspecialchars((string) ($cal_event_edit_cat_name_header ?? 'Category Name'), ENT_QUOTES, 'UTF-8') ?></th>
            <th><?= htmlspecialchars((string) ($cal_event_edit_cat_color_header ?? 'Category Color'), ENT_QUOTES, 'UTF-8') ?></th>
            <th><?= htmlspecialchars((string) ($common_actions ?? 'Actions'), ENT_QUOTES, 'UTF-8') ?></th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($events_cat instanceof PDOStatement) {
              while ($row = $events_cat->fetch(PDO::FETCH_ASSOC)) {
                  $catId = (int) ($row['id'] ?? 0);
                  $catName = (string) ($row['cat_name'] ?? '');
                  $catColor = (string) ($row['cat_color'] ?? '#008db1');
                  ?>
                  <tr>
                    <td><?= htmlspecialchars($catName, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><span class="p-2 text-white rounded" style="background-color: <?= htmlspecialchars($catColor, ENT_QUOTES, 'UTF-8') ?>;"><?= htmlspecialchars($catColor, ENT_QUOTES, 'UTF-8') ?></span></td>
                    <td>
                      <?php if ($catId > 1) : ?>
                        <a href="index.php?p=editCalendar&idToMod=<?= $catId ?>" class="btn icon btn-warning shadow edit-link" data-base-url="index.php?p=editCalendar&idToMod=<?= $catId ?>"><i class="bi bi-pencil-square"></i></a>
                        &nbsp; &nbsp;
                        <a href="#" class="btn icon btn-danger shadow" data-bs-toggle="modal" data-bs-target="#danger<?= $catId ?>"><i class="bi bi-trash"></i></a>
                        
                        <div class="modal fade text-left" id="danger<?= $catId ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?= $catId ?>" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                              <div class="modal-header bg-danger">
                                <h5 class="modal-title white" id="myModalLabel<?= $catId ?>">
                                  <?= htmlspecialchars((string) ($common_modal_title_sure ?? 'Are you sure?'), ENT_QUOTES, 'UTF-8') ?>
                                </h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                  <i data-feather="x"></i>
                                </button>
                              </div>
                              <div class="modal-body">
                                <?= htmlspecialchars((string) ($cal_event_modal_body ?? 'Do you really want to delete this category?'), ENT_QUOTES, 'UTF-8') ?>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                  <span class="d-none d-sm-block"><?= htmlspecialchars((string) ($common_modal_cancel ?? 'Cancel'), ENT_QUOTES, 'UTF-8') ?></span>
                                </button>
                                <a href="core/mngCalendar.php?idToDel=<?= $catId ?>" class="btn btn-danger ml-1">
                                  <?= htmlspecialchars((string) ($common_modal_confirm ?? 'Confirm'), ENT_QUOTES, 'UTF-8') ?>
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      <?php endif; ?>
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