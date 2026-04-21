<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

require __DIR__ . "/coreConfig.php";

// check if there's a page to delete

if (filter_input(INPUT_GET, "idToDel")) {

    $idToDel = filter_input(INPUT_GET, "idToDel");

    $mc->table = 'mc_pages';
    $mc->id = $idToDel;
    $stmt = $mc->showAllWhere('id', ['id']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);

    // check if the page is used in the menu
    $pages_json = file_get_contents('../inc/menu/menu.json');
    $pages_data = json_decode($pages_json, true);

    $searchValue = $row['id'];

    foreach ($pages_data['inmenu'] as $menuItem) {

        if (isset($menuItem['id']) && $menuItem['id'] == $searchValue) {
            header("Location: ../index.php?p=allPages&err=pageInmenu");
            exit;
        }

        // Controlla se il valore si trova in "child"
        if (isset($menuItem['child']) && in_array($searchValue, $menuItem['child'])) {
            header("Location: ../index.php?p=allPages&err=pageInmenu");
            exit;
        }
    }

    $key = array_search($searchValue, $pages_data['nomenu']);

    // Se il valore "6" è stato trovato, lo rimuove dall'array "nomenu"
    if ($key !== false) {
        unset($pages_data['nomenu'][$key]);

        // Reindicizza l'array per evitare eventuali buchi negli indici
        $pages_data['nomenu'] = array_values($pages_data['nomenu']);
    }

    $newpages_data = json_encode($pages_data, JSON_PRETTY_PRINT);
    file_put_contents('../inc/menu/menu.json', $newpages_data);

    $page_name = $row['page_name'];

    $mc->table = 'mc_pages';
    $mc->id = $idToDel;

    if ($mc->delete('id')) {

        $err_file = 0;
        $err_file_msg = '';

        // delete the php page
        if (!unlink('../../' . $page_name . '.php')) {
            $err_file++;
        }

        // delete the json file
        if (!unlink('../inc/pages/' . $idToDel . '.json')) {
            $err_file++;
        }

        if ($err_file > 0) {
            $err_file_msg = '&err=pageFilesErr';
        }

        header("Location: ../index.php?p=allPages&msg=pageDelSucc$err_file_msg");
        exit;
    } else {
        header("Location: ../index.php?p=allPages&err=pageDelFail");
        exit;
    }
}

$operation = filter_input(INPUT_POST, "operation");

// check if there's an account to edit or add

