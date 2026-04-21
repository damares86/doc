<?php
require "admin/template/inc/header.php";
?>
<div id="bottomContainer">
    <?php
    $file = basename($_SERVER['PHP_SELF']);
    $page_class = pathinfo($file, PATHINFO_FILENAME);

    $mc->page_name = $page_class;

    require "admin/template/page_recall.php";
    ?>
</div>
<?php

require "admin/template/inc/footer.php";
