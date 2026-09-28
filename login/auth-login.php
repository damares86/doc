<?php

declare(strict_types=1);

require_once __DIR__ . '/inc/header.php';

$plugin->pluginname = 'recaptcha';
$mng = 'mngAuth';
$recap = false;

if ($plugin->itemExists('pluginname') && (int) $plugin->isActive() === 1) {
    $mng = 'mngAuthRecap';
    $recap = true;
    require_once __DIR__ . '/../admin/inc/recaptcha.php';
}
?>

<h1 class="auth-title"><?= htmlspecialchars((string) ($login_title ?? 'Log in.'), ENT_QUOTES, 'UTF-8') ?></h1>

<p class="auth-subtitle mb-5">
  <?= htmlspecialchars((string) ($login_desc ?? 'Log in with your data that you entered during registration.'), ENT_QUOTES, 'UTF-8') ?>
</p>

<form action="../admin/core/<?= htmlspecialchars($mng, ENT_QUOTES, 'UTF-8') ?>.php" method="POST" data-parsley-validate>
  <?php if ($recap): ?>
    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
  <?php endif; ?>

  <div class="form-group position-relative has-icon-left mb-4">
    <input type="email" class="form-control form-control-xl" placeholder="Email" name="email" data-parsley-required="true" />
    <div class="form-control-icon">
      <i class="bi bi-envelope"></i>
    </div>
  </div>
  <div class="form-group position-relative has-icon-left mb-4">
    <input type="password" id="password" class="form-control form-control-xl" placeholder="Password" name="password" data-parsley-required="true" />
    <div class="form-control-icon">
      <i class="bi bi-shield-lock"></i>
    </div>
    <div class="toggle-password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
      <i class="bi bi-eye" id="togglePassword"></i>
    </div>
  </div>
  <div class="form-check form-check-lg d-flex align-items-end">
    <input class="form-check-input remember me-2" type="checkbox" value="remember_me" name="remember" id="flexCheckDefault" />
    <label class="form-check-label text-gray-600" for="flexCheckDefault">
      <?= htmlspecialchars((string) ($login_remember ?? 'Keep me logged in'), ENT_QUOTES, 'UTF-8') ?>
    </label>
  </div>
  <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
    <?= htmlspecialchars((string) ($login_button ?? 'Log in'), ENT_QUOTES, 'UTF-8') ?>
  </button>
</form>

<div class="text-center mt-3 text-lg fs-4">
  <?php if ($reg): ?>
    <p>
      <?= htmlspecialchars((string) ($login_reg ?? "Don't have an account?"), ENT_QUOTES, 'UTF-8') ?>
      <a href="auth-register.php" class="font-bold"><?= htmlspecialchars((string) ($login_signup ?? 'Sign up'), ENT_QUOTES, 'UTF-8') ?></a>.
    </p>
  <?php endif; ?>
  <p>
    <a class="font-bold" href="auth-forgot-password.php"><?= htmlspecialchars((string) ($login_forgot ?? 'Forgot password?'), ENT_QUOTES, 'UTF-8') ?></a>
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