if (filter_input(INPUT_POST, "idToMod")) {

    if ($operation == 'edit') {
        // replicare l'add con l'aggiunta dei dati
        if (filter_input(INPUT_POST, "idToMod") == 1) {
            $page_name = 'index';
        } else {

            $raw_name = filter_input(INPUT_POST, 'page_name');
            // Sanifica il nome: sostituisce caratteri non ammessi con '-'
            $safe_name = preg_replace('/[^a-zA-Z0-9_-]/', '-', $raw_name);
            // Rimuove trattini multipli consecutivi
            $safe_name = preg_replace('/-+/', '-', $safe_name);
            // Rimuove trattini iniziali/finali
            $safe_name = trim($safe_name, '-');

            $page_name = strtolower($safe_name);
            $page_name = str_replace(" ", "_", $page_name);
        }

        $link_to_file = filter_input(INPUT_POST, 'link_to_file_check') ? filter_input(INPUT_POST, 'link_to_file') : 'none';

        $mc->link_to_file = $link_to_file;

        $old_page_name = filter_input(INPUT_POST, 'old_page_name');

        $name_to_change = '';

        ($old_page_name == $page_name) ? $name_to_change = false : $name_to_change = true;

        $counter = filter_input(INPUT_POST, 'counter');

        // prepare data for the db query
        $mc->page_name = $page_name;

        $mc->layout = filter_input(INPUT_POST, 'layout') ? filter_input(INPUT_POST, 'layout') : 'default';

        $err_file = '';

        // check if use_header is checked
        if (filter_input(INPUT_POST, 'use_header')) {

            $mc->header = 1;

            // check the type of the header media
            if (filter_input(INPUT_POST, 'header') == 'image') {

                if ($_FILES['img_header']['size'] > 0) {
                    $file->filename = $_FILES['img_header']['name'];
                    $filename = $_FILES['img_header']['name'];

                    // if ($file->countFile() > 0) {
                    //     header("Location: ../index.php?p=allFiles&err=fileExists");
                    //     exit;
                    // }
                    // set data for file uploading
                    $file->inputFileName = $_FILES['img_header']['tmp_name'];
                    $file->label = $_FILES['img_header']['name'];
                    $file->path = "../../uploads/img/";
                    $file->origin = filter_input(INPUT_POST, "origin");

                    $file->operation = "add";
                    if ($file->uploadFile()) {
                        //success
                        $mc->header_media = $_FILES['img_header']['name'];
                    } else {
                        $mc->header_media = filter_input(INPUT_POST, 'old_header_img');
                        $err_file = "&err=headerImgFail";
                    }
                } else {
                    $mc->header_media = filter_input(INPUT_POST, 'old_header_img');
                }
            } else if (filter_input(INPUT_POST, 'header') == 'gallery') {

                $mc->header_media = filter_input(INPUT_POST, 'header_gallery');
            }
        } else {

            $mc->header = 0;
            $mc->header_media = 'visual.jpg';
        }

        $mc->use_page_name = filter_input(INPUT_POST, 'use_page_name') ? 1 : 0;
        $mc->use_name = filter_input(INPUT_POST, 'site_name') ? 1 : 0;
        $mc->use_desc = filter_input(INPUT_POST, 'site_description') ? 1 : 0;

        $mc->counter = $counter;

        $idToMod = filter_input(INPUT_POST, 'idToMod');
        $mc->id = $idToMod;

        $mc->table = 'mc_pages';

        if ($mc->update(['page_name', 'link_to_file', 'layout', 'header', 'header_media', 'use_page_name', 'use_name', 'use_desc', 'counter'], 'id')) {

            $arr0 = array(
                "name"    => $page_name,
                "link_to_file" => $link_to_file
            );

            for ($i = 1; $i <= $counter; $i++) {

                // get the type of the block
                $post_type = filter_input(INPUT_POST, 'block_' . $i . '_type');
                $post_type_arr = explode('_', $post_type);
                $type = $post_type_arr[0];

                $array_name = "arr$i";

                $colorBg = filter_input(INPUT_POST, 'bg_color_' . $i . '');
                $colorText = filter_input(INPUT_POST, 'text_color_' . $i . '');
                $bootstrap = filter_input(INPUT_POST, 'bootstrap_' . $i . '');

                if ($type == 'text') {

                    $editor = preg_replace('/^\s+/', '', filter_input(INPUT_POST, 'text_content_' . $i . ''));
                    $$array_name = array(
                        'block' . $i . '_type'  => 'text',
                        'block' . $i . ''       => $editor,
                        'block' . $i . '_bg'    => $colorBg,
                        'block' . $i . '_text'  => $colorText,
                        'block' . $i . '_bootstrap'  => $bootstrap
                    );
                } else if ($type == 'img') {

                    if ($_FILES['img_' . $i . '']['size'] > 0) {
                        $file->filename = $_FILES['img_' . $i . '']['name'];
                        $filename = $_FILES['img_' . $i . '']['name'];

                        // if ($file->countFile() > 0) {
                        //     header("Location: ../index.php?p=allFiles&err=fileExists");
                        //     exit;
                        // }
                        // set data for file uploading
                        $file->inputFileName = $_FILES['img_' . $i . '']['tmp_name'];
                        $file->label = $_FILES['img_' . $i . '']['name'];
                        $file->path = "../../uploads/img/";
                        $file->origin = filter_input(INPUT_POST, "origin");

                        $file->operation = "add";
                        if ($file->uploadFile()) {
                            //success
                            $img = $_FILES['img_' . $i . '']['name'];
                        } else {
                            $img = filter_input(INPUT_POST, 'old_img_' . $i);
                            $err_file = "&err=infoImgFail";
                        }
                    } else {
                        $img = filter_input(INPUT_POST, 'old_img_' . $i);
                    }

                    $$array_name = array(
                        'block' . $i . '_type'  => 'img',
                        'block' . $i . ''       => $img,
                        'block' . $i . '_bg'    => $colorBg,
                        'block' . $i . '_text'  => $colorText,
                        'block' . $i . '_bootstrap'  => $bootstrap
                    );
                } else if ($type == 'info') {

                    if ($_FILES['info_img_' . $i . '']['size'] > 0) {
                        $file->filename = $_FILES['info_img_' . $i . '']['name'];
                        $filename = $_FILES['info_img_' . $i . '']['name'];

                        // if ($file->countFile() > 0) {
                        //     header("Location: ../index.php?p=allFiles&err=fileExists");
                        //     exit;
                        // }
                        // set data for file uploading
                        $file->inputFileName = $_FILES['info_img_' . $i . '']['tmp_name'];
                        $file->label = $_FILES['info_img_' . $i . '']['name'];
                        $file->path = "../../uploads/img/";
                        $file->origin = filter_input(INPUT_POST, "origin");

                        $file->operation = "add";
                        if ($file->uploadFile()) {
                            //success
                            $img_info = $_FILES['info_img_' . $i . '']['name'];
                        } else {
                            $img_info = filter_input(INPUT_POST, 'old_info_img_' . $i);
                            $err_file = "&err=infoImgFail";
                        }
                    } else {
                        $img_info = filter_input(INPUT_POST, 'old_info_img_' . $i);
                    }

                    $$array_name = array(
                        'block' . $i . '_type'  => 'info',
                        'block' . $i . '_info'  => $img_info,
                        'block' . $i . '_desc'  => filter_input(INPUT_POST, 'info_content_' . $i . ''),
                        'block' . $i . '_bg'    => $colorBg,
                        'block' . $i . '_text'  => $colorText,
                        'block' . $i . '_bootstrap'  => $bootstrap
                    );
                } else if ($type == 'gallery') {

                    $$array_name = array(
                        'block' . $i . '_type'  => 'gallery',
                        'block' . $i . ''       => filter_input(INPUT_POST, 'gallery_name_' . $i . ''),
                        'block' . $i . '_bg'    => $colorBg,
                        'block' . $i . '_text'  => $colorText,
                        'block' . $i . '_bootstrap'  => $bootstrap
                    );
                } else if ($type == 'quote') {

                    $$array_name = array(
                        'block' . $i . '_type'  => 'quote',
                        'block' . $i . '_bg'    => $colorBg,
                        'block' . $i . '_text'  => $colorText,
                        'block' . $i . '_bootstrap'  => $bootstrap
                    );
                } else if ($type == 'script') {

                    $$array_name = array(
                        'block' . $i . '_type'  => 'script',
                        'block' . $i . '_file'  => filter_input(INPUT_POST, 'script_' . $i . ''),
                        'block' . $i . '_bg'    => $colorBg,
                        'block' . $i . '_text'  => $colorText,
                        'block' . $i . '_bootstrap'  => $bootstrap
                    );
                } else if ($type == 'post') {

                    $$array_name = array(
                        'block' . $i . '_type'  => 'post',
                        'block' . $i . '_cat'  => filter_input(INPUT_POST, 'post_cat_' . $i . ''),
                        'block' . $i . '_bg'    => $colorBg,
                        'block' . $i . '_text'  => $colorText,
                        'block' . $i . '_bootstrap'  => $bootstrap
                    );
                }
            }

            $arr_tot = array($arr0);

            for ($i = 1; $i <= $counter; $i++) {
                $array_name = "arr$i";
                $arr_tot[] = $$array_name;
            }

            $target_directory = '../inc/pages/';
            // if(!file_exists( $target_directory ) || !is_dir( $target_directory)){
            //     mkdir($target_directory) ;
            //     $oldmask = umask(0);
            //     chmod($target_directory, 0777);
            //     umask($oldmask);
            // }

            // if ($name_to_change) {
            //     $filename = $page_name;
            // } else {
            //     $filename = $old_page_name;
            // }

            $json_file = $target_directory . $idToMod . '.json';
            $json = json_encode($arr_tot);

            if (!file_put_contents($json_file, $json)) {
                header("Location: ../index.php?p=allPages&err=pageCustomModFileErr");
                exit;
            } else {
                chmod($json_file, 0777);
                if ($name_to_change) {
                    unlink($target_directory . $old_page_name . '.json');
                }
            }



            if ($name_to_change) {
                rename('../../' . $old_page_name . '.php', '../../' . $page_name . '.php');
                chmod('../../' . $page_name . '.php', 0777);
            }

            header("Location: ../index.php?p=allPages&msg=pageCustomEditFile");
            exit;
        } else {
            header("Location: ../index.php?p=allPages&err=pageCustomDbErr");
            exit;
        }
    } else if ($operation == 'editDefault') {

        // check if use_header is checked
        if (filter_input(INPUT_POST, 'use_header')) {

            $mc->header = 1;

            // check the type of the header media
            if (filter_input(INPUT_POST, 'header') == 'image') {

                if ($_FILES['img_header']['size'] > 0) {
                    $file->filename = $_FILES['img_header']['name'];
                    $filename = $_FILES['img_header']['name'];

                    // if ($file->countFile() > 0) {
                    //     header("Location: ../index.php?p=allFiles&err=fileExists");
                    //     exit;
                    // }
                    // set data for file uploading
                    $file->inputFileName = $_FILES['img_header']['tmp_name'];
                    $file->label = $_FILES['img_header']['name'];
                    $file->path = "../../uploads/img/";
                    $file->origin = filter_input(INPUT_POST, "origin");

                    $file->operation = "add";
                    if ($file->uploadFile()) {
                        //success
                        $mc->header_media = $_FILES['img_header']['name'];
                    } else {
                        $mc->header_media = filter_input(INPUT_POST, 'old_header_img');
                        $err_file = "&err=headerImgFail";
                    }
                } else {
                    $mc->header_media = filter_input(INPUT_POST, 'old_header_img');
                }
            } else if (filter_input(INPUT_POST, 'header') == 'gallery') {

                $mc->header_media = filter_input(INPUT_POST, 'header_gallery');
            }
        } else {

            $mc->header = 0;
            $mc->header_media = 'visual.jpg';
        }

        $mc->use_page_name = filter_input(INPUT_POST, 'use_page_name') ? 1 : 0;
        $mc->use_name = filter_input(INPUT_POST, 'site_name') ? 1 : 0;
        $mc->use_desc = filter_input(INPUT_POST, 'site_description') ? 1 : 0;

        $mc->id = filter_input(INPUT_POST, 'idToMod');

        $mc->table = 'mc_pages';

        if ($mc->update(['header', 'header_media', 'use_page_name', 'use_name', 'use_desc',], 'id')) {

            if (filter_input(INPUT_POST, 'idToMod') == 2) {

                $arr_tot = [];

                $arr0 = array('name' => 'contact');
                $arr1 = array(
                    'block1_type' => 'text',
                    'block1' => filter_input(INPUT_POST, 'contacts'),
                    'block1_bg' => 'none',
                    'block1_text' => 'none'
                );
                $arr2 = array(
                    'block2_type' => 'text',
                    'block2' => filter_input(INPUT_POST, 'maps'),
                    'block2_bg' => 'none',
                    'block2_text' => 'none'
                );

                $arr_tot[] = $arr0;
                $arr_tot[] = $arr1;
                $arr_tot[] = $arr2;

                $json_file = '../inc/pages/2.json';
                $json = json_encode($arr_tot);

                $err_file = '';
                if (!file_put_contents($json_file, $json)) {
                    $err_file = "&err=jsonErr";
                } else {
                    chmod($json_file, 0777);
                }
            }

            header("Location: ../index.php?p=allDefaultPages&msg=pageDefaultEditSucc$err_file");
            exit;
        } else {
            header("Location: ../index.php?p=allDefaultPages&err=pageDefaultEditFail");
            exit;
        }
    }
} else if ($operation == "add") {
    $link_to_file = filter_input(INPUT_POST, 'link_to_file') ? filter_input(INPUT_POST, 'link_to_file') : 'none';

    $raw_name = filter_input(INPUT_POST, 'page_name');
    // Sanifica il nome: sostituisce caratteri non ammessi con '-'
    $safe_name = preg_replace('/[^a-zA-Z0-9_-]/', '-', $raw_name);
    // Rimuove trattini multipli consecutivi
    $safe_name = preg_replace('/-+/', '-', $safe_name);
    // Rimuove trattini iniziali/finali
    $safe_name = trim($safe_name, '-');

    $page_name = strtolower($safe_name);
    $page_name = str_replace(" ", "_", $page_name);

    $link_to_file = filter_input(INPUT_POST, 'link_to_file') ? filter_input(INPUT_POST, 'link_to_file') : 'none';
    $mc->link_to_file = $link_to_file;

    // prepare data for the db query
    $mc->page_name = $page_name;

    $counter = filter_input(INPUT_POST, 'counter');
    $mc->counter = $counter;

    $mc->layout = filter_input(INPUT_POST, 'layout') ? filter_input(INPUT_POST, 'layout') : 'default';

    $err_file = '';

    // check if use_header is checked
    if (filter_input(INPUT_POST, 'use_header')) {

        $mc->header = 1;

        // check the type of the header media
        if (filter_input(INPUT_POST, 'header') == 'image') {

            if ($_FILES['img_header']['size'] > 0) {
                $file->filename = $_FILES['img_header']['name'];
                $filename = $_FILES['img_header']['name'];

                // if ($file->countFile() > 0) {
                //     header("Location: ../index.php?p=allFiles&err=fileExists");
                //     exit;
                // }
                // set data for file uploading
                $file->inputFileName = $_FILES['img_header']['tmp_name'];
                $file->label = $_FILES['img_header']['name'];
                $file->path = "../../uploads/img/";
                $file->origin = filter_input(INPUT_POST, "origin");

                $file->operation = "add";
                if ($file->uploadFile()) {
                    //success
                    $mc->header_media = $_FILES['img_header']['name'];
                } else {
                    $mc->header_media = filter_input(INPUT_POST, 'visual.jpg');
                    $err_file = "&err=headerImgFail";
                }
            } else {
                $mc->header_media = 'visual.jpg';
            }
        } else if (filter_input(INPUT_POST, 'header') == 'gallery') {

            $mc->header_media = filter_input(INPUT_POST, 'header_gallery');
        }
    } else {

        $mc->header = 0;
        $mc->header_media = 'visual.jpg';
    }

    $mc->use_page_name = filter_input(INPUT_POST, 'use_page_name') ? 1 : 0;
    $mc->use_name = filter_input(INPUT_POST, 'site_name') ? 1 : 0;
    $mc->use_desc = filter_input(INPUT_POST, 'site_description') ? 1 : 0;


    $mc->table = 'mc_pages';

    if ($mc->insert(['page_name', 'link_to_file', 'layout', 'header', 'header_media', 'use_page_name', 'use_name', 'use_desc', 'counter'])) {

        $arr0 = array(
            "name"    => $page_name,
            "link_to_file" => $link_to_file
        );

        for ($i = 1; $i <= $counter; $i++) {

            // get the type of the block
            $post_type = filter_input(INPUT_POST, 'block_' . $i . '_type');
            $post_type_arr = explode('_', $post_type);
            $type = $post_type_arr[0];

            $array_name = "arr$i";

            $colorBg = filter_input(INPUT_POST, 'bg_color_' . $i . '');
            $colorText = filter_input(INPUT_POST, 'text_color_' . $i . '');
            $bootstrap = filter_input(INPUT_POST, 'bootstrap_' . $i . '');

            if ($type == 'text') {

                $editor = preg_replace('/^\s+/', '', filter_input(INPUT_POST, 'text_content_' . $i . ''));
                $$array_name = array(
                    'block' . $i . '_type'  => 'text',
                    'block' . $i . ''       => $editor,
                    'block' . $i . '_bg'    => $colorBg,
                    'block' . $i . '_text'  => $colorText,
                    'block' . $i . '_bootstrap'  => $bootstrap
                );
            } else if ($type == 'img') {

                if ($_FILES['img_' . $i . '']['size'] > 0) {
                    $file->filename = $_FILES['img_' . $i . '']['name'];
                    $filename = $_FILES['img_' . $i . '']['name'];

                    // if ($file->countFile() > 0) {
                    //     header("Location: ../index.php?p=allFiles&err=fileExists");
                    //     exit;
                    // }
                    // set data for file uploading
                    $file->inputFileName = $_FILES['img_' . $i . '']['tmp_name'];
                    $file->label = $_FILES['img_' . $i . '']['name'];
                    $file->path = "../../uploads/img/";
                    $file->origin = filter_input(INPUT_POST, "origin");

                    $file->operation = "add";
                    if ($file->uploadFile()) {
                        //success
                        $img = $_FILES['img_' . $i . '']['name'];
                    } else {
                        $img = filter_input(INPUT_POST, 'visual.jpg');
                        $err_file = "&err=infoImgFail";
                    }
                } else {
                    $img = filter_input(INPUT_POST, 'visual.jpg');
                }

                $$array_name = array(
                    'block' . $i . '_type'  => 'img',
                    'block' . $i . ''       => $img,
                    'block' . $i . '_bg'    => $colorBg,
                    'block' . $i . '_text'  => $colorText,
                    'block' . $i . '_bootstrap'  => $bootstrap
                );
            } else if ($type == 'info') {

                if ($_FILES['info_img_' . $i . '']['size'] > 0) {
                    $file->filename = $_FILES['info_img_' . $i . '']['name'];
                    $filename = $_FILES['info_img_' . $i . '']['name'];

                    // if ($file->countFile() > 0) {
                    //     header("Location: ../index.php?p=allFiles&err=fileExists");
                    //     exit;
                    // }
                    // set data for file uploading
                    $file->inputFileName = $_FILES['info_img_' . $i . '']['tmp_name'];
                    $file->label = $_FILES['info_img_' . $i . '']['name'];
                    $file->path = "../../uploads/img/";
                    $file->origin = filter_input(INPUT_POST, "origin");

                    $file->operation = "add";
                    if ($file->uploadFile()) {
                        //success
                        $img_info = $_FILES['info_img_' . $i . '']['name'];
                    } else {
                        $img_info = filter_input(INPUT_POST, 'visual.jpg');
                        $err_file = "&err=infoImgFail";
                    }
                } else {
                    $img_info = filter_input(INPUT_POST, 'visual.jpg');
                }

                $$array_name = array(
                    'block' . $i . '_type'  => 'info',
                    'block' . $i . '_info'  => $img_info,
                    'block' . $i . '_desc'  => filter_input(INPUT_POST, 'info_content_' . $i . ''),
                    'block' . $i . '_bg'    => $colorBg,
                    'block' . $i . '_text'  => $colorText,
                    'block' . $i . '_bootstrap'  => $bootstrap
                );
            } else if ($type == 'gallery') {

                $$array_name = array(
                    'block' . $i . '_type'  => 'gallery',
                    'block' . $i . ''       => filter_input(INPUT_POST, 'gallery_' . $i . ''),
                    'block' . $i . '_bg'    => $colorBg,
                    'block' . $i . '_text'  => $colorText,
                    'block' . $i . '_bootstrap'  => $bootstrap
                );
            } else if ($type == 'quote') {

                $$array_name = array(
                    'block' . $i . '_type'  => 'quote',
                    'block' . $i . '_bg'    => $colorBg,
                    'block' . $i . '_text'  => $colorText,
                    'block' . $i . '_bootstrap'  => $bootstrap
                );
            } else if ($type == 'script') {

                $$array_name = array(
                    'block' . $i . '_type'  => 'script',
                    'block' . $i . '_file'  => filter_input(INPUT_POST, 'script_' . $i . ''),
                    'block' . $i . '_bg'    => $colorBg,
                    'block' . $i . '_text'  => $colorText,
                    'block' . $i . '_bootstrap'  => $bootstrap
                );
            } else if ($type == 'post') {

                $$array_name = array(
                    'block' . $i . '_type'  => 'post',
                    'block' . $i . '_cat'  => filter_input(INPUT_POST, 'post_cat_' . $i . ''),
                    'block' . $i . '_bg'    => $colorBg,
                    'block' . $i . '_text'  => $colorText,
                    'block' . $i . '_bootstrap'  => $bootstrap
                );
            }
        }

        $arr_tot = array($arr0);

        for ($i = 1; $i <= $counter; $i++) {
            $array_name = "arr$i";
            $arr_tot[] = $$array_name;
        }

        $target_directory = '../inc/pages/';
        if (!file_exists($target_directory) || !is_dir($target_directory)) {
            mkdir($target_directory);
            $oldmask = umask(0);
            chmod($target_directory, 0777);
            umask($oldmask);
        }

        $mc->table = "mc_pages";
        $stmt = $mc->showAllLimitDesc('id', 1);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);


        // add to nomenu
        $pages_json = file_get_contents('../inc/menu/menu.json');
        $pages_data = json_decode($pages_json, true);

        $pages_data['nomenu'][] = "" . $row['id'] . "";

        $newpages_data = json_encode($pages_data, JSON_PRETTY_PRINT);
        file_put_contents('../inc/menu/menu.json', $newpages_data);


        // create json file for blocks
        $json_file = $target_directory . $row['id'] . '.json';
        $json = json_encode($arr_tot);

        file_put_contents($json_file, $json);
        chmod($json_file, 0777);

        if (copy('../template/master.php', '../../master.php')) {
            rename('../../master.php', '../../' . $page_name . '.php');
            chmod('../../' . $page_name . '.php', 0777);

            ////////////////////////
            // cancella tutto ???
            ////////////////////////

            header("Location: ../index.php?p=allPages&msg=pageCustomSucc");
            exit;
        } else {
            header("Location: ../index.php?p=allPages&err=pageCustomFileErr");
            exit;
        }
    } else {
        header("Location: ../index.php?p=allPages&err=pageCustomDbErr");
        exit;
    }
} else {
    header("Location: ../index.php?p=allPages&err=noPost");
    exit;
}
