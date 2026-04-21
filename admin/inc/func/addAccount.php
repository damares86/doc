<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3><?= $account_add_header ?></h3>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php"><?= $common_dashboard ?></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $account_add_header ?>
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
                    <h4 class="card-title"><?= $account_add_title ?></h4>
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
                                                    <input type="text" class="form-control" placeholder="Name" id="first-name-icon" name="username" data-parsley-required="true" />
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
                                                    <input type="email" class="form-control" placeholder="Email" id="first-name-icon" name="email" data-parsley-required="true" />
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-envelope"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                                                if ($row['id'] > 1) {
                                                            ?>

                                                                    <option value="<?= $row['id'] ?>"><?= $row['rolename'] ?></option>

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

                                    require "core/accountDetails.php";
                                    foreach ($account_details as $item) {

                                        $label = "account_add_$item";
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
                                                        <input type="<?= $type ?>" class="form-control" placeholder="<?= $item_label ?>" name="<?= $item ?>" data-parsley-required="true" />
                                                        <?php

                                                        ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php

                                    }

                                    foreach ($account_details_opt as $item) {

                                        $label = "account_add_$item";
                                        $item_label = ucfirst($item);

                                    ?>
                                        <div class="col-md-3">
                                            <label><?= $item ?> <?= $account_add_optional ?></label>
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
                                                    <input type="<?= $type ?>" class="form-control" placeholder="<?= $item_label ?>" name="<?= $item ?>" />

                                                </div>
                                            </div>
                                        </div>

                                    <?php

                                    }

                                    ?>

                                    <div class="col-md-3">
                                        <label><?= $account_add_avatar ?> <?= $account_add_optional ?></label>
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
                                            <?= $common_submit ?>
                                        </button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1 shadow">
                                            <?= $common_reset ?>
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