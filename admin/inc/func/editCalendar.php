<?php

$event_id = filter_input(INPUT_GET, 'idToMod');
$calendar->id = $event_id;
$stmt = $calendar->showAllWhere('id', ['id']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
extract($row);

$url_tablePage = filter_input(INPUT_GET, 'tablePage');
$url_pageName = filter_input(INPUT_GET, 'pageName');

?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3 cass="d-inline"><?= $cal_event_cat_header ?></h3>
                <a href="index.php?p=<?= $url_pageName ?>&tablePage=<?= $url_tablePage ?>&pageName=<?= $url_pageName ?>" class="btn icon btn-info shadow mx-3 px-3">
                    <i class="bi bi-arrow-left-circle"></i> &nbsp; <?= $common_back ?>
                </a>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php"><?= $common_dashboard ?></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= $cal_event_edit_cat_header ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <br>

    <script src="script/jscolor.js"></script>

    <script>
        // Here we can adjust defaults for all color pickers on page:
        jscolor.presets.default = {
            palette: [
                '#000000', '#7d7d7d', '#870014', '#ec1c23', '#ff7e26', '#fef100', '#22b14b', '#00a1e7', '#3f47cc', '#a349a4',
                '#ffffff', '#c3c3c3', '#b87957', '#feaec9', '#ffc80d', '#eee3af', '#b5e61d', '#99d9ea', '#7092be', '#c8bfe7',
            ],
            //paletteCols: 12,
            //hideOnPaletteClick: true,
            //width: 271,
            //height: 151,
            //position: 'right',
            //previewPosition: 'right',
            //backgroundColor: 'rgba(51,51,51,1)', controlBorderColor: 'rgba(153,153,153,1)', buttonColor: 'rgba(240,240,240,1)',
        }
    </script>

    <section class="section">
        <div class="row">
            <div class="col-md-8 col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="card-title"><?= $cal_event_edit_cat_header ?></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" action="core/mngCalendar.php" method="POST" data-parsley-validate>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label><?= $cal_event_edit_cat_name_header ?> <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group has-icon-left">
                                                <div class="form-check mandatory">
                                                    <div class="position-relative">
                                                        <input type="text" class="form-control" placeholder="<?= $cal_event_edit_cat_name_header ?>" name="cat_name" data-parsley-required="true" value="<?= $row['cat_name'] ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label><?= $cal_event_edit_cat_color_header ?><span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <input name="cat_color" value="<?= $row['cat_color'] ?>" data-jscolor="{}">
                                            </div>
                                        </div>


                                        <input type="hidden" name="operation" value="edit">
                                        <input type="hidden" name="idToMod" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="origin" value="editCalendar">
                                        <input type="hidden" name="url_tablePage" value="<?= $url_tablePage ?>">
                                        <input type="hidden" name="url_pageName" value="<?= $url_pageName ?>">

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
                            <li><a href="https://www.dmweblab.com/portal/manual.php?prod=5&page=2" target="_blank"><?= $common_see_guide ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>