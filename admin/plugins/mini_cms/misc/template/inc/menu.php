<ul class="navbar-nav ms-auto mb-2 mb-lg-0 flex-shrink-0">
    <?php
    $page_order = [];
    $link = "";
    $link_child = "";

    $plugin->pluginname = "post";

    $post_check = false;

    if ($plugin->itemExists('pluginname') && $plugin->isActive() == 1) {

        $post_check = true;

        $post->table = 'post_categories';
        $cat_stmt = $post->showAll('id');
        $cat_pages = [];
        while ($cat_row = $cat_stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($cat_row);
            if ($cat_row['assign_page'] != "none") {
                $cat_pages[] = array($cat_row['assign_page'] => $cat_row['id']);
            }
        }
    }

    $plugin->pluginname = "luna_portal";

    $luna_check = false;

    if ($plugin->itemExists('pluginname') && $plugin->isActive() == 1) {

        $luna_check = true;

        $luna->table = 'luna_products';
        $luna_stmt = $luna->showAll('id');
        $luna_pages = [];
        while ($luna_row = $luna_stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($luna_row);
            if ($luna_row['assign_page'] != NULL) {
                $luna_pages[] = array($luna_row['assign_page'] => $luna_row['id']);
            }
        }
    }

    if ($page_class == "login" && $one) {

    ?>

        <li><a href="index.php#index" <?= $class ?>><- <?= $login_back_home ?> </a></li>

        <?php
    } else {

        $pages_json = file_get_contents('admin/inc/menu/menu.json');
        $pages_data = json_decode($pages_json, true);

        // Iteriamo sui parent
        foreach ($pages_data['inmenu'] as $parent) {

            $child_exists = false;
            if (array_key_exists('child', $parent)) {
                $child_exists = true;
            }

            // get page data
            $mc->table = 'mc_pages';
            $mc->id = $parent['id'];
            $stmt = $mc->showAllWhere('id', ['id']);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);
            if($row['page_name'] != 'blog'){
                // insert the page in the array for order
                $page_order[] = $row['id'];
            }

            // modify the string name
            $page_name = str_replace('_', ' ', ucfirst($row['page_name']));

            $link = $child_exists ? '#' : $row['page_name'] . ".php";

            if ($row['link_to_file'] != 'none') {
                $page_content_json = file_get_contents('admin/inc/pages/' . $parent['id'] . '.json');
                $page_content_data = json_decode($page_content_json, true);
                $link = $page_content_data[0]['link_to_file'];
            }

            // $link = $row['page_name'] . ".php";
            if ($post_check) {
                $found_value = NULL;
                foreach ($cat_pages as $page) {
                    if (array_key_exists($row['id'], $page)) {
                        $found_value = $page[$row['id']];
                        break; // Esci dal ciclo una volta trovato
                    }
                }
                if ($found_value) {
                    $link = 'blog.php?cat=' . $found_value;
                }
            }

            if ($luna_check) {
                $found_value = NULL;
                foreach ($luna_pages as $page) {
                    if (array_key_exists($row['id'], $page)) {
                        $found_value = $page[$row['id']];
                        break; // Esci dal ciclo una volta trovato
                    }
                }
                if ($found_value) {
                    $link = 'portal/manual.php?prod=' . $found_value;
                }
            }

            $scrolly = "";
            if ($one){
                if($row['page_name'] == "blog"){
                    $link = $row['page_name'] . ".php";
                }else if($row['page_name'] != "login") {
                    $link = "#" . $row['id'];
                    $scrolly = " scrolly";
                }
            }   

            $dropdown = $child_exists ? ' dropdown' : '';
        ?>

            <li class="nav-item<?= $dropdown ?>">
                <?php
                if ($child_exists) {
                ?>
                    <a class="nav-link dropdown-toggle" id="navbarDropdown<?= $row['id'] ?>" href="<?= $link ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                    <?php
                } else {
                    ?>
                        <a href="<?= $link ?>" class="nav-link<?= $scrolly ?>">

                        <?php

                    }
                        ?>


                        <?php
                        if ($row['id'] == 1) {
                            echo "Home";
                        } else if ($row['id'] == 2) {
                            echo $cont_form_page;
                        } else if ($page_name == "Post" || $page_name == "Blog") {
                            echo "News";
                        } else if ($page_name == "Stories") {
                            echo $stories_page_title;
                        } else {
                            echo $page_name;
                        } ?>
                        </a>
                        <?php
                        if ($child_exists) {
                        ?>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown<?= $row['id'] ?>">

                                <?php
                                foreach ($parent['child'] as $child) {
                                    $mc->table = 'mc_pages';
                                    $mc->id = $child;

                                    $stmt1 = $mc->showAllWhere('id', ['id']);
                                    $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                                    extract($row1);
                                    $page_order[] = $row1['id'];
                                    $child_name = str_replace('_', ' ', ucfirst($row1['page_name']));
                                    $link_child = $row1['page_name'] . ".php";
                                    if ($post_check) {
                                        $found_value = NULL;
                                        foreach ($cat_pages as $page) {
                                            if (array_key_exists($row1['id'], $page)) {
                                                $found_value = $page[$row1['id']];
                                                break; // Esci dal ciclo una volta trovato
                                            }
                                        }
                                        if ($found_value) {
                                            $link_child = 'blog.php?cat=' . $found_value;
                                        }
                                    }
                                    $scrolly = "";
                                    if ($one && $row1['page_name'] != "login") {
                                        $link_child = "#" . $row1['id'];
                                        $scrolly = " scrolly";
                                    }

                                    if ($row1['link_to_file'] != 'none') {
                                        $page_content_json = file_get_contents('admin/inc/pages/' . $child . '.json');
                                        $page_content_data = json_decode($page_content_json, true);
                                        $link_child = $page_content_data[0]['link_to_file'];
                                    }
                                ?>
                                    <li><a href="<?= $link_child ?>" class="dropdown-item<?= $scrolly ?>">
                                            <?php
                                            if ($row1['page_name'] == 'index') {
                                                echo "Home";
                                            } else {
                                                echo $child_name;
                                            } ?>
                                        </a>
                                    </li>

                                <?php
                                }
                                ?>
                            </ul>
                        <?php
                        }
                        ?>
            </li> &nbsp; &nbsp;
        <?php
        }
        ?>
</ul>
<?php
    }
?>