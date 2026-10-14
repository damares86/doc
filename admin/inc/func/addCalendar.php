<?php

declare(strict_types=1);

?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><?= htmlspecialchars((string) ($cal_event_cat_header ?? 'Calendar Categories'), ENT_QUOTES, 'UTF-8') ?></h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav
                    aria-label="breadcrumb"
                    class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php"><?= htmlspecialchars((string) ($common_dashboard ?? 'Dashboard'), ENT_QUOTES, 'UTF-8') ?></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= htmlspecialchars((string) ($cal_event_add_cat_header ?? 'Add Category'), ENT_QUOTES, 'UTF-8') ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <br>

    <script src="script/jscolor.js"></script>

    <script>
        jscolor.presets.default = {
            palette: [
                '#000000', '#7d7d7d', '#870014', '#ec1c23', '#ff7e26', '#fef100', '#22b14b', '#00a1e7', '#3f47cc', '#a349a4',
                '#ffffff', '#c3c3c3', '#b87957', '#feaec9', '#ffc80d', '#eee3af', '#b5e61d', '#99d9ea', '#7092be', '#c8bfe7',
            ],
        };
    </script>

    <section class="section">
        <div class="row">
            <div class="col-md-8 col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="card-title"><?= htmlspecialchars((string) ($cal_event_add_cat_header ?? 'Add Category'), ENT_QUOTES, 'UTF-8') ?></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" action="core/mngCalendar.php" method="POST" data-parsley-validate>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label><?= htmlspecialchars((string) ($cal_event_edit_cat_name_header ?? 'Category Name'), ENT_QUOTES, 'UTF-8') ?><span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group has-icon-left">
                                                <div class="form-check mandatory">
                                                    <div class="position-relative">
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            placeholder="<?= htmlspecialchars((string) ($cal_event_edit_cat_name_header ?? 'Category Name'), ENT_QUOTES, 'UTF-8') ?>"
                                                            name="cat_name"
                                                            data-parsley-required="true" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label><?= htmlspecialchars((string) ($cal_event_edit_cat_color_header ?? 'Category Color'), ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <input name="cat_color" value="#008db1" data-jscolor="{}">
                                            </div>
                                        </div>

                                        <input type="hidden" name="operation" value="add">
                                        <input type="hidden" name="origin" value="addCalendar">

                                        <div class="col-12 d-flex justify-content-end">
                                            <button
                                                type="submit"
                                                class="btn btn-primary me-1 mb-1 shadow">
                                                <?= htmlspecialchars((string) ($common_submit ?? 'Submit'), ENT_QUOTES, 'UTF-8') ?>
                                            </button>
                                            <button
                                                type="reset"
                                                class="btn btn-light-secondary me-1 mb-1 shadow">
                                                <?= htmlspecialchars((string) ($common_reset ?? 'Reset'), ENT_QUOTES, 'UTF-8') ?>
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
                    <h4 class="card-title px-4 pt-3"><?= htmlspecialchars((string) ($common_info ?? 'Information'), ENT_QUOTES, 'UTF-8') ?></h4>
                    <div class="card-content px-5 pb-4">
                        <ul>
                            <li><a href="https://www.dmweblab.com/portal/manual.php?prod=5&page=2" target="_blank"><?= htmlspecialchars((string) ($common_see_guide ?? 'See Guide'), ENT_QUOTES, 'UTF-8') ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>