<?php

require "inc/header.php";

$plugin->pluginname = "recaptcha";
$mng = "mngRegister";

if ($plugin->itemExists('pluginname') && $plugin->isActive() == 1) {
  $mng = "mngRegisterRecap";
  require "../admin/inc/recaptcha.php";
}

?>
<div class="login-back-button mb-3">
  <a href="auth-login.php">
    <i class="bi bi-arrow-left-short"></i>
    <?=$reg_account?>
  </a>
</div>
<?php
if ($op == "") {
?>
  <!-- Register Form -->
  <h6 class="mb-3 text-center"><?= $login_signup ?></h6>

  <form action="../admin/core/<?= $mng ?>.php" method="POST" data-parsley-validate>

    <?php

    require "../admin/core/accountDetails.php";

    $counter = 0;
    foreach ($account_details as $item) {

      $label = "account_add_$item";

      $item_label = ucfirst($item);

    ?>

      <div class="form-group mb-3 position-relative has-icon-left ">
        <label class="form-label"><?= $item_label ?> <span class="text-danger">*</span></label>
        <div class="mandatory">
          <div class="position-relative">
            <?php

            $type = "text";
            if ($item == "birth") {
              $type = "date";
            }
            ?>
            <input
              type="<?= $type ?>"
              class="form-control"
              placeholder="<?= $item_label ?>"
              name="<?= $item ?>"
              data-parsley-required="true" />


          </div>
        </div>
      </div>

    <?php
      $counter++;
    }

    $counter = 0;
    foreach ($account_details_opt as $item) {

      $label = "account_add_$item";
      $item_label = ucfirst($item);
    ?>
      <div class="form-group mb-3">
        <label class="form-label"><?= $item_label ?> <?= $account_add_optional ?></label>
        <div class="position-relative">
          <?php
          $type = "text";
          if ($item == "birth") {
            $type = "date";
          }
          ?>
          <input
            type="<?= $type ?>"
            class="form-control"
            placeholder="<?= $item ?>"
            name="<?= $item ?>" />

        </div>
      </div>


    <?php
      $counter++;
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
      <div class="mandatory">
        <input type="password" id="password" class="form-control" placeholder="Password" name="password" data-parsley-required="true" />
        <div class="toggle-password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
          <i class="bi bi-eye" id="togglePassword"></i>
        </div>

      </div>
    </div>

    <div class="form-group text-start mb-3 position-relative">
      <label class="form-label"><?= $reg_conf_psw_ph ?> <span class="text-danger">*</span></label>
      <input type="password" id="password_confirm" class="form-control" placeholder="Password" name="password_confirm" data-parsley-required="true" data-parsley-equalto="#password" />
      <div class="toggle-password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
        <i class="bi bi-eye" id="togglePassword"></i>
      </div>
    </div>
    <input type="hidden" name="reg_form" value="1">

    <div class="form-check mb-3">
      <div class="mandatory">
        <input class="form-check-input" id="checkedCheckbox" type="checkbox" value="" data-parsley-required="true">
        <label class="form-check-label text-muted fw-normal" for="checkedCheckbox"><?= $reg_agree_1 ?>
          <a href="#"><?= $reg_agree_2 ?></a><?= $reg_agree_3 ?>
          <a href="#"><?= $reg_agree_4 ?></a></label>
      </div>
    </div>
    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
    <button class="btn btn-primary w-100" type="submit"><?= $login_signup ?></button>
  </form>
<?php
}
?>
<div class="text-center mt-5 text-lg fs-4">
  <p class='text-gray-600'><?= $reg_account ?> <a href="auth-login.php" class="font-bold"><?= $reg_account_button ?></a>.</p>
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
require "inc/footer.php";
?>
</body>

</html>