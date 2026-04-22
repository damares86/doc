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
            <h3><?= $editpage_header ?></h3>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php"><?= $common_dashboard ?></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $editpage_header ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<br>

<?php

$idToMod = filter_input(INPUT_GET, 'idToMod');

$mc->table = 'mc_pages';
$mc->id = $idToMod;
$page_to_edit = $mc->showAllWhere('id', ['id']);

?>


<section class="section">
    <div class="row">
        <div class="col-md-10 col-12">
            <div class="card shadow">
                <div class="card-header">
                    <?php

                    while ($item = $page_to_edit->fetch(PDO::FETCH_ASSOC)) {
                        extract($item);

                        $str = $item['page_name'];
                        if ($item['id'] == 1) {
                            $str = "Home";
                        } else {
                            $str = str_replace('_', ' ', $str);
                            $str = ucfirst($str);
                        }
                    ?>
                        <h4 class="card-title"><?= $editpage_header ?>: <u><?= $str ?></u></h4>
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
                                                    <?php
                                                    $disabled = '';
                                                    if ($item['id'] == 1) {
                                                        $disabled = 'disabled=""';
                                                    }
                                                    ?>
                                                    <input type="text" class="form-control" placeholder="<?= $addpage_name ?>" value="<?= $str ?>" name="page_name" data-parsley-required="true" <?= $disabled ?> />
                                                    <input type="hidden" name="old_page_name" value="<?= $item['page_name'] ?>">
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
                                                    <?php
                                                    $file_checked = $item['link_to_file'] == 'none' ? '' : 'checked';
                                                    ?>
                                                    <input type="checkbox" id="checkbox_usefile" class="form-check-input" name="link_to_file_check" <?= $file_checked ?>>
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
                                                    <?php
                                                    $value_link = $item['link_to_file'] != 'none' ? 'value="' . $item['link_to_file'] . '"' : '';
                                                    ?>
                                                    <input type="text" class="form-control" placeholder="Copia e incolla url del file dal File Manager" id="file_page_link" name="link_to_file" <?= $value_link ?> />

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
                                                                if ($item['layout'] == $style) {
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
                                                            <?php
                                                            $checked = '';

                                                            if ($item['header'] == 1) {
                                                                $checked = 'checked';
                                                            }

                                                            ?>
                                                            <input type="checkbox" id="checkbox1" class="form-check-input" name="use_header" <?= $checked ?>>
                                                            <label for="checkbox1">&nbsp; <?= $addpage_use_header_select ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php
                                            $image = 'visual.jpg';
                                            $gallery = '';
                                            $checked_img = '';
                                            $checked_gallery = '';
                                            if ($item['header_media'] != NULL) {
                                                if (ctype_digit($item['header_media'])) {
                                                    $gallery = $item['header_media'];
                                                    $checked_gallery = 'checked';
                                                } else {
                                                    $image = $item['header_media'];
                                                    $checked_img = 'checked';
                                                }
                                            }
                                            ?>
                                            <div class="row highlight-section">
                                                <div class="col-md-3 mt-3 p-3 border-top">
                                                    <label><?= $addpage_header_style ?> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-9 mt-3 border-top">
                                                    <div class="row mt-3">
                                                        <div class="col border p-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input nomargin" type="radio" name="header" value="image" <?= $checked_img ?>>
                                                                <label class="form-check-label">&nbsp; <?= $addpage_header_img_title ?></label>
                                                                <br>
                                                                <br>
                                                                <span><?= $editpage_current_image ?>: <img src="../uploads/img/<?= $image ?>" class="d-inline w-25"></span>
                                                                <input type="hidden" name="old_header_img" value="<?= $item['header_media'] ?>">

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
                                                                <input class="form-check-input nomargin" type="radio" name="header" value="gallery" <?= $checked_gallery ?>>
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
                                                                                        $selected = '';

                                                                                        if ($item['header_media'] == $row['id']) {
                                                                                            $selected = 'selected';
                                                                                        }

                                                                                        $galleryOptions .= '<option value="' . $row['id'] . '">' . $row['gallery_name'] . '</option>';
                                                                                    ?>

                                                                                        <option value="<?= $row['id'] ?>" <?= $selected ?>><?= $row['gallery_name'] ?></option>

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
                                                    <label><?= $addpage_header_page_name ?> </label>
                                                </div>
                                                <div class="col-md-9 mt-3 mb-3">
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <div class="checkbox">

                                                                <?php
                                                                $checked = '';
                                                                if ($item['use_page_name'] == 1) {
                                                                    $checked = 'checked';
                                                                }
                                                                ?>

                                                                <input type="checkbox" class="form-check-input" name="use_page_name" <?= $checked ?>>
                                                                <label>&nbsp; <b><?= $str ?></b></label>
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

                                                                <?php
                                                                $checked = '';
                                                                if ($item['use_name'] == 1) {
                                                                    $checked = 'checked';
                                                                }
                                                                ?>

                                                                <input type="checkbox" class="form-check-input" name="site_name" <?= $checked ?>>
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

                                                                <?php
                                                                $checked = '';
                                                                if ($item['use_desc'] == 1) {
                                                                    $checked = 'checked';
                                                                }
                                                                ?>

                                                                <input type="checkbox" class="form-check-input" name="site_description" <?= $checked ?>>
                                                                <label>&nbsp; <b><?= $name['value'] ?></b></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row" id="dynamic_field">

                                                <?php
                                                $json_file = 'inc/pages/' . $item['id'] . '.json';
                                                $data = file_get_contents($json_file);
                                                $json_arr = json_decode($data, true);

                                                $counter = $item['counter'];

                                                for ($idx = 1; $idx <= $counter; $idx++) {

                                                    $block_type = $json_arr[$idx]['block' . $idx . '_type'];
                                                    $text_selected = $img_selected = $info_selected = $gallery_selected = $quote_selected = $post_selected =  $script_selected  = '';

                                                    // Imposta la variabile corretta per la selezione
                                                    if ($block_type === 'text') $text_selected = 'selected';
                                                    if ($block_type === 'img') $img_selected = 'selected';
                                                    if ($block_type === 'info') $info_selected = 'selected';
                                                    if ($block_type === 'gallery') $gallery_selected = 'selected';
                                                    if ($block_type === 'quote') $quote_selected = 'selected';
                                                    if ($block_type === 'script') $script_selected = 'selected';
                                                    $plugin->pluginname = 'post';

                                                    if ($block_type === 'post' && $plugin->itemExists('pluginname') && $plugin->isActive() == 1) {
                                                        $post_selected = 'selected';
                                                    }
                                                ?>
                                                    <div class="row border-top" id="block_<?= $idx ?>">
                                                        <div class="col-md-3 mt-3 p-3">
                                                            <label><b><?= $block_title ?> <span><?= $idx ?></span></b></label>
                                                        </div>
                                                        <div class="col-md-5 mt-3  p-3">
                                                            <div class="form-group">
                                                                <div class="form-check mandatory">
                                                                    <div class="position-relative">
                                                                        <fieldset class="form-group">
                                                                            <select class="form-select" id="block_<?= $idx ?>_type" name="block_<?= $idx ?>_type">

                                                                                <option value="text_<?= $idx ?>" <?= $text_selected ?>><?= $block_type_text ?></option>
                                                                                <option value="img_<?= $idx ?>" <?= $img_selected ?>><?= $block_type_image ?></option>
                                                                                <option value="info_<?= $idx ?>" <?= $info_selected ?>><?= $block_type_info ?></option>
                                                                                <option value="gallery_<?= $idx ?>" <?= $gallery_selected ?>><?= $block_type_gallery ?></option>
                                                                                <option value="quote_<?= $idx ?>" <?= $quote_selected ?>><?= $block_type_quotes ?></option>
                                                                                <option value="script_<?= $idx ?>" <?= $script_selected ?>><?= $block_type_scripts ?></option>
                                                                                <?php
                                                                                $plugin->pluginname = "post";
                                                                                $postOption = '';
                                                                                $postExist = false;

                                                                                if ($plugin->itemExists('pluginname') && $plugin->isActive() == 1) {
                                                                                    // $postOption = '<option value="post_' . $idx . '" ' . $post_selected . '>Latest post</option>';

                                                                                    $postExist = true;
                                                                                ?>
                                                                                    <option value="post_<?= $idx ?>" <?= $post_selected ?>><?= $block_type_post ?></option>
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
                                                            <button type="button" name="remove" id="<?= $idx ?>" class="btn btn-danger btn_remove">X</button>
                                                        </div>

                                                        <div class="col-md-3 p-3">
                                                            <label><?= $block_bootstrap ?></label>
                                                        </div>
                                                        <div class="col-md-9 mt-3  px-5">
                                                            <input type="text" class="form-control" placeholder="" name="bootstrap_<?= $idx ?>" value="<?= $json_arr[$idx]['block' . $idx . '_bootstrap'] ?>" />
                                                        </div>

                                                        <div class="col-12 mt-3 mb-3 px-5 pb-3 border-bottom">

                                                            <div class="row page text_<?= $idx ?>">
                                                                <?php
                                                                $text_content = '';
                                                                if ($block_type === 'text') {
                                                                    $text_content = $json_arr[$idx]['block' . $idx];
                                                                }
                                                                ?>
                                                                <textarea class="tiny" name="text_content_<?= $idx ?>"><?= $text_content ?></textarea>
                                                                <!-- <textarea class="summernote" name="text_<?= $idx ?>"></textarea> -->
                                                            </div>
                                                            <div class="row page img_<?= $idx ?>">
                                                                <label><?= $block_image_upload ?> </label>
                                                                <div class="form-group">
                                                                    <div class="form-check">
                                                                        <div class="position-relative">
                                                                            <input class="form-control" type="file" name="img_<?= $idx ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                $actual_img = 'visual.jpg';
                                                                if ($block_type === 'img') {
                                                                    $actual_img = $json_arr[$idx]['block' . $idx];
                                                                }
                                                                ?>
                                                                <span><?= $editpage_current_image ?>: <img src="../uploads/img/<?= $actual_img ?>" class="d-inline w-25"></span>
                                                                <input type="hidden" name="old_img_<?= $idx ?>" value="<?= $actual_img ?>">

                                                            </div>
                                                            <div class="row page info_<?= $idx ?>">
                                                                <label><?= $addpage_header_img_upload ?></label>
                                                                <div class="form-group">
                                                                    <div class="form-check">
                                                                        <div class="position-relative">
                                                                            <input class="form-control" type="file" name="info_img_<?= $idx ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                $actual_img = 'visual.jpg';
                                                                if ($block_type === 'info') {
                                                                    $actual_img = $json_arr[$idx]['block' . $idx . '_info'];
                                                                }
                                                                ?>
                                                                <span><?= $editpage_current_image ?>: <img src="../uploads/img/<?= $actual_img ?>" class="d-inline m-3 w-25"></span>
                                                                <input type="hidden" name="old_info_img_<?= $idx ?>" value="<?= $json_arr[$idx]['block' . $idx . '_info'] ?>">
                                                                <!-- <textarea class="summernote" class="mt-5" name="info_content_1"></textarea> -->
                                                                <textarea class="tiny mt-5" name="info_content_<?= $idx ?>"><?= $json_arr[$idx]['block' . $idx . '_desc'] ?></textarea>
                                                            </div>
                                                            <div class="row page gallery_<?= $idx ?>">
                                                                <div class="col-7">
                                                                    <label class="mb-3"><?= $block_gallery_choose ?> <span class="text-danger">*</span></label>
                                                                    <div class="form-group">
                                                                        <div class="form-check mandatory">
                                                                            <div class="position-relative">
                                                                                <fieldset class="form-group">
                                                                                    <select class="form-select" name="gallery_name_<?= $idx ?>">
                                                                                        <?php
                                                                                        $mc->table = 'mc_galleries';
                                                                                        $galleries = $mc->showAll('id');
                                                                                        while ($row = $galleries->fetch(PDO::FETCH_ASSOC)) {
                                                                                            $selected = '';
                                                                                            if ($json_arr[$idx]['block' . $idx] == $row['id']) {
                                                                                                $selected = 'selected';
                                                                                            }
                                                                                        ?>
                                                                                            <option value="<?= $row['id'] ?>" <?= $selected ?>><?= $row['gallery_name'] ?></option>

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
                                                            <div class="row page quote_<?= $idx ?>">
                                                                <p><?= $block_quotes_text ?></p>
                                                                <input type="hidden" name="quote_<?= $idx ?>" value="q">
                                                            </div>
                                                            <div class="row page script_<?= $idx ?>">
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
                                                                            <input type="text" class="form-control" placeholder="" name="script_<?= $idx ?>" value="<?= $json_arr[$idx]['block' . $idx . '_file'] ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <?php
                                                            if ($postExist == true) {
                                                            ?>
                                                                <div class="row page post_<?= $idx ?>">
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
                                                                                    <select class="form-select w-50" name="post_cat_<?= $idx ?>">
                                                                                        <option value='none'><?= $block_post_cat_all ?></option>
                                                                                        <?php
                                                                                        $catOption = '<option value="none">' . $block_post_cat_all . '</option>';
                                                                                        $post->table = "post_categories";
                                                                                        $cat_stmt = $post->showAll('id');
                                                                                        while ($cat_row = $cat_stmt->fetch(PDO::FETCH_ASSOC)) {
                                                                                            $selected_cat = '';
                                                                                            if ($json_arr[$idx]['block' . $idx . '_cat'] == $cat_row['id']) {
                                                                                                $selected_cat = 'selected';
                                                                                            }
                                                                                            $catOption .= '<option value="' . $cat_row['id'] . '">' . $cat_row['category_name'] . '</option>';

                                                                                        ?>
                                                                                            <option value='<?= $cat_row['id'] ?>' <?= $selected_cat ?>><?= $cat_row['category_name'] ?></option>
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
                                                                            <?php
                                                                            $none_checked = '';
                                                                            if ($json_arr[$idx]['block' . $idx . '_bg'] == 'none') {
                                                                                $none_checked = 'checked';
                                                                            }
                                                                            ?>
                                                                            <input type="radio" class="btn-check" name="bg_color_<?= $idx ?>" value="none" autocomplete="off" id="bg_none_<?= $idx ?>" hidden <?= $none_checked ?>>
                                                                            <label class="color-label bg shadow my-1" for="bg_none_<?= $idx ?>" style="background-color: #e5e5e5;">
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
                                                                                $bg_checked = '';
                                                                                if ($json_arr[$idx]['block' . $idx . '_bg'] == $row['color']) {
                                                                                    $bg_checked = 'checked';
                                                                                }
                                                                            ?>
                                                                                <input type="radio" class="btn-check" name="bg_color_<?= $idx ?>" value="<?= $row['color'] ?>" autocomplete="off" id="bg_<?= $row['color'] ?>_<?= $idx ?>" hidden <?= $bg_checked ?>>
                                                                                <label class="color-label shadow my-1" for="bg_<?= $row['color'] ?>_<?= $idx ?>" style="background-color: <?= $row['color'] ?>;">
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
                                                                            <?php
                                                                            $none_checked = '';
                                                                            if ($json_arr[$idx]['block' . $idx . '_text'] == 'none') {
                                                                                $none_checked = 'checked';
                                                                            }
                                                                            ?>
                                                                            <input type="radio" class="btn-check" name="text_color_<?= $idx ?>" value="none" autocomplete="off" id="text_none_<?= $idx ?>" hidden <?= $none_checked ?>>
                                                                            <label class="color-label text shadow my-1" for="text_none_<?= $idx ?>" style="background-color: #e5e5e5;">
                                                                                <?= $block_color_none ?>
                                                                                <span class="checkmark"></span>
                                                                            </label>

                                                                            <!-- Loop per i colori del Text -->
                                                                            <?php
                                                                            $mc->table = 'mc_color';
                                                                            $colors = $mc->showAll('id');

                                                                            while ($row = $colors->fetch(PDO::FETCH_ASSOC)) {
                                                                                $text_checked = '';
                                                                                if ($json_arr[$idx]['block' . $idx . '_text'] == $row['color']) {
                                                                                    $text_checked = 'checked';
                                                                                }
                                                                            ?>
                                                                                <input type="radio" class="btn-check" name="text_color_<?= $idx ?>" value="<?= $row['color'] ?>" autocomplete="off" id="text_<?= $row['color'] ?>_<?= $idx ?>" hidden <?= $text_checked ?>>
                                                                                <label class="color-label shadow my-1" for="text_<?= $row['color'] ?>_<?= $idx ?>" style="background-color: <?= $row['color'] ?>;">
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

                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" name="add" id="add" class="btn btn-success w-25"><?= $block_add ?></button>


                                    <input type="hidden" name="operation" value="edit">
                                    <input type="hidden" name="origin" value="editPage">
                                    <input type="hidden" name="idToMod" value="<?= $idToMod ?>">
                                    <input type="hidden" name="counter" value="<?= $counter ?>" id="counter">

                                <?php
                            }
                                ?>

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
        // Gestisce il checkbox per evidenziare la sezione dell'header
        const checkbox = document.getElementById('checkbox1');
        const headerSections = document.querySelectorAll('.highlight-section');

        // Controlla lo stato iniziale della checkbox
        if (checkbox.checked) {
            headerSections.forEach(section => {
                section.classList.add('highlight-background');
            });
        }

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

        // Funzione per aggiornare la visibilità dei blocchi
        function updateBlockVisibility(selectElement) {
            const selectedValue = selectElement.value;
            const blockId = selectElement.id.replace('_type', ''); // Identifica l'ID del blocco

            // console.log("Processing block ID:", blockId);
            // console.log("Selected value:", selectedValue);

            // Verifica se l'elemento del blocco esiste nel DOM
            const blockElement = document.getElementById(blockId);
            if (!blockElement) {
                console.log(`Block element with ID ${blockId} not found in the DOM.`);
                return;
            }

            // Nascondi tutte le righe relative al blocco corrente
            blockElement.querySelectorAll('.row.page').forEach(function(row) {
                row.style.display = 'none';
                const input = row.querySelector('input');
                if (input) {
                    input.removeAttribute('data-parsley-required');
                }
            });

            // Mostra la riga corrispondente al valore selezionato
            const selectedRow = blockElement.querySelector('.page.' + selectedValue);
            if (selectedRow) {
                // console.log("Selected Row found: ", selectedRow);
                selectedRow.style.display = 'block';
                const input = selectedRow.querySelector('input');
                if (input && input.type !== 'file') {
                    input.setAttribute('data-parsley-required', 'true');
                }
            } else {
                // console.log("Selected Row not found for block: ", blockId);
            }
        }

        // Funzione per inizializzare la visibilità dei blocchi in base ai dati
        function initializeBlockSelects() {
            document.querySelectorAll('select[id^="block_"][id$="_type"]').forEach(function(selectElement) {
                selectElement.addEventListener('change', function() {
                    updateBlockVisibility(selectElement);
                });

                // Inizializza lo stato attuale per ogni blocco
                updateBlockVisibility(selectElement);
            });
        }

        // Inizializzazione per i blocchi già presenti
        initializeBlockSelects();

        // Gestione per i nuovi blocchi aggiunti dinamicamente
        document.getElementById('add').addEventListener('click', function() {
            // Utilizzare un ritardo per garantire che il blocco sia effettivamente aggiunto al DOM
            setTimeout(() => {
                console.log("Reinitializing after adding new blocks.");
                initializeBlockSelects();
            }, 500); // Aumenta il ritardo se necessario
        });
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
        var i = <?= $counter ?>;
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
    var colors = <?= $colors; ?>;
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
    var block_bg_color = '<?php echo $block_bg_color; ?>';
    var block_bg_text = '<?php echo $block_bg_text; ?>';
    var block_color_none = '<?php echo $block_color_none; ?>';
    var block_add = '<?php echo $block_add; ?>';
    var block_image_upload = '<?php echo $block_image_upload; ?>';
    var block_image_default = '<?php echo $block_image_default; ?>';
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