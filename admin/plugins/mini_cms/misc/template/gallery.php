<?php
require "../../admin/template/inc/header_gallery.php";

$file = basename($_SERVER['PHP_SELF']);
$page_class = pathinfo($file, PATHINFO_FILENAME);
$gallery = $page_class;

// Target directory
$dir = "img/$gallery/";


?>
<div id="bottomContainer">
    <div id="content">
        <?php
        if (isset($_SESSION['loggedin'])) {
            $type = "";
            if ($page->id < 8) {
                $type = "default";
            } else {
                $type = "custom";
            }
        ?>
            <div class="text-right">

                <a href="admin/index.php?man=gall&op=edit&name=<?= $gallery ?>" class="btn btn-primary btn-sm"><b><?= $gall_site_edit ?></b></a>
            </div>
        <?php
        }
        ?>
        <div class='container'>
            <div class="row">
                <div class="col-12 pt-5 text-center">
                    <h2><?= $page_name ?></h2>
                </div>
            </div>
            <div class="gallery">
                <div class="row p-2">
                    <?php

                    // Image extensions
                    $image_extensions = array("png", "jpg", "jpeg", "JPG");


                    if (is_dir($dir)) {

                        if ($dh = opendir($dir)) {
                            $count = 1;

                            // Read files
                            while (($file = readdir($dh)) !== false) {

                                if ($file != '' && $file != '.' && $file != '..') {

                                    // Thumbnail image path
                                    $thumbnail_path = $dir . $file;

                                    // Image path
                                    $image_path = $dir . $file;

                                    $thumbnail_ext = pathinfo($thumbnail_path, PATHINFO_EXTENSION);
                                    $image_ext = pathinfo($image_path, PATHINFO_EXTENSION);

                                    // Check its not folder and it is image file
                                    if (
                                        !is_dir($image_path) &&
                                        in_array($thumbnail_ext, $image_extensions) &&
                                        in_array($image_ext, $image_extensions)
                                    ) {
                    ?>

                                        <!-- Image -->
                                        <div class="col-md-4 col-lg-3">
                                            <a href="<?php echo $image_path; ?>">
                                                <img src="<?php echo $thumbnail_path; ?>" alt="" title="" class="gallery">
                                            </a>
                                        </div>
                                        <!-- --- -->
                                        <?php

                                        // Break
                                        if ($count % 4 == 0) {
                                        ?>
                                            <div class="clear"></div>
                    <?php
                                        }
                                        $count++;
                                    }
                                }
                            }
                            closedir($dh);
                        }
                    }
                    ?>
                </div>
                <!-- Script -->
                <script type='text/javascript'>
                    $(document).ready(function() {

                        // Intialize gallery
                        var gallery = $('.gallery a').simpleLightbox();

                    });
                </script>




            </div>
        </div>

        <div class="clearfix"></div>
    </div>
</div>

<?php
require "../../admin/template/inc/footer.php";
?>