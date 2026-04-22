<?php
require "admin/template/inc/header.php";
?>
<div id="bottomContainer">
    <?php
if(!$one){
    $file = basename($_SERVER['PHP_SELF']);
    $page_class = pathinfo($file, PATHINFO_FILENAME);

    $mc->page_name = $page_class;

    require "admin/template/page_recall.php";
}else{
    
    foreach($page_order as $page_req){
        if($page_req!=3){
            
        //   if($page_req==1){
        //         require "admin/template/page_recall.php";
        //     }else{
                ?>
        <div id="<?=$page_req?>">
            <?php
             if($page_req==2){
                require "admin/template/contact_form.php";
            }else{
                require "admin/template/page_recall.php";
            }
            ?>
        </div>
        <?php
            }
        }
    }
// }
    ?>
</div>
<?php

require "admin/template/inc/footer.php";
