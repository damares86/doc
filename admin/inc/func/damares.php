<?php

require "inc/funcHeader.php";

?>

<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="card-title"><?= $damares_title ?></h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="core/mngDamares.php" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>PHP Debug</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="position-relative">
                                                <div class="form-check">
                                                    <div class="checkbox">
                                                        <?php
                                                        $setting->name = "debug";
                                                        $stmt = $setting->showAllWhere('id', ['name']);
                                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                                        extract($row);
                                                        $checked = "";
                                                        if ($row['value'] == 1) {
                                                            $checked = "checked";
                                                        }

                                                        ?>
                                                        <input type="hidden" name="debug_check" value="yes">
                                                        <input type="checkbox" id="checkbox1" name="debug" class="form-check-input" <?= $checked ?>>
                                                        <label for="checkbox1">&nbsp; &nbsp;<?= $damares_enable ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br><br><br>
                                    <div class="col-12 d-flex justify-content-start">
                                        <button type="submit" class="btn btn-primary me-1 mb-1 shadow">
                                            <?= $common_submit ?>
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                        <hr>
                        <br>
                        <div class="row">
                            <div class="col-2">
                                <h6><?=$damares_clear_title?></h6>
                            </div>
                            <div class="col-10 text-left">
                                <a href="#" class="btn btn-danger shadow" data-bs-toggle="modal" data-bs-target="#clear"><?=$damares_clear_button?></a>
                                </a>
                                <!--Danger theme Modal -->
                                <div class="modal fade text-left" id="clear" tabindex="-1" role="dialog" aria-labelledby="myModalLabel120" aria-hidden="true">
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
                                                <?= $damares_modal_body ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block"><?= $common_modal_cancel ?></span>
                                                </button>
                                                <span class="d-none d-sm-block"><a href="core/clean.php" class="btn btn-danger ml-1"><?= $common_modal_confirm ?></a></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <hr>

                    </div>
                </div>
            </div>
        </div>

    </div>
</section>