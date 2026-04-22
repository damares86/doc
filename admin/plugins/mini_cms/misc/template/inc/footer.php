<div class="clearfix"></div>
</main>
<?php
if (!$one) {
?>
    <footer>


        <div class="container">
            <div class="row align-items-center justify-content-between flex-column flex-sm-row">
                <div class="col-12">
                    <div class="small m-0 text-center"><?= $mc_settings['mc_footer'] ?></div>
                </div>
                <div class="col-12 mt-3">
                    <p class="copyright" style="font-size:0.7em;">
                        <img class="align-middle" src="uploads/img/logo_mc_rid.png"> &nbsp; &nbsp; by &nbsp; &nbsp;
                        <a href="https://www.dmweblab.com"><img class="align-middle" src="admin/assets/images/logo/dmweblab_logo.png"></a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
<?php
}
?>


    <script src="admin/script/quotes.js"></script>

<script src="admin/assets/extensions/parsleyjs/parsley.min.js"></script>
<script src="admin/assets/js/pages/parsley.js"></script>
<script src="admin/assets/js/pages/<?= $lang ?>.js"></script>
<script src="admin/assets/js/pages/<?= $lang ?>.extra.js"></script>
<?php
require "assets/themes/" . $mc_settings['mc_theme'] . "/inc/cookie.php";

foreach (glob("admin/scripts/var/*.js") as $file) {
?>
    <script src="<?= $file ?>"></script>
<?php
}
require "assets/themes/" . $mc_settings['mc_theme'] . "/inc/footerScript.php";
?>


</body>

</html>