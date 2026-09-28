<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../../inc/funcHeader.php';

$setting->name = 'reg_role';
$stmt = $setting->showAllWhere('id', ['name']);
$row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
$reg_role = (string) ($row['value'] ?? '');
?>

<section class="section">
    <div class="row">
        <div class="col-md-8 col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="card-title"><?= htmlspecialchars((string) ($regset_title ?? 'Registration Settings'), ENT_QUOTES, 'UTF-8') ?></h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="core/mngRegister.php" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label><?= htmlspecialchars((string) ($common_role ?? 'Default Role'), ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="form-check mandatory">
                                                <div class="position-relative">
                                                    <fieldset class="form-group">
                                                        <select class="form-select" id="role" name="role">
                                                            <?php
                                                            $stmt = $role->showAll('id');
                                                            if ($stmt instanceof PDOStatement) {
                                                                while ($rRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                                    if ((int) $rRow['id'] > 1) {
                                                                        $selected = ($reg_role === (string) $rRow['rolename']) ? 'selected' : '';
                                                                        ?>
                                                                        <option value="<?= (int) $rRow['id'] ?>" <?= $selected ?>><?= htmlspecialchars((string) $rRow['rolename'], ENT_QUOTES, 'UTF-8') ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="reg_role" value="1">

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
                <div class="card-header">
                    <h4 class="card-title"><?= htmlspecialchars((string) ($common_info ?? 'Info'), ENT_QUOTES, 'UTF-8') ?></h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>