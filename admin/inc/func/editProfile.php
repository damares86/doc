<?php

$account->id = $_SESSION['account_id'];
$stmt1 = $account->showAllWhere('id', ['id']);
?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><?= $account_edit_header ?></h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php"><?= $common_dashboard ?></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= $account_edit_header ?>
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
                        <h4 class="card-title"><?= $account_edit_title ?></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <?php
                            $id = "";
                            $username = "";
                            $email = "";
                            $avatar = "";
                            $roleId = "";

                            while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {

                                $id = $row1['id'];
                                $username = $row1['username'];
                                $email = $row1['email'];
                                $avatar = $row1['avatar'];

                                if (!$avatar) {
                                    $avatar = "default.png";
                                }

                                $accountroles->account_id = $row1['id'];
                                $stmt2 = $accountroles->showAllWhere('id', ['account_id']);

                                while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                                    $roleId = $row2['role_id'];
                                }
                            }
                            ?>
                            <form class="form form-horizontal" action="core/mngAccounts.php" method="POST" enctype="multipart/form-data" data-parsley-validate>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label><?= $common_username ?> <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group has-icon-left">
                                                <div class="form-check mandatory">
                                                    <div class="position-relative">
                                                        <input type="text" class="form-control" placeholder="Name" id="username" name="username" data-parsley-required="true" value="<?= $username ?>" />
                                                        <div class="form-control-icon">
                                                            <i class="bi bi-person"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label><?= $common_email ?> <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group has-icon-left">
                                                <div class="form-check mandatory">
                                                    <div class="position-relative">
                                                        <input type="email" class="form-control" placeholder="Email" id="email" name="email" data-parsley-required="true" value="<?= $email ?>" />
                                                        <div class="form-control-icon">
                                                            <i class="bi bi-envelope"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-3">
                                            <label><?= $account_add_avatar ?></label>
                                        </div>
                                        <div class="col-md-2 mb-2 text-center">
                                            <div class="avatar avatar-lg me-3">
                                                <img src="uploads/avatar/<?= $avatar ?>" alt="" srcset="">
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
                                        <input type="hidden" name="avatar_orig" value="<?= $avatar ?>">
                                        <input type="hidden" name="role" value="<?= $roleId ?>">
                                        <input type="hidden" name="idToMod" value="<?= $id ?>">
                                        <input type="hidden" name="origin" value="editAccount">

                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1 shadow">
                                                <?= $common_update ?>
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
                            <li><a href="http://dmweblab.com/portal/manual.php?prod=1&page=6" target="_blank"><?= $common_see_guide ?></a></li>
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
                        <h4 class="card-title"><?= $account_edit_password ?></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" action="core/mngAccounts.php" method="POST" enctype="multipart/form-data" data-parsley-validate>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label><?= $common_password ?> <span class="text-danger">*</span></label>
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
                                                <?= $common_submit ?>
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