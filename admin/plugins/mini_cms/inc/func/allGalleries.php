<?php
$mc->table = 'mc_galleries';
$gall = $mc->showAll('id');
?>

<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3><?=$allgall_header?></h3>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.php"><?= $common_dashboard ?></a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <?=$allgall_header?>
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
    <div class="card-header"><?=$allgall_title?> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
      <a href="index.php?p=addGallery" class="btn icon icon-left btn-success shadow">
        <i data-feather="plus-circle"></i> <?=$allgall_add?>
      </a>
    </div>
    <div class="card-body">

      <div class="row">
        <?php
        if($gall->rowCount()>0){
        while ($row = $gall->fetch(PDO::FETCH_ASSOC)) {
          $images = glob("../uploads/gallery/g_" . $row['id'] . "/*");
          if (count($images) > 0) {

        ?>
            <div class="col-6 col-lg-4 col-md-6">
              <div class="card border">
                <div class="card-body px-4 py-4-5">

                  <div class="row">

                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                      <h6 class="font-extrabold mb-2"><?= $row['gallery_name'] ?></h6>
                      <?php

                      ?>
                      <img src="<?= $images[0] ?>" class="w-100">
                    </div>
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5">
                      <a href="index.php?p=editGallery&idToMod=<?= $row['id'] ?>&pagename=allGalleries">
                        <div class="stats-icon bg-warning mb-2">
                          <i class="bi-pencil-square"></i>
                        </div>
                      </a>
                      <div class="stats-icon bg-danger mb-2">
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
                                <?=$allgall_modal_body?>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                  <i class="bx bx-x d-block d-sm-none"></i>
                                  <span class="d-none d-sm-block"><?= $common_modal_cancel ?></span>
                                </button>
                                <span class="d-none d-sm-block"><a href="core/mngGallery.php?idToDel=<?= $row['id'] ?>" class="btn btn-danger ml-1">
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
            </div>
        <?php
          } else {
            echo $edigall_noimg;
          }
        }
      }else{
          echo $allgall_nogall ;

        }



        ?>
      </div>

    </div>
  </div>
</section>