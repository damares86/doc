<script type="text/javascript" src="script/coloris.min.js"></script>

<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3><?= $allcolors_header ?></h3>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.php"><?= $common_dashboard ?></a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <?= $allcolors_header ?>
          </li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<br>

<section class="section">
  <!-- <div class="card-header">Scegli il tema &nbsp; &nbsp; &nbsp;
    </div> -->

  <div id="colorpicker"></div>
  <div class="row">
    <div class="col-md-8 col-12">
      <div class="card shadow">
        <div class="card-body">
          <form class="form form-horizontal mb-3" action="core/mngColors.php" method="POST" data-parsley-validate>
            <div class="form-body">
              <div class="row">

                <div class="col-md-3">
                  <label><?= $allcolors_addcolor ?></label>
                </div>
                <div class="col-md-9">
                  <div class="form-group has-icon-left">
                    <div class="form-check mandatory square">
                      <input type="text" class="form-control coloris instance1" id="color" name="color" value="#008db1" data-parsley-required="true" data-coloris>
                    </div>
                  </div>
                </div>

                <input type="hidden" name="origin" value="allColor">
                <input type="hidden" name="operation" value="addColor">

                <div class="col-12 mt-3 d-flex justify-content-end">
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
    <div class="col-md-4 col-12">
      <div class="card shadow">
        <h4 class="card-title px-4 pt-3"><?= $common_info ?></h4>
        <div class="card-content px-5 pb-4">
          <ul>
            <li><a href="https://www.dmweblab.com/portal/manual.php?prod=2&parent=1&page=13" target="_blank"><?= $common_see_guide ?></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="card shadow">
    <!-- <div class="card-header">Scegli il tema &nbsp; &nbsp; &nbsp;
    </div> -->
    <div class="card-body">

      <div class="col-12 py-3">
        <label><?= $allcolors_colors ?></label>
        <div class="row mt-3">
          <?php
          $mc->table = 'mc_color';
          $stmt1 = $mc->showAll('id');

          while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
          ?>
            <div class="col-6 col-lg-3 col-md-6">
              <div class="card shadow" style="background-color: <?= $row1['color'] ?>;">
                <div class="card-body px-4 py-4-5">
                  <div class="row">
                    <div class="col-md-3 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-center ">
                      <a href="#" data-bs-toggle="modal" data-bs-target="#danger<?= $row1['id'] ?>">
                        <div class="stats-icon red mb-2 border shadow">
                          <i class="bi-trash"></i>
                        </div>
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
                              <?= $allcolors_modal_body ?>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block"><?= $common_modal_cancel ?></span>
                              </button>
                              <span class="d-none d-sm-block"><a href="core/mngColors.php?idToDel=<?= $row1['id'] ?>" class="btn btn-danger ml-1">
                                  <?= $common_modal_confirm ?>
                                </a></span>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php

          }


          ?>
        </div>
      </div>

    </div>
  </div>
</section>