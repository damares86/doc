<?php
$mc->table = 'mc_settings';
$stmt = $mc->showAll('id');

$mc_settings = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

  extract($row);
  $mc_settings[$row['name']] = $row['value'];
}

?>

<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3><?= $mcsettings_header ?></h3>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.php"><?= $common_dashboard ?></a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <?= $mcsettings_header ?>
          </li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<br>

<section class="section">
  <div class="row">
    <div class="col-md-8 col-12">
      <div class="card shadow">
        <div class="card-header">
          <h4 class="card-title"><?= $mcsettings_site ?></h4>
        </div>
        <div class="card-content">
          <div class="card-body">
            <form class="form form-horizontal" action="core/mngMcSettings.php" method="POST" enctype="multipart/form-data" data-parsley-validate>
              <div class="form-body">
                <div class="row">

                  <div class="col-md-3">
                    <label>Logo <span class="text-danger">*</span></label>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <div class="form-check mandatory">
                        <div class="position-relative">

                          <div class="form-check">
                            <span>Attuale : <img src="../uploads/img/<?= $mc_settings['mc_site_logo'] ?>" class="d-inline w-25"></span>
                            <br>
                            <br>
                            <input type="hidden" name="current_logo" value="<?= $mc_settings['mc_site_logo'] ?>">
                            <span><?= $addpage_header_img_upload ?></span>

                            <div class="form-group">
                              <div class="form-check mandatory">
                                <div class="position-relative">
                                  <input class="form-control" type="file" name="img_logo" />
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <label><?= $mcsettings_site_name ?> <span class="text-danger">*</span></label>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <div class="form-check mandatory">
                        <div class="position-relative">
                          <input type="text" class="form-control" placeholder="<?= $mcsettings_site_name ?>" name="mc_site_name" value="<?= $mc_settings['mc_site_name'] ?>" data-parsley-required="true" />
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <label><?= $mcsettings_site_description ?> <span class="text-danger">*</span></label>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <div class="form-check mandatory">
                        <div class="position-relative">
                          <input type="text" class="form-control" placeholder="<?= $mcsettings_site_description ?>" name="mc_site_description" value="<?= $mc_settings['mc_site_description'] ?>" data-parsley-required="true" />
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <label><?= $mcsettings_footer ?> <span class="text-danger">*</span></label>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <div class="form-check mandatory">
                        <div class="position-relative">
                          <textarea class="tiny mt-5" name="mc_footer"><?= $mc_settings['mc_footer'] ?></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" name="operation" value="settings">
                  <input type="hidden" name="origin" value="allMcSettings">

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
      </div>


      <div class="card shadow">
        <div class="card-header">
          <h4 class="card-title"><?= $mcsettings_contact ?></h4>
        </div>
        <div class="card-content">
          <div class="card-body">
            <form class="form form-horizontal" action="core/mngMcSettings.php" method="POST" data-parsley-validate>
              <div class="form-body">
                <div class="row">
                  <div class="col-12">
                    <?php
                    $mc->table = 'mc_contacts';
                    $stmt1 = $mc->showAll('id');
                    ?>
                    <table class="table" id="table">
                      <thead>
                        <tr>
                          <th><?= $mcsettings_label ?></th>
                          <th><?= $common_email ?></th>
                          <th><?= $common_actions ?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                          extract($row1);
                        ?>
                          <tr>
                            <td><?= $row1['label']; ?></td>
                            <td><?= $row1['email']; ?></td>
                            <td>
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
                                      <?= $mcsettings_modal_body ?>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                        <i class="bx bx-x d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block"><?= $common_modal_cancel ?></span>
                                      </button>
                                      <span class="d-none d-sm-block"><a href="core/mngMcSettings.php?idToDel=<?= $row1['id'] ?>" class="btn btn-danger ml-1">
                                          <?= $common_modal_confirm ?>
                                        </a></span>
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
                    <div class="row mt-5">
                      <div class="col-12">
                        <h6><?= $mcsettings_contact_add ?></h6>
                      </div>

                      <div class="col-md-3">
                        <label><?= $mcsettings_label ?> <span class="text-danger">*</span></label>
                      </div>
                      <div class="col-md-9">
                        <div class="form-group">
                          <div class="form-check mandatory">
                            <div class="position-relative">
                              <input type="text" class="form-control" placeholder="Label" name="label" data-parsley-required="true" />
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-3">
                        <label><?= $common_email ?> <span class="text-danger">*</span></label>
                      </div>
                      <div class="col-md-9">
                        <div class="form-group">
                          <div class="form-check mandatory">
                            <div class="position-relative">
                              <input type="text" class="form-control" placeholder="Email" name="email" data-parsley-required="true" />
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>

                  <input type="hidden" name="operation" value="contact">
                  <input type="hidden" name="origin" value="allMcSettings">

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
      </div>

      <div class="card shadow">
        <div class="card-header">
          <h4 class="card-title"><?= $mcsettings_maintenance ?></h4>
        </div>
        <div class="card-content">
          <div class="card-body">
            <form class="form form-horizontal" action="core/mngMcSettings.php" method="POST" data-parsley-validate>
              <div class="form-body">
                <div class="row">


                  <div class="col-md-5">
                    <label><?= $mcsettings_maintenance ?> <span class="text-danger">*</span></label>
                  </div>
                  <div class="col-md-7">
                    <div class="form-group">
                      <div class="form-check">
                        <div class="checkbox">
                          <?php

                          $mc_settings['maintenance'] == 1 ? $maintenance_checked = 'checked' : $maintenance_checked = '';
                          ?>
                          <input type="checkbox" id="checkbox1" class="form-check-input" name="maintentance" <?= $maintenance_checked ?>>
                          <label for="checkbox1">&nbsp; <?= $mcsettings_maintenance_activate ?></label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <input type="hidden" name="operation" value="maintenance">
                  <input type="hidden" name="origin" value="allMcSettings">

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
      </div>
    </div>

    <div class="col-md-4 col-12">
      <div class="card shadow">
        <h4 class="card-title px-4 pt-3"><?= $common_info ?></h4>
        <div class="card-content px-5 pb-4">
          <ul>
            <li><a href="https://www.dmweblab.com/portal/manual.php?prod=2&parent=1&page=17" target="_blank"><?= $common_see_guide ?></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>