<?php

declare(strict_types=1);

require_once __DIR__ . '/inc/header.php';

$plugin->pluginname = 'recaptcha';
$mng = 'mngPass';

if ($plugin->itemExists('pluginname') && (int) $plugin->isActive() === 1) {
    $mng = 'mngPassRecap';
    require_once __DIR__ . '/../admin/inc/recaptcha.php';
}
?>

<?php
if ($op === '') {
?>
    <h1 class="auth-title"><?= htmlspecialchars((string) ($forgot_title ?? 'Forgot Password'), ENT_QUOTES, 'UTF-8') ?></h1>
    <p class="auth-subtitle mb-5"><?= htmlspecialchars((string) ($forgot_desc ?? 'Input your email and we will send you reset password link.'), ENT_QUOTES, 'UTF-8') ?></p>

    <form action="../admin/core/<?= htmlspecialchars($mng, ENT_QUOTES, 'UTF-8') ?>.php" method="POST" data-parsley-validate>
        <div class="form-group position-relative has-icon-left mb-4">
            <div class="form-check mandatory">
                <input type="email" name="email" class="form-control form-control-xl" placeholder="Email" data-parsley-required="true">
            </div>
        </div>
        <input type="hidden" name="resetForm" value="resetForm" />
        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5"><?= htmlspecialchars((string) ($forgot_button ?? 'Send'), ENT_QUOTES, 'UTF-8') ?></button>
    </form>
    <div class="text-center mt-5 text-lg fs-4">
        <p class="text-gray-600"><a href="auth-login.php" class="font-bold">&larr; <?= htmlspecialchars((string) ($login_title ?? 'Log in'), ENT_QUOTES, 'UTF-8') ?></a></p>
    </div>
<?php
} elseif ($op === 'reset') {
    $email = (string) (filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL) ?? '');
    $account->email = $email;
    $token = (string) (filter_input(INPUT_GET, 'token', FILTER_DEFAULT) ?? '');
    $account->token = $token;
    $curDate = date('Y-m-d H:i:s');

    $pswTmp = $account->getPswTmpData();

    if (!$pswTmp || empty($pswTmp['token'])) {
?>
        <a href="auth-login.php">&larr; <?= htmlspecialchars((string) ($log_back ?? 'Back to login'), ENT_QUOTES, 'UTF-8') ?></a>
<?php
    } else {
        $expDate = $account->getExpDate();
        if ($expDate !== null && $expDate >= $curDate) {
?>
            <h1 class="auth-title"><?= htmlspecialchars((string) ($forgot_choose ?? 'Choose new password'), ENT_QUOTES, 'UTF-8') ?></h1>

            <form action="../admin/core/<?= htmlspecialchars($mng, ENT_QUOTES, 'UTF-8') ?>.php" method="POST" data-parsley-validate>
                <div class="form-group position-relative has-icon-left mb-4">
                    <div class="form-check mandatory">
                        <input
                            type="password"
                            class="form-control form-control-xl"
                            placeholder="Password"
                            name="password"
                            data-parsley-required="true"
                        />
                    </div>
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                <input type="hidden" name="resetMail" value="resetMail" />
                <input type="hidden" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" />

                <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5"><?= htmlspecialchars((string) ($forgot_button ?? 'Submit'), ENT_QUOTES, 'UTF-8') ?></button>
            </form>
<?php
        } else {
?>
            <div class="alert alert-danger">
                <?= htmlspecialchars((string) ($forgot_token ?? 'Token expired'), ENT_QUOTES, 'UTF-8') ?>
            </div>
            <a href="auth-login.php">&larr; <?= htmlspecialchars((string) ($log_back ?? 'Back to login'), ENT_QUOTES, 'UTF-8') ?></a>
<?php
        }
    }
}
?>
</div>
</div>
<div class="col-lg-7 d-none d-lg-block">
    <div id="auth-right">
        &nbsp;
    </div>
</div>
</div>
<?php
require_once __DIR__ . '/inc/footer.php';
?>
</div>
</body>
</html>