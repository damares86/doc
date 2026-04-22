<button id="burger-menu" class="burger-menu">
    ☰
</button>
<div id="side_damares" class="">
    <div class="sidebar_damares sidebar-wrapper_damares shadow">
        <div class="sidebar-logo border-bottom">
            <a href="index.php">
                <img src="assets/images/logo/damares_logo.png" alt="Logo" srcset="" />
            </a>
        </div>
        <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-body py-4 px-4">
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <a href="#" id="topbarUserDropdown" class="user-dropdown d-flex align-items-center dropend dropdown-toggle border-0" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="avatar avatar-xl">
                                    <img src="uploads/avatar/<?= $_SESSION['avatar'] ?>" alt="Avatar">
                                </div>
                                <div class="text">
                                    <h6 class="user-dropdown-name"><?= $_SESSION['username'] ?></h6>
                                    <p class="user-dropdown-status text-sm text-muted"><?= $_SESSION['rolename'] ?></p>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="topbarUserDropdown">
                                <li class="px-2"><a class="dropdown-item border-0" href="index.php?p=editProfile"><?= $common_profile ?></a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li class="px-2"><a class="dropdown-item border-0" href="core/logout.php"><?= $common_logout ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 text-center">
            <?php
            $plugin->pluginname = "mini_cms";

            if ($plugin->itemExists('pluginname') && $plugin->isActive() == 1) {
            ?>
                <a href="../" class="btn icon btn-primary shadow mx-3 px-3 text-white">
                    <i class="bi bi-arrow-left-circle"></i> &nbsp; <?= $mc_backsite ?>
                </a>
            <?php
            }
            ?>
        </div>
        <ul class="sidebar_menu list-unstyled">
            <?php
            $role_id = $_SESSION['role_id'];
            $rolessection->table = 'rolesSectionChild';
            $rolessection->role_id = $role_id;
            $permissionChild = $rolessection->showAllWhere('id', ['role_id']);
            $permChildArr = $permissionChild->fetch(PDO::FETCH_ASSOC);
            extract($permChildArr);
            $sectionChild = explode(',', $permChildArr['section_id']);
            $section->table = 'sectionParent';
            $stmt = $section->showAll('id');

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $hasSub = "";
                $active = "";
                $link = $row['link'] == "index" ? "index.php" : "index.php?p=" . $row['link'] . "";
                $parent_id = $row['id'];

                $section->table = 'sectionChild';
                $section->parent_id = $row['id'];
                $child = $section->showAllWhere('id', ['parent_id']);
                $countChildPermissions = 0;

                while ($row2 = $child->fetch(PDO::FETCH_ASSOC)) {
                    if (in_array($row2['id'], $sectionChild)) {
                        $countChildPermissions++;
                    }
                }

                $child = $section->showAllWhere('id', ['parent_id']);

                if ($section->countChild($row['id']) > 0 && $countChildPermissions > 0) {
                    $check_nomenu = 0;
                    while ($row_child = $child->fetch(PDO::FETCH_ASSOC)) {
                        if ($row_child['show_menu'] == 1) {
                            $check_nomenu++;
                        }
                    }
                    // $link = ($row['link'] == "index") ? "" : "index.php?p=" . $row['link'];
                    if ($check_nomenu > 0) {
                        $hasSub = "has-sub";
                        $link = "javascript:void(0)"; // Imposta correttamente href solo per i link con sottomenu
                    }
                }

                if ($page == $row['link']) {
                    $active = "active";
                }

                $rolessection->role_id = $role_id;
                $rolessection->table = 'rolesSection';
                $permissionParent = $rolessection->showAllWhere('id', ['role_id']);
                $row3 = $permissionParent->fetch(PDO::FETCH_ASSOC);
                extract($row3);
                $perm = explode(',', $row3['section_id']);
                $sectionParent = [];
                foreach ($perm as $item) {
                    $sectionParent[] = $item;
                }

                if ($role_id == 1 ||  in_array($row['id'], $sectionParent)) {
            ?>
                    <li class="sidebar_li align-items-center <?= $active ?>">
                        <a href="<?= $link ?>" class="sidebar-link <?= $hasSub ?>">
                            <i class="bi bi-<?= $row['icon'] ?>"></i>
                            <?php
                            if ($lang == "en") {
                                echo $row['label'];
                            } else {
                                $locale_label = strtolower($row['label']);
                                $locale_label = str_replace(" ", "_", $locale_label);
                                $locale_label = "label_$locale_label";
                                $section_label = $$locale_label;
                                echo $section_label;
                            }
                            ?>
                        </a>
                        <?php
                        if ($hasSub) {
                        ?>
                            <span class="toggle-submenu">+</span>
                            <ul class="submenu_damares list-unstyled" style="display: none;">
                                <?php
                                $where = ['parent_id'];
                                $section->parent_id = $row['id'];
                                $child = $section->showAllChild();
                                if ($role_id == 1 || count($sectionChild) > 0) {
                                    while ($row1 = $child->fetch(PDO::FETCH_ASSOC)) {
                                        if (($role_id == 1 || in_array($row1['id'], $sectionChild))) {
                                            if ($check_nomenu > 0) {
                                                $active1 = "";
                                                $display = '';
                                                $show_menu = true;
                                                if ($row1['show_menu'] == 0) {
                                                    $display = 'style="display:none;"';
                                                    $show_menu = false;
                                                }
                                                extract($row1);

                                                if ($page == $row1['link']) {
                                                    $active1 = "active";
                                                }
                                ?>
                                                <li class="<?= $active1 ?>"><a href="index.php?p=<?= $row1['link'] ?>" data-parent-id="<?= $parent_id ?>" <?= $display ?>>
                                                        <i class="bi bi-<?= $row1['icon'] ?>"></i>
                                                        <span>
                                                            <?php
                                                            if ($show_menu) {
                                                                if ($lang == "en") {
                                                                    echo $row1['label'];
                                                                } else {
                                                                    $locale_label = strtolower($row1['label']);
                                                                    $locale_label = str_replace(" ", "_", $locale_label);
                                                                    $locale_label = "label_$locale_label";
                                                                    $section_label = $$locale_label;
                                                                    echo $section_label;
                                                                }
                                                            }
                                                            ?>
                                                        </span>
                                                    </a>
                                                </li>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                            </ul>
                        <?php
                                }
                        ?>
                    </li>
        <?php
                        }
                    }
                }
        ?>
        </ul>
    </div>
</div>

<script>
    $(document).ready(function() {
        var currentPage = <?= $pageId ?>;
        var parentPage = <?= $check_parent ?>;
        var parentOfChild = null;

        // Funzione per aprire il submenu senza animazione
        function openSubmenuNoAnimation($submenu) {
            $submenu.addClass('active').show();
            $submenu.prev('li').find('.toggle-submenu').text('-');
        }

        // Funzione per aprire il submenu con animazione
        function openSubmenu($submenu) {
            $submenu.addClass('active').slideDown();
            $submenu.prev('li').find('.toggle-submenu').text('-');
        }

        // Funzione per chiudere il submenu con animazione
        function closeSubmenu($submenu) {
            $submenu.removeClass('active').slideUp();
            $submenu.prev('li').find('.toggle-submenu').text('+');
        }

        // Apri i sottomenu dei parent attivi all'inizio, senza animazione
        $('a[data-parent-id]').each(function() {
            var $this = $(this);
            var parentId = $this.data('parent-id');

            if (parentId == parentPage || parentId == currentPage) {
                var $submenu = $this.closest('li').find('.submenu_damares');
                openSubmenuNoAnimation($submenu); // Apri senza animazione

                // Memorizza il parent se la pagina corrente è un child
                if (parentId == currentPage) {
                    parentOfChild = $this.data('parent-id');
                }
            }
        });

        // Apri anche il parent se la pagina corrente è un child
        if (parentOfChild !== null) {
            $('a[data-parent-id="' + parentOfChild + '"]').each(function() {
                var $submenu = $(this).closest('li').find('.submenu_damares');
                openSubmenuNoAnimation($submenu); // Apri senza animazione
            });
        }

        // Aggiungi la classe active anche al parent del submenu
        $('.submenu_damares').each(function() {
            if ($(this).find('li.active').length > 0) {
                $(this).prev('a').addClass('active');
                openSubmenuNoAnimation($(this)); // Apri senza animazione
                $(this).prev('span').text('-'); // Imposta il simbolo a '-'
            }
        });

        // Gestione del click sul toggle del submenu e sui link
        $('.toggle-submenu').on('click', function(e) {
            e.preventDefault();
            var $submenu = $(this).closest('li').find('.submenu_damares').first();

            if ($submenu.hasClass('active')) {
                closeSubmenu($submenu);
                $(this).text('+');
            } else {
                openSubmenu($submenu);
                $(this).text('-');
            }
        });

        // Abilita i link a.has-sub per aprire i sottomenu
        $('a.has-sub').on('click', function(e) {
            e.preventDefault();
            var $submenu = $(this).closest('li').find('.submenu_damares').first();

            if ($submenu.hasClass('active')) {
                closeSubmenu($submenu);
            } else {
                openSubmenu($submenu);
            }
        });

        // Impedire che i link con "javascript:void(0)" causino un reload
        $('a[href="javascript:void(0)"]').on('click', function(e) {
            e.preventDefault();
        });

        // Gestione del click sul burger menu
        $('#burger-menu').on('click', function() {
            $('#side_damares').toggleClass('active');
        });
    });
</script>




<style>
    /* Stile per la freccia e animazione */
    .toggle-submenu {
        font-size: 1em;
        cursor: pointer;
        margin-left: 5px;
    }
</style>