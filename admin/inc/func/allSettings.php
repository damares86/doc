<?php

declare(strict_types=1);

require_once __DIR__ . '/../funcHeader.php';
?>

<section class="section">
    <div class="row">
        <div class="col-md-8 col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="card-title"><?= htmlspecialchars((string) ($settings_all_title ?? 'Settings'), ENT_QUOTES, 'UTF-8') ?></h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="core/mngSettings.php" method="POST" data-parsley-validate>
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label><?= htmlspecialchars((string) ($settings_all_lang ?? 'Language'), ENT_QUOTES, 'UTF-8') ?> </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="position-relative">
                                                <fieldset class="form-group">
                                                    <select class="form-select" id="lang" name="lang">
                                                        <?php
                                                        $localeDir = __DIR__ . '/../../locale/';
                                                        $scan = is_dir($localeDir) ? scandir($localeDir) : [];
                                                        $exclude = ['..', '.', '.gitkeep', 'fm_translation.json'];
                                                        foreach ($scan as $folder) {
                                                            if (!in_array($folder, $exclude, true) && is_dir($localeDir . $folder)) {
                                                                $selected = ($folder === ($lang ?? 'en')) ? 'selected' : '';
                                                                ?>
                                                                <option value="<?= htmlspecialchars($folder, ENT_QUOTES, 'UTF-8') ?>" <?= $selected ?>><?= htmlspecialchars($folder, ENT_QUOTES, 'UTF-8') ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $setting->name = 'noreply';
                                    $stmt = $setting->showAllWhere('id', ['name']);
                                    $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
                                    $reset = (string) ($row['value'] ?? '');
                                    ?>

                                    <div class="col-md-3 border-top mt-3 pt-3">
                                        <label><?= htmlspecialchars((string) ($settings_all_noreply ?? 'No-reply email'), ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9 border-top mt-3 pt-3">
                                        <div class="form-group has-icon-left">
                                            <div class="form-check mandatory">
                                                <div class="position-relative">
                                                    <input type="email" class="form-control" placeholder="Email" name="noreply" data-parsley-required="true" value="<?= htmlspecialchars($reset, ENT_QUOTES, 'UTF-8') ?>" />
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-envelope"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 border-top mt-3 pt-3">
                                        <label><?= htmlspecialchars((string) ($settings_layout_title ?? 'Layout'), ENT_QUOTES, 'UTF-8') ?> </label>
                                    </div>
                                    <div class="col-md-9 border-top mt-3 pt-3">
                                        <div class="form-group">
                                            <div class="position-relative">
                                                <fieldset class="form-group">
                                                    <select class="form-select" id="layout" name="layout">
                                                        <?php
                                                        $setting->name = 'layout';
                                                        $stmt = $setting->showAllWhere('id', ['name']);
                                                        $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
                                                        $currentLayout = (string) ($row['value'] ?? 'v');
                                                        $selHoriz = ($currentLayout === 'h') ? 'selected' : '';
                                                        $selVert = ($currentLayout === 'v') ? 'selected' : '';
                                                        ?>
                                                        <option value="h" <?= $selHoriz ?>><?= htmlspecialchars((string) ($settings_layout_horizontal ?? 'Horizontal'), ENT_QUOTES, 'UTF-8') ?></option>
                                                        <option value="v" <?= $selVert ?>><?= htmlspecialchars((string) ($settings_layout_vertical ?? 'Vertical'), ENT_QUOTES, 'UTF-8') ?></option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>

                                    <br><br><br>
                                    <hr>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1 shadow">
                                            <?= htmlspecialchars((string) ($common_submit ?? 'Submit'), ENT_QUOTES, 'UTF-8') ?>
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
                <h4 class="card-title px-4 pt-3"><?= htmlspecialchars((string) ($common_info ?? 'Info'), ENT_QUOTES, 'UTF-8') ?></h4>
                <div class="card-content px-5 pb-4">
                    <ul>
                        <li><a href="http://dmweblab.com/portal/manual.php?prod=1&page=11" target="_blank"><?= htmlspecialchars((string) ($common_see_guide ?? 'See guide'), ENT_QUOTES, 'UTF-8') ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>