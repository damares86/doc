<?php

declare(strict_types=1);

$accountId = (int) ($_SESSION['account_id'] ?? 0);
$account->id = $accountId;
$stmt1 = $account->showAllWhere('id', ['id']);
?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><?= htmlspecialchars((string) ($account_edit_header ?? 'Edit profile'), ENT_QUOTES, 'UTF-8') ?></h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php"><?= htmlspecialchars((string) ($common_dashboard ?? 'Dashboard'), ENT_QUOTES, 'UTF-8') ?></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= htmlspecialchars((string) ($account_edit_header ?? 'Edit profile'), ENT_QUOTES, 'UTF-8') ?>
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
                        <h4 class="card-title"><?= htmlspecialchars((string) ($account_edit_title ?? 'Profile'), ENT_QUOTES, 'UTF-8') ?></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <?php
                            $id = $accountId;
                            $username = '';
                            $email = '';
                            $avatar = 'default.png';
                            $roleId = (int) ($_SESSION['role_id'] ?? 0);

                            if ($stmt1 instanceof PDOStatement) {
                                $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                                if ($row1) {
                                    $id = (int) ($row1['id'] ?? $accountId);
                                    $username = (string) ($row1['username'] ?? '');
                                    $email = (string) ($row1['email'] ?? '');
                                    $avatar = !empty($row1['avatar']) ? (string) $row1['avatar'] : 'default.png';

                                    $accountroles->account_id = $id;
                                    $rId = $accountroles->showAccountRolesId();
                                    if ($rId) {
                                        $roleId = (int) $rId;
                                    }
                                }
                            }
                            ?>
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
                                            <label><?= htmlspecialchars((string) ($account_add_avatar ?? 'Avatar'), ENT_QUOTES, 'UTF-8') ?></label>
                                        </div>
                                        <div class="col-md-2 mb-2 text-center">
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
                                        <input type="hidden" name="role" value="<?= $roleId ?>">
                                        <input type="hidden" name="idToMod" value="<?= $id ?>">
                                        <input type="hidden" name="origin" value="editAccount">

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
                                                        <input type="password" class="form-control" placeholder="Password" name="password" data-parsley-required="true" />
                                                        <div class="form-control-icon">
                                                            <i class="bi bi-lock"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="operation" value="password">
                                        <input type="hidden" name="idToMod" value="<?= $id ?>">
                                        <input type="hidden" name="origin" value="editAccount">

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