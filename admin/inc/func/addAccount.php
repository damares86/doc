<?php

declare(strict_types=1);

?>
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3><?= htmlspecialchars((string) ($account_add_header ?? 'Add account'), ENT_QUOTES, 'UTF-8') ?></h3>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php"><?= htmlspecialchars((string) ($common_dashboard ?? 'Dashboard'), ENT_QUOTES, 'UTF-8') ?></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= htmlspecialchars((string) ($account_add_header ?? 'Add account'), ENT_QUOTES, 'UTF-8') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<br>

<section class="section">
    <div class="row">
        <div class="col-md-8 col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="card-title"><?= htmlspecialchars((string) ($account_add_title ?? 'New account'), ENT_QUOTES, 'UTF-8') ?></h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="core/mngAccounts.php" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label><?= htmlspecialchars((string) ($common_username ?? 'Username'), ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group has-icon-left">
                                            <div class="form-check mandatory">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="Name" name="username" data-parsley-required="true" />
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label><?= htmlspecialchars((string) ($common_email ?? 'Email'), ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group has-icon-left">
                                            <div class="form-check mandatory">
                                                <div class="position-relative">
                                                    <input type="email" class="form-control" placeholder="Email" name="email" data-parsley-required="true" />
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-envelope"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label><?= htmlspecialchars((string) ($common_password ?? 'Password'), ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group has-icon-left">
                                            <div class="form-check mandatory">
                                                <div class="position-relative">
                                                    <input type="password" class="form-control" id="password" placeholder="Password" name="password" data-parsley-required="true" />
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-lock"></i>
                                                    </div>
                                                    <div class="toggle-password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                                        <i class="bi bi-eye" id="togglePassword"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label><?= htmlspecialchars((string) ($common_role ?? 'Role'), ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
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
                                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                                    if ((int) $row['id'] > 1) {
                                                                        ?>
                                                                        <option value="<?= (int) $row['id'] ?>"><?= htmlspecialchars((string) $row['rolename'], ENT_QUOTES, 'UTF-8') ?></option>
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

                                    <?php
                                    require __DIR__ . '/../../core/accountDetails.php';
                                    if (isset($account_details) && is_array($account_details)) {
                                        foreach ($account_details as $item) {
                                            $item_label = ucfirst($item);
                                            $type = ($item === 'birth') ? 'date' : 'text';
                                            ?>
                                            <div class="col-md-3">
                                                <label><?= htmlspecialchars($item_label, ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <div class="form-check mandatory">
                                                        <div class="position-relative">
                                                            <input type="<?= $type ?>" class="form-control" placeholder="<?= htmlspecialchars($item_label, ENT_QUOTES, 'UTF-8') ?>" name="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>" data-parsley-required="true" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }

                                    if (isset($account_details_opt) && is_array($account_details_opt)) {
                                        foreach ($account_details_opt as $item) {
                                            $item_label = ucfirst($item);
                                            $type = ($item === 'birth') ? 'date' : 'text';
                                            ?>
                                            <div class="col-md-3">
                                                <label><?= htmlspecialchars($item_label, ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars((string) ($account_add_optional ?? '(optional)'), ENT_QUOTES, 'UTF-8') ?></label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <div class="position-relative">
                                                        <input type="<?= $type ?>" class="form-control" placeholder="<?= htmlspecialchars($item_label, ENT_QUOTES, 'UTF-8') ?>" name="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>

                                    <div class="col-md-3">
                                        <label><?= htmlspecialchars((string) ($account_add_avatar ?? 'Avatar'), ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars((string) ($account_add_optional ?? '(optional)'), ENT_QUOTES, 'UTF-8') ?></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="position-relative">
                                                <input class="form-control" type="file" id="formFile" name="avatar" />
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="operation" value="add">
                                    <input type="hidden" name="origin" value="addAccount">

                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1 shadow">
                                            <?= htmlspecialchars((string) ($common_submit ?? 'Submit'), ENT_QUOTES, 'UTF-8') ?>
                                        </button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1 shadow">
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
                <h4 class="card-title px-4 pt-3"><?= htmlspecialchars((string) ($common_info ?? 'Info'), ENT_QUOTES, 'UTF-8') ?></h4>
                <div class="card-content px-5 pb-4">
                    <ul>
                        <li><a href="http://dmweblab.com/portal/manual.php?prod=1&page=6" target="_blank"><?= htmlspecialchars((string) ($common_see_guide ?? 'See guide'), ENT_QUOTES, 'UTF-8') ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const toggleBtn = document.getElementById('togglePassword');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            if (passwordInput) {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.classList.remove('bi-eye');
                    this.classList.add('bi-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    this.classList.remove('bi-eye-slash');
                    this.classList.add('bi-eye');
                }
            }
        });
    }
</script>