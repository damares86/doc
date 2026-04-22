<?php

require "admin/core/prefix.php";
require "admin/inc/mc_version.php";

session_start();
// loading class

if (!is_file('admin/class/Database.php')) {
    require "admin/inc/dbdata.php";
    exit;
}

spl_autoload_register('autoloader');
function autoloader($class)
{
    include("admin/class/$class.php");
}

/////////////////////////////////////////////////////////////////////
$prefix_table = "";
if (is_file("admin/core/prefix.php")) {
    include "admin/core/prefix.php";
    $prefix_table = $prefix;
}
/////////////////////////////////////////////////////////////////////

$database = new Database();
$db = $database->getConnection();

$files = glob("admin/class/*.php", GLOB_BRACE);
rsort($files);

if (!is_file('admin/inc/class_initialize.php')) {
    $file_handle = fopen('admin/inc/class_initialize.php', 'w');
    fwrite($file_handle, '<?php');
    fwrite($file_handle, "\n");
    foreach ($files as $filename) {
        $nomefile = pathinfo($filename);
        $filelabel = $nomefile['filename'];
        $file_var = strtolower($filelabel);
        fwrite($file_handle, '$' . $file_var . ' = new ' . $filelabel . '($db);');
        fwrite($file_handle, "\n");
        fwrite($file_handle, '$' . $file_var . '->prx = "' . $prefix_table . '";');
        fwrite($file_handle, "\n");
    }
    fwrite($file_handle, "?>");
    chmod('admin/inc/class_initialize.php', 0777);
}

include "admin/inc/class_initialize.php";

$setting->name = 'lang';
$stmt_lang = $setting->showAllWhere('id', ['name']);
$row_lang = $stmt_lang->fetch(PDO::FETCH_ASSOC);
extract($row_lang);
$lang = $row_lang['value'];

foreach (glob("admin/locale/$lang/*.php") as $filelang) {
    require "$filelang";
}

$setting->name = "debug";
$dbg = $setting->showAllWhere('id', ['name']);
$row_debug = $dbg->fetch(PDO::FETCH_ASSOC);
extract($row_debug);

if ($row_debug['value'] == 1) {
    require 'admin/vendor/autoload.php';        // If installed via composer
    $debug = new \bdk\Debug(array(
        'collect' => true,
        'output' => true,
    ));
}


// prendo il nome del file (con estensione)
$file_name = basename($_SERVER['PHP_SELF']);

$post_title = "";

$page_name = "";
$page_class = "";

if ($file_name == "login.php") {
    if (isset($_SESSION['loggedin'])) {
        header('Location: admin/');
        exit;
    }
}


if ($file_name == "index.php") {

    //force the page name in home page
    $page_name_title = "Home";
    $page_class = pathinfo($file_name, PATHINFO_FILENAME);
} else if ($file_name == "post.php") {

    // get the post data
    $post->table = 'post';
    $post->id = filter_input(INPUT_GET, "id");
    $post_title_stmt = $post->showAllWhere('id', ['id']);
    $post_title_row = $post_title_stmt->fetch(PDO::FETCH_ASSOC);
    extract($post_title_row);
    $post_title = $post_title_row['title'];

    $page_name_title = $post_title . " - Blog ";
    $page_class = "blog";
} else if($file_name=="stories.php" || "single_story.php"){
    $page_name_title = $stories_page_title ;
} else if ($file_name == "contact.php") {

    // force the page name in contacts
    $page_name_title = $cont_form_page;
    $page_class = "contact";
}  else if ($file_name == "blog.php") {
    if (filter_input(INPUT_GET, 'cat')) {
        $cat = filter_input(INPUT_GET, 'cat');
        if ($cat == 2 || $cat == 3) {
            $post->table = 'post_categories';
            $post->id = $cat;
            $title_stmt = $post->showAllWhere('id', ['id']);
            $title_row = $title_stmt->fetch(PDO::FETCH_ASSOC);
            extract($title_row);
            $page_name_title = ucfirst($title_row['category_name']);
        } else {
            $page_name_title = "Blog";
        }
    } else {
        $page_name_title = "Blog";
    }
    $page_class = "blog";
}else {
    // mi prendo solo il nome senza l'estensione
    $page_name_title = pathinfo($file_name, PATHINFO_FILENAME);
    $page_class = pathinfo($file_name, PATHINFO_FILENAME);
    // rimuovo gli _ (underscore) che ho messo nel nome file
    $page_name_title = str_replace("_", " ", $page_name_title);
    // metto la prima lettera maiuscola
    $page_name_title = ucfirst($page_name_title);
}

$mc->table = 'mc_pages';
$mc->page_name = $page_class;
$page_data = $mc->showAllWhere('id', ['page_name']);

