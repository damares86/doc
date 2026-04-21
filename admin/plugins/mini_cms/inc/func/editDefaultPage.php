<?php
// $summernote = true;
?>

<style>
    .row.page {
        display: none
    }
</style>

<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3><?=$editpage_header?></h3>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php"><?= $common_dashboard ?></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?=$editpage_header?>
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
                        $str = str_replace('_', ' ', $str);
                        $str = ucfirst($str);
                    ?>
                        <h4 class="card-title"><?=$editpage_header?>: <u><?= $str ?></u></h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="core/mngPage.php" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <div class="form-body">
                                <div class="row">

                                    <div class="col-md-3 mt-3 pt-3">
                                        <label><?=$addpage_use_header?> <span class="text-danger">*</span></label>
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
                                                    <label for="checkbox1">&nbsp; <?=$addpage_use_header_select?></label>
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
                                            <label><?=$addpage_header_style ?> <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9 mt-3 border-top">
                                            <div class="row mt-3">
                                                <div class="col border p-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input nomargin" type="radio" name="header" value="image" <?= $checked_img ?>>
                                                        <label class="form-check-label">&nbsp; <?=$addpage_header_img_title?></label>
                                                        <br>
                                                        <br>
                                                        <span><?=$editpage_current_image?>: <img src="../uploads/img/<?= $image ?>" class="d-inline w-25"></span>
                                                        <input type="hidden" name="old_header_img" value="<?= $item['header_media'] ?>">

                                                        <br>
                                                        <br>

                                                        <label><?=$addpage_header_img_upload?> </label>

                                                        <div class="form-group">
                                                            <div class="form-check">
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
                                                        <label class="form-check-label">&nbsp; <?=$addpage_header_gallery_title?></label>
                                                        <br><br>
                                                        <label><?=$addpage_header_gallery_choose ?> <span class="text-danger">*</span></label>

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
                                            <label>Use page name </label>
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
                                            <label><?=$addpage_header_site_name ?> </label>
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
                                            <label><?=$addpage_header_site_description?> </label>
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

                                    <?php
                                    if ($item['id'] == 2) {
                                        $json_file = 'inc/pages/2.json';
                                        $data = file_get_contents($json_file);
                                        $json_arr = json_decode($data, true);
                                        // print_r($json_arr);
                                    ?>

                                        <div class="col-md-3 mt-3">
                                            <label><?=$editpage_link_maps?> </label>
                                        </div>
                                        <div class="col-md-9 mt-3">
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <div class="position-relative">
                                                        <textarea name="maps"cols="50" rows="6"><?=$json_arr[2]['block2']?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label><?=$editpage_contact?></label>
                                        </div>
                                        <div class="col-md-9 mt-3">
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <div class="position-relative">
                                                        <textarea name="contacts" class="tiny" cols="60" rows="5"><?=$json_arr[1]['block1']?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <input type="hidden" name="operation" value="editDefault">
                                    <input type="hidden" name="origin" value="editDefaultPage">
                                    <input type="hidden" name="idToMod" value="<?= $idToMod ?>">

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
                        <li><a href="https://www.dmweblab.com/portal/manual.php?prod=2&parent=1&page=2" target="_blank"><?= $common_see_guide ?></a></li>
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

    });
</script>