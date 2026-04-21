<?php

require "inc/funcHeader.php";

?>

<section class="section">
    <div class="row">
        <div class="col-md-8 col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="card-title"><?= $settings_all_title ?></h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="core/mngSettings.php" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label><?= $settings_all_lang ?> </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="position-relative">
                                                <fieldset class="form-group">
                                                    <select class="form-select" id="lang" name="lang">
                                                        <?php

                                                        $scan = scandir('locale/');
                                                        $exclude = array('..', '.', '.gitkeep','fm_translation.json');
                                                        $selected = "";
                                                        foreach ($scan as $folder) {
                                                            if (!in_array($folder, $exclude)) {

                                                                if ($folder == $lang) {
                                                                    $selected = "selected";
                                                                }

                                                        ?>

                                                                <option value="<?= $folder ?>" <?= $selected ?>><?= $folder ?></option>

                                                        <?php
                                                                $selected = "";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $setting->name = "noreply";
                                    $stmt = $setting->showAllWhere('id', ['name']);
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $reset = $row['value'];
                                    ?>

                                    <div class="col-md-3 border-top mt-3 pt-3">
                                        <label><?= $settings_all_noreply ?> <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9 border-top mt-3 pt-3">
                                        <div class="form-group has-icon-left">
                                            <div class="form-check mandatory">
                                                <div class="position-relative">
                                                    <input type="email" class="form-control" placeholder="Email" id="first-name-icon" name="noreply" data-parsley-required="true" value="<?= $reset ?>" />
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-envelope"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-3 border-top mt-3 pt-3">
                                        <label><?= $settings_layout_title ?> </label>
                                    </div>
                                    <div class="col-md-9 border-top mt-3 pt-3">
                                        <div class="form-group">
                                            <div class="position-relative">
                                                <fieldset class="form-group">
                                                    <select class="form-select" id="layout" name="layout">
                                                        <?php
                                                        $selHoriz = '';
                                                        $selVert = '';
                                                        $setting->name = "layout";
                                                        $stmt = $setting->showAllWhere('id', ['name']);
                                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                                        $layout = $row['value'];

                                                        if ($layout == 'h') {
                                                            $selHoriz = "selected";
                                                        } else if ($layout == 'v') {
                                                            $selVert = "selected";
                                                        }
                                                        ?>

                                                        <option value="h" <?= $selHoriz ?>><?= $settings_layout_horizontal ?></option>
                                                        <option value="v" <?= $selVert ?>><?= $settings_layout_vertical ?></option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>

                                    <br>
                                    <br>
                                    <br>
                                    <hr>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1 shadow">
                                            <?= $common_submit ?>
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
                        <li><a href="http://dmweblab.com/portal/manual.php?prod=1&page=11" target="_blank"><?= $common_see_guide ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>