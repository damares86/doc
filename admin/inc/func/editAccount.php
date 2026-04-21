<?php

$account->id = filter_input(INPUT_GET, "idToMod");
$stmt1 = $account->showAllWhere('id', ['id']);

$url_tablePage = filter_input(INPUT_GET, 'tablePage');
$url_pageName = filter_input(INPUT_GET, 'pageName');
?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3 class="d-inline"><?= $account_edit_header ?></h3>
                <a href="index.php?p=<?=$url_pageName?>&tablePage=<?=$url_tablePage?>&pageName=<?=$url_pageName?>" class="btn icon btn-info shadow mx-3 px-3">
                    <i class="bi bi-arrow-left-circle"></i> &nbsp; <?=$common_back?>
                </a>
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

    <?php
    $id = "";
    $username = "";
    $email = "";
    $avatar = "";
    $roleId = "";
    $details = "";
    $details_opt = "";

    while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
        extract($row1);

        $id = $row1['id'];
        $username = $row1['username'];
        $email = $row1['email'];
        $avatar = $row1['avatar'];
        $details = unserialize($row1['details']);
        $details_opt = unserialize($row1['details_opt']);


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

    <section class="section">
        <div class="row">
            <div class="col-md-8 col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="card-title"><?= $account_edit_title ?> <b><?= $username ?></b> </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
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
                                            <label><?= $common_role ?> <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <div class="form-check mandatory">
                                                    <div class="position-relative">
                                                        <fieldset class="form-group">
                                                            <select class="form-select" id="role" name="role">
                                                                <?php
                                                                $stmt = $role->showAll('id');
                                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                                    $selected = "";

                                                                    if ($row['id'] == $roleId) {
                                                                        $selected = "selected";
                                                                    }
                                                                ?>

                                                                    <option value="<?= $row['id'] ?>" <?= $selected ?>><?= $row['rolename'] ?></option>

                                                                <?php

                                                                }
                                                                ?>
                                                            </select>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php

                                        require "core/accountDetails.php";

                                        $counter = 0;
                                        foreach ($account_details as $item) {

                                            $label = "account_add_$item";
                                            $array_value = array_values($details[$counter]);
                                            $value = $array_value[0];


                                            $item_label = ucfirst($item);

                                        ?>
                                            <div class="col-md-3">
                                                <label><?= $item_label ?> <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <div class="form-check mandatory">
                                                        <div class="position-relative">
                                                            <?php

                                                            $type = "text";
                                                            if ($item == "birth") {
                                                                $type = "date";
                                                            }
                                                            ?>
                                                            <input type="<?= $type ?>" class="form-control" placeholder="<?= $item_label ?>" name="<?= $item ?>" data-parsley-required="true" value="<?= $value ?>" />

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php
                                            $counter++;
                                        }

                                        $counter = 0;
                                        foreach ($account_details_opt as $item) {

                                            $label = "account_add_$item";
                                            // if(array_values($details_opt[$counter])){
                                            $array_value = array_values($details_opt[$counter]);
                                            $value = $array_value[0];
                                            // }

                                        ?>
                                            <div class="col-md-3">
                                                <label><?= $$label ?> <?= $account_add_optional ?></label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <div class="position-relative">
                                                        <?php
                                                        $type = "text";
                                                        if ($item == "birth") {
                                                            $type = "date";
                                                        }
                                                        ?>
                                                        <input type="<?= $type ?>" class="form-control" placeholder="<?= $$label ?>" name="<?= $item ?>" data-parsley-required="true" value="<?= $value ?>" />

                                                    </div>
                                                </div>
                                            </div>

                                        <?php
                                            $counter++;
                                        }

                                        ?>


                                        <div class="col-md-3">
                                            <label><?= $account_add_avatar ?></label>
                                        </div>
                                        <div class="col-md-2 text-center">
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
                                        <input type="hidden" name="idToMod" value="<?= $id ?>">
                                        <input type="hidden" name="origin" value="editAccount">
                                        <input type="hidden" name="url_tablePage" value="<?= $url_tablePage ?>">
                                        <input type="hidden" name="url_pageName" value="<?= $url_pageName ?>">

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

                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">
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

<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const passwordIcon = this;

        // Controlla il tipo di input e cambia tra password e text
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('bi-eye');
            passwordIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('bi-eye-slash');
            passwordIcon.classList.add('bi-eye');
        }

        const passwordConfirmInput = document.getElementById('password_confirm');
        const passwordConfirmIcon = this;

        // Controlla il tipo di input e cambia tra password e text
        if (passwordConfirmInput.type === 'password') {
            passwordConfirmInput.type = 'text';
            passwordConfirmInput.classList.remove('bi-eye');
            passwordConfirmInput.classList.add('bi-eye-slash');
        } else {
            passwordConfirmIcon.type = 'password';
            passwordConfirmIcon.classList.remove('bi-eye-slash');
            passwordConfirmIcon.classList.add('bi-eye');
        }
    });
</script>