while ($row = $page_data->fetch(PDO::FETCH_ASSOC)) {

    extract($row);

    $page_id = $row['id'];
    $page_layout = $row['layout'];
    $page_header = $row['header'];
    $page_header_media = $row['header_media'];
    $page_use_page_name = $row['use_page_name'];
    $page_use_site_name = $row['use_name'];
    $page_use_site_description = $row['use_desc'];
    $page_counter = $row['counter'];
}

$mc->table = 'mc_settings';
$stmt = $mc->showAll('id');

$mc_settings = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    extract($row);
    $mc_settings[$row['name']] = $row['value'];
}


$url_file = ($_SERVER['PHP_SELF']);
$root = $mc_settings['mc_site_url'];
$url = $root . $url_file;

$one = false;
if ($mc_settings['mc_theme_one'] == 1) {
    $one = true;
}
$quote_counter = 1;
?>
<!doctype html>
<html>

<head>
    <!--
    ==========================================================================
            
         Mini Cms is a project by DM WebLab (https://www.dmweblab.com)
            
    ==========================================================================
    -->
    <meta charset="utf-8">
    <meta name="author" content="dmweblab" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <meta name="description" content="Parrocchie San Lorenzo e San Giacomo di Giaveno e San Giovanni Battista di Valgioie. Sito ufficiale con informazioni sulle attività delle parrocchie.">
    <meta name="keywords" content="parrocchia san lorenzo giaveno, parrocchia san giacomo giaveno, parrocchia san giovanni battista valgioie, giaveno, san lorenzo, orari messe">
    <meta name="robots" content="index, follow">


    <!-- FACEBOOK and LINKEDIN meta tag -->
    <meta property="og:title" content="<?= $mc_settings['mc_site_name'] ?>">
    <meta property="og:description" content="<?= $mc_settings['mc_site_description'] ?>">
    <meta property="og:url" content="<?= $url ?>" />
    <meta property="og:image" content="uploads/img/<?= $page_header_media ?>">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="800">

    <!-- TWITTER meta tag -->
    <meta name="twitter:card" value="summary">
    <meta name="twitter:title" content="<?= $mc_settings['mc_site_name'] ?>">
    <meta name="twitter:description" content="<?= $mc_settings['mc_site_description'] ?>">
    <meta name="twitter:site" content="<?= $url ?>" />
    <meta name="twitter:image" content="uploads/img/<?= $page_header_media ?>">

    <title><?= $page_name_title ?> - <?= $mc_settings['mc_site_name'] ?></title>
    <link href='admin/script/simplelightbox/simple-lightbox.min.css' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="admin/script/simplelightbox/simple-lightbox.jquery.min.js"></script>

    <?php

    $plugin->pluginname = "post";
    if ($plugin->itemExists('pluginname') && $plugin->isActive() == 1) {
    ?>
        <link href='admin/assets/css/post.css' rel='stylesheet' type='text/css'>

    <?php
    }

    require "admin/template/check.php";

    $plugin->pluginname = "recaptcha";
    $recap = false;
    if ($plugin->itemExists('pluginname') && $plugin->isActive() == 1) {
        $recap = true;
    }

    if (($file_name == "login.php") || ($file_name == "contact.php") && $recap) {
        require "admin/template/inc/recaptcha.php";
    }
    ?>
    <link rel="stylesheet" href="admin/assets/css/carousel.css" />

    <?php
    require "assets/themes/" . $mc_settings['mc_theme'] . "/inc/scripts.php";
    foreach (glob("admin/template/inc/css/*.css") as $cssfile) {
    ?>
        <link href='<?= $cssfile ?>' rel='stylesheet' type='text/css'>
    <?php
    }

    ?>

    <link rel="stylesheet" href="assets/themes/<?= $mc_settings['mc_theme'] ?>/custom.css" />
</head>


<body class="d-flex flex-column h-100">
    <?php
    $style = "";
    if (isset($_SESSION['loggedin'])) {
        $style = "style='margin-top:1.8em'";
    }
    ?>
    <main class="flex-shrink-0" <?= $style ?>>

        <?php
        $mc->table = 'mc_popup';
        $mc->page_id = $page_id;
        $popup_data = $mc->showAllWhere('id', ['page_id']);

        if ($popup_data->rowCount() > 0) {
            $popup = $popup_data->fetch(PDO::FETCH_ASSOC);
            extract($popup);

            $mc->table = 'mc_popup_cat';
            $mc->id = $popup['popup_cat_id'];
            $popup_cat_stmt = $mc->showAllWhere('id', ['id']);
            $popup_cat_row = $popup_cat_stmt->fetch(PDO::FETCH_ASSOC);
            extract($popup_cat_row);
        ?>

            <script>
                $(document).ready(function() {
                    $("#myPopup").modal('show');
                });
            </script>

            <div class="modal fade <?= $popup_cat_row['category'] ?>" id="myPopup" tabindex="-1" aria-labelledby="popupTitle" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="popupTitle"><?= $popup['title'] ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                        </div>

                        <div class="modal-body">
                            <?= $popup['content'] ?>
                        </div>

                    </div>
                </div>
            </div>


        <?php
        }

        ?>
        <script type='text/javascript'>
            $(document).ready(function() {
                // Intialize gallery
                var gallery = $('.gallery a').simpleLightbox();
            });
        </script>
        <?php
        $style = "";
        if (isset($_SESSION['loggedin'])) {
        ?>
            <div id="adminBar">
                <a href="admin"><?= $fe_admin ?></a>
                &nbsp; - &nbsp;
                <a href="admin/core/logout.php"><?= $common_logout ?></a>
            </div>
        <?php
        }

        $mc->table = 'mc_quotes';
        if ($mc->countAll() > 0) {
            $mc->table = 'mc_quotes';
            $quote_stmt = $mc->showAll('id');

        ?>
            <div class="row quotes">
                <div class="col-12 pt-3 text-end">

                    <div class="slideshow-container header">
                        <?php
                        while ($quote_row = $quote_stmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($quote_row);
                        ?>
                            <div class="mySlides">
                                <q><?= $quote_row['quote'] ?></q>
                                <p class="author"><?= $quote_row['author'] ?></p>
                            </div>
                        <?php
                        }
                        ?>

                        <!-- <a class="prev" onclick="plusSlides(-1)">❮</a>
<a class="next" onclick="plusSlides(1)">❯</a> -->

                    </div>
                    
    <div class="dot-container">
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
    </div>
                </div>
            </div>
        <?php
        }
        ?>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand p_<?= $page_id ?>" href="index.php">
                    <img class="img-logo" src="uploads/img/<?= $mc_settings['mc_site_logo'] ?>">
                </a>

                <button class="navbar-toggler collapsed  ms-auto p_<?= $page_id ?>" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

                <div class="collapse navbar-collapse p_<?= $page_id ?>" id="navbarSupportedContent" style="text-align:right">
                    <?php
                    require "admin/template/inc/menu.php";
                    ?>
                </div>
            </div>
        </nav>

        <?php
        if ($page_header == 1) {
        ?>
            <?php
            if (pathinfo($page_header_media, PATHINFO_EXTENSION)) {

            ?>
                <!-- Header-->
                <header class="py-5 p_<?= $page_id ?>" style="background-image: url('uploads/img/<?= $page_header_media ?>');">
                    <div class="container px-5">
                        <div class="row gx-5 align-items-center justify-content-center">
                            <div class="col-12">
                                <div class="my-5 text-center">
                                    <?php
                                    if ($page_use_site_name == 1) {
                                    ?>
                                        <h1 class="display-5 fw-bolder mb-2"><?= $mc_settings['mc_site_name'] ?></h1>

                                    <?php
                                    }

                                    if ($page_use_site_description == 1) {
                                    ?>
                                        <p class="lead fw-normal mb-4"><?= $mc_settings['mc_site_description'] ?></p>
                                    <?php
                                    }

                                    if ($page_use_page_name == 1) {
                                    ?>
                                        <h1 class="display-5 fw-bolder mb-2"><?= $page_name_title ?></h1>

                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php
                } else {
                    ?>
                        <header class="bg-dark py-5">
                            <div class="container px-5">
                                <script>
                                    $('#myVisualCarousel').carousel({
                                        interval: 2000,
                                        cycle: true
                                    })
                                </script>

                                <div id="#myVisualCarousel" class="carousel slide" data-ride="carousel">
                                    <ol class="carousel-indicators">
                                        <?php

                                        $dirCarousel = "uploads/gallery/g_$page_header_media/";

                                        $idx = 0;
                                        foreach (glob($dirCarousel . "*") as $item) {

                                            $active = "";
                                            if ($idx == 0) {
                                                $active = "class=\"active\"";
                                            }

                                        ?>
                                            <li data-target="#myVisualCarousel" data-slide-to="<?= $idx ?>" <?= $class ?>></li>
                                        <?php

                                            $idx++;
                                        }
                                        ?>
                                    </ol>
                                    <div class="carousel-inner">

                                        <?php
                                        $idx = 0;
                                        foreach (glob($dirCarousel . "*") as $item) {
                                            $img = pathinfo($item, PATHINFO_FILENAME);
                                            $ext = pathinfo($item, PATHINFO_EXTENSION);
                                            $imgName = $img . "." . $ext;

                                            $active = "";
                                            if ($idx == 0) {
                                                $active = "active";
                                            }

                                            $numberArr = array('first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth');

                                            $number = $numberArr[$idx];

                                        ?>
                                            <div class="carousel-item <?= $active ?>">
                                                <img class="<?= $number ?>-slide" src="<?= $dirCarousel ?>/<?= $imgName ?>" alt="<?= $number ?> slide">
                                            </div>
                                        <?php
                                            $idx++;
                                        }
                                        ?>

                                    </div>

                                </div>
                            <?php
                            $idx = 0;
                        }
                            ?>
                            </div>
                        </header>
                    <?php
                }
                    ?>