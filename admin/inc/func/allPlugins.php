<?php

declare(strict_types=1);

require_once __DIR__ . '/../funcHeader.php';

$allplugins = $plugin->showAll('id');
?>

<section class="section">
  <div class="card shadow">
    <div class="card-header">
      <div class="row">
        <div class="col-md-5">
          <form class="form form-horizontal upload-form" action="core/mngPlugins.php" method="POST" enctype="multipart/form-data" data-parsley-validate>
            <div class="form-body">
              <div class="row">
                <div class="col-md-3">
                  <label><?= htmlspecialchars((string) ($plugin_all_add ?? 'Upload plugin'), ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                  <div class="form-group">
                    <div class="form-check mandatory">
                      <div class="position-relative">
                        <input class="form-control" type="file" id="formFile" name="zip_file" data-parsley-required="true" />
                      </div>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="new" value="file">
                <div class="col-12 d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary me-1 mb-1 shadow">
                    <?= htmlspecialchars((string) ($common_submit ?? 'Submit'), ENT_QUOTES, 'UTF-8') ?>
                  </button>
                  <button type="reset" class="btn btn-light-secondary me-1 mb-1 shadow">
                    <?= htmlspecialchars((string) ($common_reset ?? 'Reset'), ENT_QUOTES, 'UTF-8') ?>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

      <hr>

      <div class="card-body">
        <h4 class="card-title"><?= htmlspecialchars((string) ($plugin_all_title ?? 'Plugins'), ENT_QUOTES, 'UTF-8') ?></h4>
        <table class="table" id="table">
          <thead>
            <tr>
              <th><?= htmlspecialchars((string) ($plugin_all_name ?? 'Plugin name'), ENT_QUOTES, 'UTF-8') ?></th>
              <th><?= htmlspecialchars((string) ($plugin_all_description ?? 'Description'), ENT_QUOTES, 'UTF-8') ?></th>
              <th><?= htmlspecialchars((string) ($common_actions ?? 'Actions'), ENT_QUOTES, 'UTF-8') ?></th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($allplugins instanceof PDOStatement) {
                while ($row = $allplugins->fetch(PDO::FETCH_ASSOC)) {
                    $pId = (int) ($row['id'] ?? 0);
                    $isActive = (int) ($row['active'] ?? 0) === 1;
                    $isInstalled = (int) ($row['installed'] ?? 0) === 1;

                    $background = $isActive ? '#c7fac1' : 'none';
                    $btnOp = $isActive ? 'dis' : 'add';
                    $btnClass = $isActive ? 'btn-warning' : 'btn-success';
                    $btnIcon = $isActive ? 'bi-dash-circle' : 'bi-plus-circle';

                    $pluginLabel = ucfirst(str_replace('_', ' ', (string) ($row['pluginname'] ?? '')));
                    $pluginDesc = (string) ($row['description'] ?? '');
                    ?>
                    <tr style="background:<?= $background ?>">
                      <td><?= htmlspecialchars($pluginLabel, ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars($pluginDesc, ENT_QUOTES, 'UTF-8') ?></td>
                      <td>
                        <a href="core/mngPlugins.php?idPlugin=<?= $pId ?>&op=<?= $btnOp ?>" class="btn icon <?= $btnClass ?> shadow">
                          <i class="bi <?= $btnIcon ?>"></i>
                        </a>
                        &nbsp; &nbsp;
                        <?php if ($isInstalled): ?>
                          <a href="#" class="btn icon btn-danger shadow" data-bs-toggle="modal" data-bs-target="#danger<?= $pId ?>">
                            <i class="bi bi-trash"></i>
                          </a>
                        <?php endif; ?>

                        <!-- Danger theme Modal -->
                        <div class="modal fade text-left" id="danger<?= $pId ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?= $pId ?>" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                              <div class="modal-header bg-danger">
                                <h5 class="modal-title white" id="myModalLabel<?= $pId ?>">
                                  <?= htmlspecialchars((string) ($common_modal_title_sure ?? 'Are you sure?'), ENT_QUOTES, 'UTF-8') ?>
                                </h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                  <i data-feather="x"></i>
                                </button>
                              </div>
                              <div class="modal-body">
                                <?= htmlspecialchars((string) ($plugin_all_modal_body ?? 'Do you want to delete this plugin?'), ENT_QUOTES, 'UTF-8') ?>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                  <i class="bx bx-x d-block d-sm-none"></i>
                                  <span class="d-none d-sm-block"><?= htmlspecialchars((string) ($common_modal_cancel ?? 'Cancel'), ENT_QUOTES, 'UTF-8') ?></span>
                                </button>
                                <a href="core/mngPlugins.php?idPlugin=<?= $pId ?>&op=rm" class="btn btn-danger ml-1 shadow">
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
  </div>
</section>