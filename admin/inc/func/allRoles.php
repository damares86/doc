<?php

$setting->name = "role_redirect";
$stmt = $setting->showAllWhere('id', ['name']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$redir = $row['value'];

?>
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3><?= $role_all_header ?></h3>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.php"><?= $common_dashboard ?></a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <?= $role_all_header ?>
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
    <div class="card-header"><?= $role_all_title ?> &nbsp; &nbsp; &nbsp;
      <a href="index.php?p=addRole" class="btn icon icon-left btn-success shadow"><i data-feather="plus-circle"></i> <?= $role_all_add ?></a>
    </div>
    <div class="card-body">
      <table class="table" id="table">
        <thead>
          <tr>
            <th><?= $common_rolename ?></th>
            <th><?= $common_section_auth ?></th>
            <?php
            if ($redir == 1) {
            ?>
              <th><?= $common_redirect ?></th>
            <?php
            }
            ?>
            <th><?= $common_number_user ?></th>
            <th><?= $common_actions ?></th>
          </tr>
        </thead>
        <tbody>

          <?php
          $allroles = $role->showAll('id');
          while ($row1 = $allroles->fetch(PDO::FETCH_ASSOC)) {
            extract($row1);

            // hide sections for non root users
            if ($_SESSION['role_id'] == 1) {
              $exclude_roles = [1];
            } else {
              $exclude_roles = [1, 2];
            }

            if (in_array($row1['id'], $exclude_roles)) {
              continue;
            }

          ?>
            <tr>
              <td><?= $row1['rolename'] ?></td>
              <td>
                <?php
                $rolessection->role_id = $row1['id'];
                $permissions = $rolessection->showAllPermission();
                $row2 = $permissions->fetch(PDO::FETCH_ASSOC);
                extract($row2);
                if (!is_null($row2['section_id'])) {
                  $section_arr = explode(',', $row2['section_id']);
                  foreach ($section_arr as $item) {

                    $section->id = $item;
                    $stmt1 = $section->showById('sectionParent');
                    if ($lang == "en") {
                      echo $stmt1['label'] . "<br>";
                    } else {
                      $locale_label = strtolower($stmt1['label']);
                      $locale_label = str_replace(" ", "_", $locale_label);
                      $locale_label = "label_$locale_label";
                      $section_label = $$locale_label;
                      echo $section_label . "<br>";
                    }
                  }
                }
                ?>
              </td>
              <?php
              if ($redir == 1) {
              ?>
                <td><?= $row1['redirect'] ?></td>
              <?php
              }
              ?>
              <td>
                <?php
                $accountroles->role_id = $row1['id'];
                $roleNum = $accountroles->countRoleAccounts();
                echo $roleNum;
                ?>
              </td>
              <td>
                <a href="index.php?p=editRole&idToMod=<?= $row['id'] ?>" class="btn icon btn-warning shadow edit-link" data-base-url="index.php?p=editRole&idToMod=<?= $row1['id'] ?>"><i class="bi bi-pencil-square"></i></a>
                &nbsp; &nbsp;
                <a href="#" class="btn icon btn-danger shadow" data-bs-toggle="modal" data-bs-target="#danger<?= $row1['id'] ?>"><i class="bi bi-trash"></i>
                </a>
                <!--Danger theme Modal -->
                <div class="modal fade text-left" id="danger<?= $row1['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel120" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                      <div class="modal-header bg-danger">
                        <h5 class="modal-title white" id="myModalLabel120">
                          <?= $common_modal_title_sure ?>
                        </h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                          <i data-feather="x"></i>
                        </button>
                      </div>
                      <div class="modal-body">
                        <?= $role_all_modal_body ?>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                          <i class="bx bx-x d-block d-sm-none"></i>
                          <span class="d-none d-sm-block"><?= $common_modal_cancel ?></span>
                        </button>
                        <span class="d-none d-sm-block"><a href="core/mngRoles.php?idToDel=<?= $row1['id'] ?>" class="btn btn-danger ml-1"><?= $common_modal_confirm ?></a></span>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>




          <?php
          }


          ?>



        </tbody>
      </table>
    </div>
  </div>
</section>