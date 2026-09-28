<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

$users = $account->showAll('id');
?>

<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3><?= htmlspecialchars((string) ($account_all_header ?? 'All accounts'), ENT_QUOTES, 'UTF-8') ?></h3>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.php"><?= htmlspecialchars((string) ($common_dashboard ?? 'Dashboard'), ENT_QUOTES, 'UTF-8') ?></a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <?= htmlspecialchars((string) ($account_all_header ?? 'All accounts'), ENT_QUOTES, 'UTF-8') ?>
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
    <div class="card-header">
      <?= htmlspecialchars((string) ($account_all_title ?? 'Accounts'), ENT_QUOTES, 'UTF-8') ?> &nbsp; &nbsp; &nbsp;
      <a href="index.php?p=addAccount" class="btn icon icon-left btn-success shadow">
        <i data-feather="plus-circle"></i> <?= htmlspecialchars((string) ($account_all_add ?? 'Add account'), ENT_QUOTES, 'UTF-8') ?>
      </a>
    </div>
    <div class="card-body">
      <table class="table" id="table">
        <thead>
          <tr>
            <th><?= htmlspecialchars((string) ($common_username ?? 'Username'), ENT_QUOTES, 'UTF-8') ?></th>
            <th><?= htmlspecialchars((string) ($common_email ?? 'Email'), ENT_QUOTES, 'UTF-8') ?></th>
            <th><?= htmlspecialchars((string) ($common_role ?? 'Role'), ENT_QUOTES, 'UTF-8') ?></th>
            <th><?= htmlspecialchars((string) ($common_lastLogin ?? 'Last login'), ENT_QUOTES, 'UTF-8') ?></th>
            <th><?= htmlspecialchars((string) ($common_actions ?? 'Actions'), ENT_QUOTES, 'UTF-8') ?></th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($users instanceof PDOStatement) {
              while ($row = $users->fetch(PDO::FETCH_ASSOC)) {
                  $accountId = (int) ($row['id'] ?? 0);
                  $currentRoleId = (int) ($_SESSION['role_id'] ?? 0);

                  if ($accountId > 2 || $currentRoleId === 1) {
                      $accountroles->account_id = $accountId;
                      $roleId = $accountroles->showAccountRolesId();
                      $roleName = '—';
                      if ($roleId) {
                          $role->id = $roleId;
                          $roleName = (string) ($role->showRolenameById() ?? '—');
                      }
                      ?>
                      <tr>
                        <td><?= htmlspecialchars((string) ($row['username'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($row['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($roleName, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($row['last_login'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                          <a href="index.php?p=editAccount&idToMod=<?= $accountId ?>" class="btn icon btn-warning shadow edit-link" data-base-url="index.php?p=editAccount&idToMod=<?= $accountId ?>">
                            <i class="bi bi-pencil-square"></i>
                          </a>

                          &nbsp; &nbsp;
                          <a href="#" class="btn icon btn-danger shadow" data-bs-toggle="modal" data-bs-target="#danger<?= $accountId ?>">
                            <i class="bi bi-trash"></i>
                          </a>

                          <!-- Danger theme Modal -->
                          <div class="modal fade text-left" id="danger<?= $accountId ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?= $accountId ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                              <div class="modal-content">
                                <div class="modal-header bg-danger">
                                  <h5 class="modal-title white" id="myModalLabel<?= $accountId ?>">
                                    <?= htmlspecialchars((string) ($common_modal_title_sure ?? 'Are you sure?'), ENT_QUOTES, 'UTF-8') ?>
                                  </h5>
                                  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <i data-feather="x"></i>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <?= htmlspecialchars((string) ($account_all_modal_body ?? 'Do you want to delete this account?'), ENT_QUOTES, 'UTF-8') ?>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block"><?= htmlspecialchars((string) ($common_modal_cancel ?? 'Cancel'), ENT_QUOTES, 'UTF-8') ?></span>
                                  </button>
                                  <a href="core/mngAccounts.php?idToDel=<?= $accountId ?>" class="btn btn-danger ml-1">
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
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
