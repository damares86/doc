<?php

declare(strict_types=1);

$idToMod = (int) (filter_input(INPUT_GET, 'idToMod', FILTER_VALIDATE_INT) ?? 0);
$account->id = $idToMod;
$stmt1 = $account->showAllWhere('id', ['id']);

$url_tablePage = (string) (filter_input(INPUT_GET, 'tablePage', FILTER_DEFAULT) ?? '1');
$url_pageName = (string) (filter_input(INPUT_GET, 'pageName', FILTER_DEFAULT) ?? 'allAccounts');
?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3 class="d-inline"><?= htmlspecialchars((string) ($account_edit_header ?? 'Edit account'), ENT_QUOTES, 'UTF-8') ?></h3>
                <a href="index.php?p=<?= urlencode($url_pageName) ?>&tablePage=<?= urlencode($url_tablePage) ?>&pageName=<?= urlencode($url_pageName) ?>" class="btn icon btn-info shadow mx-3 px-3">
                    <i class="bi bi-arrow-left-circle"></i> &nbsp; <?= htmlspecialchars((string) ($common_back ?? 'Back'), ENT_QUOTES, 'UTF-8') ?>
                </a>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php"><?= htmlspecialchars((string) ($common_dashboard ?? 'Dashboard'), ENT_QUOTES, 'UTF-8') ?></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= htmlspecialchars((string) ($account_edit_header ?? 'Edit account'), ENT_QUOTES, 'UTF-8') ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <br>

    <?php
    $id = 0;
    $username = '';
    $email = '';
    $avatar = 'default.png';
    $roleId = 0;
    $details = [];
    $details_opt = [];

    if ($stmt1 instanceof PDOStatement) {
        $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        if ($row1) {
            $id = (int) ($row1['id'] ?? 0);
            $username = (string) ($row1['username'] ?? '');
            $email = (string) ($row1['email'] ?? '');
            $avatar = !empty($row1['avatar']) ? (string) $row1['avatar'] : 'default.png';

            if (!empty($row1['details'])) {
                $unserialized = @unserialize((string) $row1['details']);
                $details = is_array($unserialized) ? $unserialized : [];
            }
            if (!empty($row1['details_opt'])) {
                $unserializedOpt = @unserialize((string) $row1['details_opt']);
                $details_opt = is_array($unserializedOpt) ? $unserializedOpt : [];
            }

            $accountroles->account_id = $id;
            $roleId = (int) ($accountroles->showAccountRolesId() ?? 0);
        }
    }
    ?>

    <section class="section">
        <div class="row">
            <div class="col-md-8 col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="card-title"><?= htmlspecialchars((string) ($account_edit_title ?? 'Edit'), ENT_QUOTES, 'UTF-8') ?> <b><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></b></h4>
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
                                                        <input type="text" class="form-control" placeholder="Name" id="username" name="username" data-parsley-required="true" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>" />
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
                                                        <input type="email" class="form-control" placeholder="Email" id="email" name="email" data-parsley-required="true" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" />
                                                        <div class="form-control-icon">
                                                            <i class="bi bi-envelope"></i>
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
                                                                    while ($rRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                                        $selected = ((int) $rRow['id'] === $roleId) ? 'selected' : '';
                                                                        ?>
                                                                        <option value="<?= (int) $rRow['id'] ?>" <?= $selected ?>><?= htmlspecialchars((string) $rRow['rolename'], ENT_QUOTES, 'UTF-8') ?></option>
                                                                        <?php
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
                                            $counter = 0;
                                            foreach ($account_details as $item) {
                                                $item_label = ucfirst($item);
                                                $value = '';
                                                if (isset($details[$counter]) && is_array($details[$counter])) {
                                                    $vals = array_values($details[$counter]);
                                                    $value = (string) ($vals[0] ?? '');
                                                }
                                                $type = ($item === 'birth') ? 'date' : 'text';
                                                ?>
                                                <div class="col-md-3">
                                                    <label><?= htmlspecialchars($item_label, ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <div class="form-check mandatory">
                                                            <div class="position-relative">
                                                                <input type="<?= $type ?>" class="form-control" placeholder="<?= htmlspecialchars($item_label, ENT_QUOTES, 'UTF-8') ?>" name="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>" data-parsley-required="true" value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                $counter++;
                                            }
                                        }

                                        if (isset($account_details_opt) && is_array($account_details_opt)) {
                                            $counter = 0;
                                            foreach ($account_details_opt as $item) {
                                                $item_label = ucfirst($item);
                                                $value = '';
                                                if (isset($details_opt[$counter]) && is_array($details_opt[$counter])) {
                                                    $vals = array_values($details_opt[$counter]);
                                                    $value = (string) ($vals[0] ?? '');
                                                }
                                                $type = ($item === 'birth') ? 'date' : 'text';
                                                ?>
                                                <div class="col-md-3">
                                                    <label><?= htmlspecialchars($item_label, ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars((string) ($account_add_optional ?? '(optional)'), ENT_QUOTES, 'UTF-8') ?></label>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <div class="position-relative">
                                                            <input type="<?= $type ?>" class="form-control" placeholder="<?= htmlspecialchars($item_label, ENT_QUOTES, 'UTF-8') ?>" name="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>" value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                $counter++;
                                            }
                                        }
                                        ?>

                                        <div class="col-md-3">
                                            <label><?= htmlspecialchars((string) ($account_add_avatar ?? 'Avatar'), ENT_QUOTES, 'UTF-8') ?></label>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <div class="avatar avatar-lg me-3">
                                                <img src="uploads/avatar/<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar">
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <div>
                                                    <input class="form-control" type="file" id="formFile" name="avatar" />
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="operation" value="edit">
                                        <input type="hidden" name="avatar_orig" value="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="idToMod" value="<?= $id ?>">
                                        <input type="hidden" name="origin" value="editAccount">
                                        <input type="hidden" name="url_tablePage" value="<?= htmlspecialchars($url_tablePage, ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="url_pageName" value="<?= htmlspecialchars($url_pageName, ENT_QUOTES, 'UTF-8') ?>">

                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1 shadow">
                                                <?= htmlspecialchars((string) ($common_update ?? 'Update'), ENT_QUOTES, 'UTF-8') ?>
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

    <section class="section">
        <div class="row">
            <div class="col-md-8 col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="card-title"><?= htmlspecialchars((string) ($account_edit_password ?? 'Edit password'), ENT_QUOTES, 'UTF-8') ?></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" action="core/mngAccounts.php" method="POST" data-parsley-validate>
                                <div class="form-body">
                                    <div class="row">
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
                                        <input type="hidden" name="operation" value="password">
                                        <input type="hidden" name="idToMod" value="<?= $id ?>">
                                        <input type="hidden" name="origin" value="editAccount">
                                        <input type="hidden" name="url_tablePage" value="<?= htmlspecialchars($url_tablePage, ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="url_pageName" value="<?= htmlspecialchars($url_pageName, ENT_QUOTES, 'UTF-8') ?>">

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
        </div>
    </section>
</div>

<script>
    const togglePass = document.getElementById('togglePassword');
    if (togglePass) {
        togglePass.addEventListener('click', function() {
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