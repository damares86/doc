<?php
require "inc/funcHeader.php";

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
                  <label><?= $plugin_all_add ?> <span class="text-danger">*</span></label>
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
                    <?= $common_submit ?>
                  </button>
                  <button type="reset" class="btn btn-light-secondary me-1 mb-1 shadow">
                    <?= $common_reset ?>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

      <hr>

      <div class="card-body">
        <h4 class="card-title"><?= $plugin_all_title ?></h4>
        <table class="table" id="table">
          <thead>
            <tr>
              <th><?= $plugin_all_name ?></th>
              <th><?= $plugin_all_description ?></th>
              <th><?= $common_actions ?></th>
            </tr>
          </thead>
          <tbody>

            <?php
            while ($row = $allplugins->fetch(PDO::FETCH_ASSOC)) {
              extract($row);

              $background = "#c7fac1";
              $button = "<a href=\"core/mngPlugins.php?idPlugin=" . $row['id'] . "&op=dis\" class=\"btn icon btn-warning shadow\"><i class=\"bi bi-dash-circle\"></i></a>";

              if ($row['active'] == 0) {
                $background = "none";
                $button = "<a href=\"core/mngPlugins.php?idPlugin=" . $row['id'] . "&op=add\" class=\"btn icon btn-success shadow\"><i class=\"bi bi-plus-circle\"></i></a>";
              }
            ?>
              <tr style="background:<?= $background ?>">
                <td>
                  <?php
                  $pluginLabel = str_replace("_", " ", $row['pluginname']);
                  $pluginLabel = ucfirst($pluginLabel);
                  echo $pluginLabel;
                  ?>
                </td>
                <td>
                  <?php
                  echo $row['description'];
                  ?>
                </td>

                <td>
                  <?php echo $button ?>
                  &nbsp; &nbsp;
                  <?php
                  if ($row['installed'] == 1) {
                  ?>
                    <a href="#" class="btn icon btn-danger shadow" data-bs-toggle="modal" data-bs-target="#danger<?= $row['id'] ?>"><i class="bi bi-trash"></i></a>
                  <?php
                  }
                  ?>
                </td>

                <!--Danger theme Modal -->
                <div class="modal fade text-left" id="danger<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel120" aria-hidden="true">
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
                        <?= $plugin_all_modal_body ?>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                          <i class="bx bx-x d-block d-sm-none"></i>
                          <span class="d-none d-sm-block"><?= $common_modal_cancel ?></span>
                        </button>
                        <span class="d-none d-sm-block"><a href="core/mngPlugins.php?idPlugin=<?= $row['id'] ?>&op=rm" class="btn btn-danger ml-1 shadow"><?= $common_modal_confirm ?></a></span>
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