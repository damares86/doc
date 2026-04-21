<?php
$mc->table = 'mc_quotes';
$quotes = $mc->showAll('id');
?>

<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3><?= $allquotes_header ?></h3>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.php"><?= $common_dashboard ?></a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <?= $allquotes_header ?>
          </li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<br>


<!-- Basic Tables start -->
<section class="section">
  <div class="row">
    <div class="col-md-8 col-12">
      <div class="card shadow">
        <div class="card-body">

          <form class="form form-horizontal" action="core/mngQuote.php" method="POST" enctype="multipart/form-data" data-parsley-validate>
            <div class="form-body">
              <h5><?= $allquotes_add ?></h5>
              <div class="row border-bottom mb-5 py-3">

                <div class="col-md-3">
                  <label><?= $allquotes_author ?> <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                  <div class="form-group">
                    <div class="form-check mandatory">
                      <div class="position-relative">
                        <input type="text" class="form-control" placeholder="Autore" name="author" data-parsley-required="true" />
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <label><?= $allquotes_quote ?> <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                  <div class="form-group">
                    <div class="form-check mandatory">
                      <div class="position-relative">
                        <input type="text" class="form-control" placeholder="Citazione" name="quote" data-parsley-required="true" />
                      </div>
                    </div>
                  </div>
                </div>


                <input type="hidden" name="origin" value="allQuotes">
                <input type="hidden" name="operation" value="add">

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
            <li><a href="https://www.dmweblab.com/portal/manual.php?prod=2&parent=1&page=14" target="_blank"><?= $common_see_guide ?></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card shadow">
        <div class="card-body">
          <h5><?= $allquotes_title ?></h5>

          <table class="table" id="table">
            <thead>
              <tr>
                <th><?= $allquotes_quote ?></th>
                <th><?= $allquotes_author ?></th>
                <th><?= $common_actions ?></th>
              </tr>
            </thead>
            <tbody>

              <?php
              while ($row = $quotes->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
              ?>
                <tr>
                  <td><?= $row['quote'] ?></td>
                  <td><?= $row['author'] ?></td>
                  <td>
                    <a href="index.php?p=editQuote&idToMod=<?= $row['id'] ?>" class="btn icon btn-warning shadow edit-link" data-base-url="index.php?p=editQuote&idToMod=<?= $row['id'] ?>">
                      <i class="bi bi-pencil-square"></i>
                    </a>

                    &nbsp; &nbsp;
                    <a href="#" class="btn icon btn-danger shadow" data-bs-toggle="modal" data-bs-target="#danger<?= $row['id'] ?>"><i class="bi bi-trash"></i>
                    </a>
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
                            <?= $allquotes_modal_body ?>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                              <i class="bx bx-x d-block d-sm-none"></i>
                              <span class="d-none d-sm-block"><?= $common_modal_cancel ?></span>
                            </button>
                            <span class="d-none d-sm-block"><a href="core/mngQuote.php?idToDel=<?= $row['id'] ?>" class="btn btn-danger ml-1">
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
        </div>
      </div>
    </div>

  </div>
</section>