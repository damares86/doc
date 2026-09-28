<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../../inc/funcHeader.php';

$verify->table = 'verify';
$stmt = $verify->showAll('id');
$row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
$publicKey = (string) ($row['public'] ?? '');
$secretKey = (string) ($row['secret'] ?? '');
?>

<section class="section">
    <div class="row">
        <div class="col-md-8 col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="card-title"><?= htmlspecialchars((string) ($recap_title ?? 'reCAPTCHA Settings'), ENT_QUOTES, 'UTF-8') ?></h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="core/mngRecaptcha.php" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label><?= htmlspecialchars((string) ($recap_public ?? 'Public Key'), ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group has-icon-left">
                                            <div class="form-check mandatory">
                                                <div class="position-relative">
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        placeholder="Public key"
                                                        id="public"
                                                        name="public"
                                                        value="<?= htmlspecialchars($publicKey, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-parsley-required="true"
                                                    />
                                                    <div class="form-control-icon">
                                                        <i class="key"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label><?= htmlspecialchars((string) ($recap_secret ?? 'Secret Key'), ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group has-icon-left">
                                            <div class="form-check mandatory">
                                                <div class="position-relative">
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        placeholder="Secret key"
                                                        id="secret"
                                                        name="secret"
                                                        value="<?= htmlspecialchars($secretKey, ENT_QUOTES, 'UTF-8') ?>"
                                                        data-parsley-required="true"
                                                    />
                                                    <div class="form-control-icon">
                                                        <i class="key"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="recap" value="1">

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