<?php

declare(strict_types=1);

require_once __DIR__ . '/inc/header.php';

$plugin->pluginname = 'recaptcha';
$mng = 'mngRegister';

if ($plugin->itemExists('pluginname') && (int) $plugin->isActive() === 1) {
    $mng = 'mngRegisterRecap';
    require_once __DIR__ . '/../admin/inc/recaptcha.php';
}
?>
<div class="login-back-button mb-3">
  <a href="auth-login.php">
    <i class="bi bi-arrow-left-short"></i>
    <?= htmlspecialchars((string) ($reg_account ?? 'Back to login'), ENT_QUOTES, 'UTF-8') ?>
  </a>
</div>
<?php
if ($op === '') {
?>
  <!-- Register Form -->
  <h6 class="mb-3 text-center"><?= htmlspecialchars((string) ($login_signup ?? 'Sign up'), ENT_QUOTES, 'UTF-8') ?></h6>

  <form action="../admin/core/<?= htmlspecialchars($mng, ENT_QUOTES, 'UTF-8') ?>.php" method="POST" data-parsley-validate>
    <?php
    require_once __DIR__ . '/../admin/core/accountDetails.php';

    if (isset($account_details) && is_array($account_details)) {
        foreach ($account_details as $item) {
            $item_label = ucfirst($item);
            $type = ($item === 'birth') ? 'date' : 'text';
            ?>
            <div class="form-group mb-3 position-relative has-icon-left">
              <label class="form-label"><?= htmlspecialchars($item_label, ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
              <div class="mandatory">
                <div class="position-relative">
                  <input
                    type="<?= $type ?>"
                    class="form-control"
                    placeholder="<?= htmlspecialchars($item_label, ENT_QUOTES, 'UTF-8') ?>"
                    name="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>"
                    data-parsley-required="true" />
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
            <div class="form-group mb-3">
              <label class="form-label">
                <?= htmlspecialchars($item_label, ENT_QUOTES, 'UTF-8') ?>
                <?= htmlspecialchars((string) ($account_add_optional ?? '(optional)'), ENT_QUOTES, 'UTF-8') ?>
              </label>
              <div class="position-relative">
                <input
                  type="<?= $type ?>"
                  class="form-control"
                  placeholder="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>"
                  name="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>" />
              </div>
            </div>
            <?php
        }
    }
    ?>

    <div class="form-group text-start mb-3">
      <label class="form-label">Username <span class="text-danger">*</span></label>
      <div class="mandatory">
        <input type="text" class="form-control" name="username" placeholder="Username" data-parsley-required="true">
      </div>
    </div>

    <div class="form-group text-start mb-3">
      <label class="form-label">Email <span class="text-danger">*</span></label>
      <div class="mandatory">
        <input class="form-control" name="email" type="email" placeholder="Email" data-parsley-required="true">
      </div>
    </div>

    <div class="form-group text-start mb-3 position-relative">
      <label class="form-label">Password <span class="text-danger">*</span></label>
      <div class="mandatory position-relative">
        <input type="password" id="password" class="form-control" placeholder="Password" name="password" data-parsley-required="true" />
        <div class="toggle-password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
          <i class="bi bi-eye" id="togglePassword"></i>
        </div>
      </div>
    </div>

    <div class="form-group text-start mb-3 position-relative">
      <label class="form-label"><?= htmlspecialchars((string) ($reg_conf_psw_ph ?? 'Confirm password'), ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
      <div class="position-relative">
        <input type="password" id="password_confirm" class="form-control" placeholder="Password" name="password_confirm" data-parsley-required="true" data-parsley-equalto="#password" />
      </div>
    </div>
    <input type="hidden" name="reg_form" value="1">

    <div class="form-check mb-3">
      <div class="mandatory">
        <input class="form-check-input" id="checkedCheckbox" type="checkbox" value="" data-parsley-required="true">
        <label class="form-check-label text-muted fw-normal" for="checkedCheckbox">
          <?= htmlspecialchars((string) ($reg_agree_1 ?? 'I agree to the'), ENT_QUOTES, 'UTF-8') ?>
          <a href="#"><?= htmlspecialchars((string) ($reg_agree_2 ?? 'terms'), ENT_QUOTES, 'UTF-8') ?></a>
          <?= htmlspecialchars((string) ($reg_agree_3 ?? 'and'), ENT_QUOTES, 'UTF-8') ?>
          <a href="#"><?= htmlspecialchars((string) ($reg_agree_4 ?? 'privacy policy'), ENT_QUOTES, 'UTF-8') ?></a>
        </label>
      </div>
    </div>
    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
    <button class="btn btn-primary w-100" type="submit"><?= htmlspecialchars((string) ($login_signup ?? 'Sign up'), ENT_QUOTES, 'UTF-8') ?></button>
  </form>
<?php
}
?>
<div class="text-center mt-5 text-lg fs-4">
  <p class="text-gray-600">
    <?= htmlspecialchars((string) ($reg_account ?? 'Already have an account?'), ENT_QUOTES, 'UTF-8') ?>
    <a href="auth-login.php" class="font-bold"><?= htmlspecialchars((string) ($reg_account_button ?? 'Log in'), ENT_QUOTES, 'UTF-8') ?></a>.
  </p>
</div>
</div>
</div>
<div class="col-lg-7 d-none d-lg-block">
  <div id="auth-right">
    &nbsp;
  </div>
</div>
</div>

</div>
<?php
require_once __DIR__ . '/inc/footer.php';
?>
</body>

</html>