<?php

declare(strict_types=1);

$setting->name = 'role_redirect';
$stmt = $setting->showAllWhere('id', ['name']);
$row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
$redir = (string) ($row['value'] ?? '0');
?>
<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3><?= htmlspecialchars((string) ($role_all_header ?? 'Roles'), ENT_QUOTES, 'UTF-8') ?></h3>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.php"><?= htmlspecialchars((string) ($common_dashboard ?? 'Dashboard'), ENT_QUOTES, 'UTF-8') ?></a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <?= htmlspecialchars((string) ($role_all_header ?? 'Roles'), ENT_QUOTES, 'UTF-8') ?>
          </li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<br>

<section class="section">
  <div class="card shadow">
    <div class="card-header">
      <?= htmlspecialchars((string) ($role_all_title ?? 'All roles'), ENT_QUOTES, 'UTF-8') ?> &nbsp; &nbsp; &nbsp;
      <a href="index.php?p=addRole" class="btn icon icon-left btn-success shadow">
        <i data-feather="plus-circle"></i> <?= htmlspecialchars((string) ($role_all_add ?? 'Add role'), ENT_QUOTES, 'UTF-8') ?>
      </a>
    </div>
    <div class="card-body">
      <table class="table" id="table">
        <thead>
          <tr>
            <th><?= htmlspecialchars((string) ($common_rolename ?? 'Role name'), ENT_QUOTES, 'UTF-8') ?></th>
            <th><?= htmlspecialchars((string) ($common_section_auth ?? 'Authorized sections'), ENT_QUOTES, 'UTF-8') ?></th>
            <?php if ($redir === '1'): ?>
              <th><?= htmlspecialchars((string) ($common_redirect ?? 'Redirect'), ENT_QUOTES, 'UTF-8') ?></th>
            <?php endif; ?>
            <th><?= htmlspecialchars((string) ($common_number_user ?? 'Accounts count'), ENT_QUOTES, 'UTF-8') ?></th>
            <th><?= htmlspecialchars((string) ($common_actions ?? 'Actions'), ENT_QUOTES, 'UTF-8') ?></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $allroles = $role->showAll('id');
          if ($allroles instanceof PDOStatement) {
              $currentRoleId = (int) ($_SESSION['role_id'] ?? 0);
              $exclude_roles = ($currentRoleId === 1) ? [1] : [1, 2];

              while ($row1 = $allroles->fetch(PDO::FETCH_ASSOC)) {
                  $roleId = (int) ($row1['id'] ?? 0);
                  if (in_array($roleId, $exclude_roles, true)) {
                      continue;
                  }
                  ?>
                  <tr>
                    <td><?= htmlspecialchars((string) ($row1['rolename'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                      <?php
                      $rolessection->role_id = $roleId;
                      $permissions = $rolessection->showAllPermission();
                      $row2 = $permissions ? $permissions->fetch(PDO::FETCH_ASSOC) : null;
                      if ($row2 && !empty($row2['section_id'])) {
                          $section_arr = explode(',', (string) $row2['section_id']);
                          foreach ($section_arr as $item) {
                              $section->id = $item;
                              $stmt1 = $section->showById('section_parent');
                              if ($stmt1 && isset($stmt1['label'])) {
                                  $secLabel = (string) $stmt1['label'];
                                  if (($lang ?? 'en') !== 'en') {
                                      $locale_label = 'label_' . str_replace(' ', '_', strtolower($secLabel));
                                      if (isset($$locale_label)) {
                                          $secLabel = (string) $$locale_label;
                                      }
                                  }
                                  echo htmlspecialchars($secLabel, ENT_QUOTES, 'UTF-8') . '<br>';
                              }
                          }
                      }
                      ?>
                    </td>
                    <?php if ($redir === '1'): ?>
                      <td><?= htmlspecialchars((string) ($row1['redirect'] ?? 'none'), ENT_QUOTES, 'UTF-8') ?></td>
                    <?php endif; ?>
                    <td>
                      <?php
                      $accountroles->role_id = $roleId;
                      echo (int) $accountroles->countRoleAccounts();
                      ?>
                    </td>
                    <td>
                      <a href="index.php?p=editRole&idToMod=<?= $roleId ?>" class="btn icon btn-warning shadow edit-link" data-base-url="index.php?p=editRole&idToMod=<?= $roleId ?>">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      &nbsp; &nbsp;
                      <a href="#" class="btn icon btn-danger shadow" data-bs-toggle="modal" data-bs-target="#danger<?= $roleId ?>">
                        <i class="bi bi-trash"></i>
                      </a>

                      <!-- Danger theme Modal -->
                      <div class="modal fade text-left" id="danger<?= $roleId ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?= $roleId ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                          <div class="modal-content">
                            <div class="modal-header bg-danger">
                              <h5 class="modal-title white" id="myModalLabel<?= $roleId ?>">
                                <?= htmlspecialchars((string) ($common_modal_title_sure ?? 'Are you sure?'), ENT_QUOTES, 'UTF-8') ?>
                              </h5>
                              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i data-feather="x"></i>
                              </button>
                            </div>
                            <div class="modal-body">
                              <?= htmlspecialchars((string) ($role_all_modal_body ?? 'Do you want to delete this role?'), ENT_QUOTES, 'UTF-8') ?>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block"><?= htmlspecialchars((string) ($common_modal_cancel ?? 'Cancel'), ENT_QUOTES, 'UTF-8') ?></span>
                              </button>
                              <a href="core/mngRoles.php?idToDel=<?= $roleId ?>" class="btn btn-danger ml-1">
                                <?= htmlspecialchars((string) ($common_modal_confirm ?? 'Confirm'), ENT_QUOTES, 'UTF-8') ?>
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
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