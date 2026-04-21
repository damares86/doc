<?php
// $summernote = true;
?>

<style>
    .row.page {
        display: none
    }
</style>
<div id="fm_button">
    <button type="button" class="btn btn-primary me-1 mb-1 shadow" data-bs-toggle="modal" data-bs-target="#fm_modal">
        <?= $addpage_fm_open?>
    </button>
</div>

<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3><?= $addpage_header ?></h3>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php"><?= $common_dashboard ?></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $addpage_header ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<br>



<section class="section">
    <div class="row">
        <div class="col-md-10 col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="card-title"><?= $addpage_title ?></h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="core/mngPage.php" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label><?= $addpage_name ?> <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="form-check mandatory">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="<?= $addpage_name ?>" name="page_name" data-parsley-required="true" />

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- link page to a file -->
                                    <div class="col-md-3 mt-3 p-3 border-top">
                                        <label><?=$addpage_link_file?></label>
                                    </div>
                                    <div class="col-md-9 mt-3 pt-3 border-top">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <div class="checkbox">
                                                    <input type="checkbox" id="checkbox_usefile" class="form-check-input" name="link_to_file_check">
                                                    <!-- <label for="checkbox_usefile">&nbsp; Seleziona per </label> -->
                                                    &nbsp; &nbsp; &nbsp;
                                                    <button type="button" class="btn btn-primary me-1 mb-1 shadow" data-bs-toggle="modal" data-bs-target="#fm_modal">
                                                        <?= $addpage_fm_open?>
                                                    </button>
                                                    <div class="col-12">


                                                        <style>
                                                            .modal-dialog {
                                                                width: 79%;
                                                                max-width: 80%;
                                                                height: 70%;
                                                            }
                                                        </style>
                                                        <div class="modal fade" id="fm_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel120" aria-hidden="true">
                                                            <div class="modal-dialog" role="document" style="height: 100%;">
                                                                <div class="modal-content h-75">
                                                                    <div class="modal-body">
                                                                        <iframe src='core/tinyfilemanager.php' style="width: 100%; height:100%;">
                                                                        </iframe>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 border-bottom">
                                        <div class="form-group">
                                            <div class="form-check mandatory">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="Copia e incolla url del file dal File Manager" id="file_page_link" name="link_to_file" />

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="mngpage_content" class="col-12">
                                        <div class="row">

                                            <div class="col-md-3 mt-3">
                                                <label><?= $addpage_layout ?> <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-9 mt-3">
                                                <div class="form-group">
                                                    <div class="form-check mandatory">
                                                        <?php
                                                        $layout_counter = 0;
                                                        foreach (glob("../assets/template/img/*") as $file) {
                                                            if (is_file($file)) {
                                                                $style = pathinfo($file, PATHINFO_FILENAME);

                                                                $checked = '';
                                                                if ($layout_counter == 0) {
                                                                    $checked = 'checked';
                                                                }
                                                        ?>

                                                                <input type="radio" class="btn-check" name="layout" value="<?= $style ?>" autocomplete="off" id="layout_<?= $style ?>" <?= $checked ?>>
                                                                <label class="btn btn-outline-primary" for="layout_<?= $style ?>"><img src='../assets/template/img/<?= $style ?>.png'></label>
                                                                &nbsp;
                                                        <?php
                                                            }
                                                            $layout_counter++;
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 mt-3 pt-3">
                                                <label><?= $addpage_use_header ?> <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-9 mt-3 pt-3">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <div class="checkbox">
                                                            <input type="checkbox" id="checkbox1" class="form-check-input" name="use_header">
                                                            <label for="checkbox1">&nbsp; <?= $addpage_use_header_select ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row highlight-section">
                                                <div class="col-md-3 mt-3 p-3 border-top">
                                                    <label><?= $addpage_header_style ?> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-9 mt-3 border-top">
                                                    <div class="row mt-3">
                                                        <div class="col border p-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input nomargin" type="radio" name="header" value="image" checked>
                                                                <label class="form-check-label">&nbsp; <?= $addpage_header_img_title ?></label>
                                                                <br>
                                                                <br>
                                                                <span><?= $addpage_header_img_default ?>: <img src="../uploads/img/visual.jpg" class="d-inline w-25"></span>
                                                                <br>
                                                                <br>

                                                                <label><?= $addpage_header_img_upload ?> <span class="text-danger">*</span></label>

                                                                <div class="form-group">
                                                                    <div class="form-check mandatory">
                                                                        <div class="position-relative">
                                                                            <input class="form-control" type="file" name="img_header" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col border p-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input nomargin" type="radio" name="header" value="gallery">
                                                                <label class="form-check-label">&nbsp; <?= $addpage_header_gallery_title ?></label>
                                                                <br><br>
                                                                <label><?= $addpage_header_gallery_choose ?> <span class="text-danger">*</span></label>

                                                                <div class="form-group">
                                                                    <div class="form-check mandatory">
                                                                        <div class="position-relative">
                                                                            <fieldset class="form-group">
                                                                                <select class="form-select" name="header_gallery">
                                                                                    <?php
                                                                                    $mc->table = 'mc_galleries';
                                                                                    $galleries = $mc->showAll('id');
                                                                                    $galleryOptions = '';
                                                                                    while ($row = $galleries->fetch(PDO::FETCH_ASSOC)) {
                                                                                        $galleryOptions .= '<option value="' . $row['id'] . '">' . $row['gallery_name'] . '</option>';
                                                                                    ?>

                                                                                        <option value="<?= $row['id'] ?>"><?= $row['gallery_name'] ?></option>

                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-3 mt-3 mb-3">
                                                    <label>Use page name </label>
                                                </div>

                                                <div class="col-md-9 mt-3 mb-3">
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <div class="checkbox">
                                                                <input type="checkbox" class="form-check-input" name="use_page_name">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 mt-3 mb-3">
                                                    <label><?= $addpage_header_site_name ?> </label>
                                                </div>
                                                <?php
                                                $mc->table = 'mc_settings';
                                                $mc->name = 'mc_site_name';
                                                $sitename = $mc->showAllWhere('id', ['name']);
                                                $name = $sitename->fetch(PDO::FETCH_ASSOC);

                                                ?>
                                                <div class="col-md-9 mt-3 mb-3">
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <div class="checkbox">
                                                                <input type="checkbox" class="form-check-input" name="site_name">
                                                                <label>&nbsp; <b><?= $name['value'] ?></b></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 mt-3 mb-3 pb-3 border-bottom">
                                                    <label><?= $addpage_header_site_description ?> </label>
                                                </div>
                                                <?php
                                                $mc->table = 'mc_settings';
                                                $mc->name = 'mc_site_description';
                                                $sitename = $mc->showAllWhere('id', ['name']);
                                                $name = $sitename->fetch(PDO::FETCH_ASSOC);

                                                ?>
                                                <div class="col-md-9 mt-3 mb-3 pb-3 border-bottom">
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <div class="checkbox">
                                                                <input type="checkbox" class="form-check-input" name="site_description">
                                                                <label>&nbsp; <b><?= $name['value'] ?></b></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row" id="dynamic_field">
                                                <div class="row" id="block_1">
                                                    <div class="col-md-3 mt-3 p-3">
                                                        <label><b><?= $block_title ?> <span>1</span></b></label>
                                                    </div>
                                                    <div class="col-md-5 mt-3  p-3">
                                                        <div class="form-group">
                                                            <div class="form-check mandatory">
                                                                <div class="position-relative">
                                                                    <fieldset class="form-group">
                                                                        <select class="form-select" id="block_1_type" name="block_1_type">
                                                                            <option value="text_1"><?= $block_type_text ?></option>
                                                                            <option value="img_1"><?= $block_type_image ?></option>
                                                                            <option value="info_1"><?= $block_type_info ?></option>
                                                                            <option value="gallery_1"><?= $block_type_gallery ?></option>
                                                                            <option value="quote_1"><?= $block_type_quotes ?></option>
                                                                            <option value="script_1"><?= $block_type_scripts ?></option>
                                                                            <?php
                                                                            $plugin->pluginname = "post";
                                                                            // $postOption = '';
                                                                            $postExist = false;
                                                                            if ($plugin->itemExists('pluginname') && $plugin->isActive() == 1) {
                                                                                $postExist = true;
                                                                                // $postOption = '<option value="post_1">' . $block_type_post . '</option>';
                                                                            ?>
                                                                                <option value="post_1"><?= $block_type_post ?></option>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </fieldset>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mt-3 p-3">
                                                        &nbsp;
                                                    </div>

                                                    <div class="col-md-3 p-3">
                                                        <label><?= $block_bootstrap ?></label>
                                                    </div>
                                                    <div class="col-md-9 mt-3  px-5">
                                                        <input type="text" class="form-control" placeholder="" name="bootstrap_1" />
                                                    </div>

                                                    <div class="col-12 mt-3 mb-3 px-5 pb-3 border-bottom">

                                                        <div class="row page text_1">
                                                            <textarea class="tiny" name="text_content_1"></textarea>
                                                            <!-- <textarea class="summernote" name="text_1"></textarea> -->
                                                        </div>
                                                        <div class="row page img_1">
                                                            <label><?= $block_image_upload ?> <span class="text-danger">*</span></label>
                                                            <div class="form-group">
                                                                <div class="form-check mandatory">
                                                                    <div class="position-relative">
                                                                        <input class="form-control" type="file" name="img_1" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row page info_1">
                                                            <label><?= $block_image_upload ?> <span class="text-danger">*</span></label>
                                                            <div class="form-group">
                                                                <div class="form-check mandatory">
                                                                    <div class="position-relative">
                                                                        <input class="form-control" type="file" name="info_img_1" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <textarea class="summernote" class="mt-5" name="info_content_1"></textarea> -->
                                                            <textarea class="tiny mt-5" name="info_content_1"></textarea>
                                                        </div>
                                                        <div class="row page gallery_1">
                                                            <div class="col-7">
                                                                <label class="mb-3"><?= $block_gallery_choose ?> <span class="text-danger">*</span></label>
                                                                <div class="form-group">
                                                                    <div class="form-check mandatory">
                                                                        <div class="position-relative">
                                                                            <fieldset class="form-group">
                                                                                <select class="form-select" name="gallery_1">
                                                                                    <?php
                                                                                    $mc->table = 'mc_galleries';
                                                                                    $galleries = $mc->showAll('id');
                                                                                    while ($row = $galleries->fetch(PDO::FETCH_ASSOC)) {
                                                                                    ?>
                                                                                        <option value="<?= $row['id'] ?>"><?= $row['gallery_name'] ?></option>

                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-5">&nbsp;</div>
                                                        </div>
                                                        <div class="row page quote_1">
                                                            <p><?= $block_quotes_text ?></p>
                                                            <input type="hidden" name="quote_1" value="q">
                                                        </div>
                                                        <div class="row page script_1">
                                                            <div class="col-12">
                                                                <b><?= $block_script_title ?></b><br>
                                                                <?= $block_script_warn ?> <br><?= $block_script_desc ?>
                                                            </div>
                                                            <div class="col-md-3 pb-3 mt-3">
                                                                <label><?= $block_script_file ?></label>
                                                            </div>
                                                            <div class="col-md-9 pb-3">
                                                                <div class="form-group">
                                                                    <div class="position-relative">
                                                                        <input type="text" class="form-control" placeholder="" name="script_1" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        if ($postExist == true) {
                                                        ?>
                                                            <div class="row page post_1">
                                                                <div class="col-12">
                                                                    <p><?= $block_post_text ?></p>
                                                                </div>
                                                                <div class="col-md-3 pb-3">
                                                                    <label><?= $block_post_cat ?></label>
                                                                </div>
                                                                <div class="col-md-9 pb-3">
                                                                    <div class="form-group has-icon-left">
                                                                        <div class="position-relative">
                                                                            <fieldset class="form-group">
                                                                                <select class="form-select w-50" name="post_cat_1">
                                                                                    <option value='none'><?= $block_post_cat_all ?></option>
                                                                                    <?php
                                                                                    $catOption = '<option value="none">' . $block_post_cat_all . '</option>';
                                                                                    $post->table = "post_categories";
                                                                                    $cat_stmt = $post->showAll('id');
                                                                                    while ($cat_row = $cat_stmt->fetch(PDO::FETCH_ASSOC)) {
                                                                                        $catOption .= '<option value="' . $cat_row['id'] . '">' . $cat_row['category_name'] . '</option>';

                                                                                    ?>
                                                                                        <option value='<?= $cat_row['id'] ?>'><?= $cat_row['category_name'] ?></option>
                                                                                    <?php
                                                                                    }

                                                                                    ?>
                                                                                </select>
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="post_1" value="p">
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>

                                                    </div>

                                                    <div class="row colors mb-5">

                                                        <!-- Sezione Background color -->
                                                        <div class="col-md-3 mt-3 px-3">
                                                            <label><?= $block_bg_color ?></label>
                                                        </div>
                                                        <div class="col-md-9 mt-3 px-3">
                                                            <div class="form-group">
                                                                <div class="form-check mandatory">
                                                                    <div class="position-relative">
                                                                        <div class="form-group">
                                                                            <!-- Opzione 'none' per il Background color -->
                                                                            <input type="radio" class="btn-check" name="bg_color_1" value="none" autocomplete="off" id="bg_none_1" hidden checked>
                                                                            <label class="color-label bg shadow my-1" for="bg_none_1" style="background-color: #e5e5e5;">
                                                                                <?= $block_color_none ?>
                                                                                <span class="checkmark"></span>
                                                                            </label>

                                                                            <!-- Loop per i colori del Background -->
                                                                            <?php
                                                                            $mc->table = 'mc_color';
                                                                            $colors = $mc->showAll('id');
                                                                            $colorArray = [];

                                                                            while ($row = $colors->fetch(PDO::FETCH_ASSOC)) {
                                                                                $colorArray[] = ['color' => $row['color']];
                                                                            ?>
                                                                                <input type="radio" class="btn-check" name="bg_color_1" value="<?= $row['color'] ?>" autocomplete="off" id="bg_<?= $row['color'] ?>" hidden>
                                                                                <label class="color-label  shadow my-1" for="bg_<?= $row['color'] ?>" style="background-color: <?= $row['color'] ?>;">
                                                                                    <span class="checkmark">✔</span>
                                                                                    &nbsp;
                                                                                </label>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Sezione Text color -->
                                                        <div class="col-md-3 mt-3 px-3">
                                                            <label><?= $block_bg_text ?></label>
                                                        </div>
                                                        <div class="col-md-9 mt-3 px-3">
                                                            <div class="form-group">
                                                                <div class="form-check mandatory">
                                                                    <div class="position-relative">
                                                                        <div class="form-group">
                                                                            <!-- Opzione 'none' per il Text color -->
                                                                            <input type="radio" class="btn-check" name="text_color_1" value="none" autocomplete="off" id="text_none_1" hidden checked>
                                                                            <label class="color-label text shadow my-1" for="text_none_1" style="background-color: #e5e5e5;">
                                                                                <?= $block_color_none ?>
                                                                                <span class="checkmark"></span>
                                                                            </label>

                                                                            <!-- Loop per i colori del Text -->
                                                                            <?php
                                                                            $mc->table = 'mc_color';
                                                                            $colors = $mc->showAll('id');

                                                                            while ($row = $colors->fetch(PDO::FETCH_ASSOC)) {
                                                                            ?>
                                                                                <input type="radio" class="btn-check" name="text_color_1" value="<?= $row['color'] ?>" autocomplete="off" id="text_<?= $row['color'] ?>" hidden>
                                                                                <label class="color-label shadow my-1" for="text_<?= $row['color'] ?>" style="background-color: <?= $row['color'] ?>;">
                                                                                    <span class="checkmark">✔</span>
                                                                                    &nbsp;
                                                                                </label>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>

                                                </div>
                                            </div>

                                            <button type="button" name="add" id="add" class="btn btn-success w-25"><?= $block_add ?></button>
                                        </div>
                                    </div>

                                    <input type="hidden" name="operation" value="add">
                                    <input type="hidden" name="origin" value="addPage">
                                    <input type="hidden" name="counter" value="1" id="counter">


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
        </div>
        <div class="col-md-2 col-12">
            <div class="card shadow">
                <h4 class="card-title px-4 pt-3"><?= $common_info ?></h4>
                <div class="card-content px-5 pb-4">
                    <ul>
                        <li><a href="https://www.dmweblab.com/portal/manual.php?prod=2&page=5" target="_blank"><?= $common_see_guide ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('checkbox1');
        const headerSections = document.querySelectorAll('.highlight-section');

        checkbox.addEventListener('change', function() {
            if (checkbox.checked) {
                headerSections.forEach(section => {
                    section.classList.add('highlight-background');
                });
            } else {
                headerSections.forEach(section => {
                    section.classList.remove('highlight-background');
                });
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const selectElement = document.getElementById('block_1_type');

        selectElement.addEventListener('change', function() {
            const selectedValue = selectElement.value;

            // Nascondi tutte le righe
            document.querySelectorAll('.col-12 .row.page').forEach(function(row) {
                row.style.display = 'none';
                // Rimuovi l'attributo data-parsley-required da tutti gli input all'interno delle righe nascoste
                const input = row.querySelector('input');
                if (input) {
                    input.removeAttribute('data-parsley-required');
                }
            });

            // Mostra la riga corrispondente al valore selezionato
            if (selectedValue) {
                const selectedRow = document.querySelector('.page.' + selectedValue);
                if (selectedRow) {
                    selectedRow.style.display = 'block';
                    // Aggiungi l'attributo data-parsley-required all'input visibile
                    const input = selectedRow.querySelector('input');
                    if (input) {
                        input.setAttribute('data-parsley-required', 'true');
                    }
                }
            }
        });

        // Trigger the change event to handle the initial state
        selectElement.dispatchEvent(new Event('change'));
    });
</script>

<?php

if (isset($count)) {
?>
    <script>
        var i = <?= $count ?> - 1;
    </script>
<?php
} else {
?>
    <script>
        var i = 1;
    </script>
<?php
}
?>
<?php
$colors = json_encode($colorArray);
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const checkbox = document.getElementById("checkbox_usefile");
        const contentDiv = document.getElementById("mngpage_content");
        const fileLinkInput = document.getElementById("file_page_link");

        function toggleContentDisabledState() {
            const disable = checkbox.checked;

            // Disabilita/abilita i contenuti del div
            const elements = contentDiv.querySelectorAll("input, textarea, button, select");
            elements.forEach(el => {
                el.disabled = disable;
            });

            contentDiv.style.pointerEvents = disable ? "none" : "auto";
            contentDiv.style.opacity = disable ? 0.5 : 1;

            // Gestione data-parsley-required dinamico su #file_page_link
            if (checkbox.checked) {
                fileLinkInput.setAttribute("data-parsley-required", "true");
            } else {
                fileLinkInput.removeAttribute("data-parsley-required");
            }

            // Parsley: aggiorna validazione
            if ($(fileLinkInput).data('Parsley')) {
                $(fileLinkInput).parsley().destroy();
            }
            $(fileLinkInput).parsley();
        }

        checkbox.addEventListener("change", toggleContentDisabledState);

        // Inizializzazione al caricamento
        toggleContentDisabledState();
    });
</script>

<script type="text/javascript">
    var galleryOptions = '<?php echo $galleryOptions; ?>';
    <?php
    if ($postExist == true) {
        echo 'var postExist = true;';
        echo 'var catOptions = ' . json_encode($catOption) . ';';
        echo 'var block_post_cat = ' . json_encode($block_post_cat) . ';';
        echo 'var block_post_text = ' . json_encode($block_post_text) . ';';
    } else {
        echo 'var postExist = false;';
    }
    ?>
    var colorOptionsBg = '<?php echo $colorOptionsBg; ?>';
    var colorOptionsText = '<?php echo $colorOptionsText; ?>';
    var colors = <?php echo $colors; ?>;
    var block_type_text = '<?php echo $block_type_text; ?>';
    var block_bootstrap = '<?php echo $block_bootstrap ?>';
    var block_title = '<?php echo $block_title; ?>';
    var block_type_image = '<?php echo $block_type_image; ?>';
    var block_type_info = '<?php echo $block_type_info; ?>';
    var block_type_gallery = '<?php echo $block_type_gallery; ?>';
    var block_type_quotes = '<?php echo $block_type_quotes; ?>';
    var block_type_scripts = '<?php echo $block_type_scripts; ?>';
    var block_type_post = '<?php echo $block_type_post; ?>';
    var block_gallery_choose = '<?php echo $block_gallery_choose; ?>';
    var block_quotes_text = '<?php echo $block_quotes_text; ?>';
    var block_script_title = '<?php echo $block_script_title ?>';
    var block_script_warn = '<?php echo $block_script_warn ?>';
    var block_script_desc = '<?php echo $block_script_desc ?>';
    var block_script_file = '<?php echo $block_script_file ?>';
    var block_post_cat_all = '<?php echo $block_post_cat_all; ?>';
    var block_bg_color = '<?php echo $block_bg_color; ?>';
    var block_bg_text = '<?php echo $block_bg_text; ?>';
    var block_color_none = '<?php echo $block_color_none; ?>';
    var block_add = '<?php echo $block_add; ?>';
    var block_image_upload = '<?php echo $block_image_upload; ?>';
    var block_image_default = '<?php echo $block_image_default; ?>';
</script>
<script>
    $(document).ready(function() {
        if (typeof colors !== 'undefined') {
            colors.forEach(function(row) {
                console.log(row.color);
            });
        } else {
            console.error("La variabile 'colors' non è definita");
        }
    });
</script>
<script src="script/mc_addBlockPage.js"></script>
<script>
    $(document).ready(function() {
        $('#addPage').on('submit', function(event) {
            $(".summernote").each(function() {
                var content = $(this).summernote('code');
                $(this).val(content);
            });
        });
    });
</script>