<?php

declare(strict_types=1);

require_once __DIR__ . '/../funcHeader.php';
?>

<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="card-title"><?= htmlspecialchars((string) ($damares_title ?? 'Damares Info'), ENT_QUOTES, 'UTF-8') ?></h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="core/mngDamares.php" method="POST" data-parsley-validate>
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
                                                        $setting->name = 'debug';
                                                        $stmt = $setting->showAllWhere('id', ['name']);
                                                        $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
                                                        $checked = ($row && (int) ($row['value'] ?? 0) === 1) ? 'checked' : '';
                                                        ?>
                                                        <input type="hidden" name="debug_check" value="yes">
                                                        <input type="checkbox" id="checkbox1" name="debug" class="form-check-input" <?= $checked ?>>
                                                        <label for="checkbox1">&nbsp; &nbsp;<?= htmlspecialchars((string) ($damares_enable ?? 'Enable'), ENT_QUOTES, 'UTF-8') ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br><br><br>
                                    <div class="col-12 d-flex justify-content-start">
                                        <button type="submit" class="btn btn-primary me-1 mb-1 shadow">
                                            <?= htmlspecialchars((string) ($common_submit ?? 'Submit'), ENT_QUOTES, 'UTF-8') ?>
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
                                <h6><?= htmlspecialchars((string) ($damares_clear_title ?? 'Clear Data'), ENT_QUOTES, 'UTF-8') ?></h6>
                            </div>
                            <div class="col-10 text-left">
                                <a href="#" class="btn btn-danger shadow" data-bs-toggle="modal" data-bs-target="#clear">
                                    <?= htmlspecialchars((string) ($damares_clear_button ?? 'Reset'), ENT_QUOTES, 'UTF-8') ?>
                                </a>

                                <!-- Danger theme Modal -->
                                <div class="modal fade text-left" id="clear" tabindex="-1" role="dialog" aria-labelledby="myModalLabel120" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title white" id="myModalLabel120">
                                                    <?= htmlspecialchars((string) ($common_modal_title_sure ?? 'Are you sure?'), ENT_QUOTES, 'UTF-8') ?>
                                                </h5>
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <i data-feather="x"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <?= htmlspecialchars((string) ($damares_modal_body ?? 'Do you want to reset all data and tables?'), ENT_QUOTES, 'UTF-8') ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block"><?= htmlspecialchars((string) ($common_modal_cancel ?? 'Cancel'), ENT_QUOTES, 'UTF-8') ?></span>
                                                </button>
                                                <a href="core/clean.php" class="btn btn-danger ml-1">
                                                    <?= htmlspecialchars((string) ($common_modal_confirm ?? 'Confirm'), ENT_QUOTES, 'UTF-8') ?>
                                                </a>
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