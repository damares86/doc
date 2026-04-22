<?php

$idToMod = filter_input(INPUT_GET, "idToMod");
$role->id = $idToMod;
$stmt1 = $role->showAllWhere('id', ['id']);

$setting->name = "role_redirect";
$stmt = $setting->showAllWhere('id', ['name']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$redir = $row['value'];

$url_tablePage = filter_input(INPUT_GET, 'tablePage');
$url_pageName = filter_input(INPUT_GET, 'pageName');
?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3 class="d-inline"><?= $role_edit_header ?></h3>
                <a href="index.php?p=<?=$url_pageName?>&tablePage=<?=$url_tablePage?>&pageName=<?=$url_pageName?>" class="btn icon btn-info shadow mx-3 px-3">
                    <i class="bi bi-arrow-left-circle"></i> &nbsp; <?=$common_back?>
                </a>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php"><?= $common_dashboard ?></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= $role_edit_header ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <br>

    <section class="section">
        <div class="row">
            <div class="col-md-8 col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <?php

                        $roleid = "";
                        $rolename = "";
                        $redirect = "";
                        while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                            $roleid = $row1['id'];
                            $rolename = $row1['rolename'];
                            $redirect = $row1['redirect'];
                        ?>
                            <h4 class="card-title"><?= $role_edit_title ?> <b><?= $rolename ?></b></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                        <?php


                        }
                        ?>
                        <form class="form form-horizontal" action="core/mngRoles.php" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label><?= $common_rolename ?> <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group has-icon-left">
                                            <div class="form-check mandatory">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="Rolename" id="rolename" name="rolename" data-parsley-required="true" value="<?= $rolename ?>" />
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label><?= $common_section_auth ?> <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-12 rounded px-5 py-2 my-1 border" style="background-color: #008db1;">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <h5 class="text-white"><?= $role_header_parent ?></h5>
                                            </div>
                                            <div class="col-md-7">
                                                <h5 class="text-white"><?= $role_header_child ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    // get all parent sections
                                    $section->table = 'sectionParent';
                                    $stmt = $section->showAll('id');

                                    $role_id = $_SESSION['role_id'];
                                    $section->table = 'rolesSection';
                                    $rolessection->role_id = $role_id;
                                    $permission = $rolessection->showAllPermission('id', ['role_id']);
                                    $sectionOk = [];

                                    // hide sections for non root users
                                    foreach ($permission as $item) {
                                        if ($item['role_id'] == $role_id) {
                                            if (!is_null($item['section_id'])) {
                                                $section_id_arr = explode(',', $item['section_id']);
                                                $sectionOk[] = $section_id_arr;
                                            } else {
                                                $sectionOk[] = array(0);
                                            }
                                        }
                                    }
                                    // get all permissions for parent section for the role to modify
                                    $rolessection->table = 'rolesSection';
                                    $rolessection->role_id = $idToMod;
                                    $permissionParent = $rolessection->showAllWhere('id', ['role_id']);
                                    $permArr = $permissionParent->fetch(PDO::FETCH_ASSOC);
                                    extract($permArr);
                                    $sectionParent = explode(',', $permArr['section_id']);

                                    // get all permissions for child section for the role to modify
                                    $rolessection->table = 'rolesSectionChild';
                                    $rolessection->role_id = $idToMod;
                                    $permissionChild = $rolessection->showAllWhere('id', ['role_id']);
                                    $permChildArr = $permissionChild->fetch(PDO::FETCH_ASSOC);
                                    extract($permChildArr);
                                    $sectionChild = explode(',', $permChildArr['section_id']);


                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        extract($row);


                                        if ($role_id == 1 ||  in_array($row['id'], $sectionOk[0])) {
                                            $checkedParent = '';
                                            if (in_array($row['id'], $sectionParent)) {
                                                $checkedParent = 'checked';
                                            }



                                    ?>
                                            <div class="col-md-12 rounded bg-light px-5 py-2 my-1 border">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="form-check">
                                                                <div class="checkbox">
                                                                    <input type="checkbox" name="section[]" class="form-check-input" value="<?= $row['id'] ?>" <?= $checkedParent ?>>
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
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-7">
                                                            <?php
                                                            $section->table = 'sectionChild';
                                                            $section->parent_id = $row['id'];
                                                            $stmt1 = $section->showAllWhere('id', ['parent_id']);
                                                            while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                                                                extract($row1);

                                                                // hide sections for non root users
                                                                $role_id = $_SESSION['role_id'];
                                                                $rolessection->role_id = $role_id;
                                                                $rolessection->table = 'rolesSectionChild';
                                                                $permissionChild = $rolessection->showAllPermission('id', ['role_id']);

                                                                $sectionChildOk = [];
                                                                foreach ($permissionChild as $item) {
                                                                    if (!is_null($item['section_id'])) {
                                                                        $section_child_arr = explode(',', $item['section_id']);
                                                                        $sectionChildOk[] = $section_child_arr;
                                                                    } else {
                                                                        $sectionOk[] = array(0);
                                                                    }
                                                                }

                                                                if ($role_id == 1 ||  in_array($row1['id'], $sectionChildOk[0])) {

                                                                    if($row1['show_menu']==1){
                                                                    $checkedChild = '';
                                                                    if (in_array($row1['id'], $sectionChild)) {
                                                                        $checkedChild = 'checked';
                                                                    }

                                                            ?>
                                                                    <div class="form-check">
                                                                        <div class="checkbox">
                                                                            <input type="checkbox" name="sectionChild[]" class="form-check-input" value="<?= $row1['id'] ?>" <?= $checkedChild ?>>
                                                                            <?php
                                                                            if ($lang == "en") {
                                                                                echo $row1['label'];
                                                                            } else {
                                                                                $locale_label = strtolower($row1['label']);
                                                                                $locale_label = str_replace(" ", "_", $locale_label);
                                                                                $locale_label = "label_$locale_label";
                                                                                $section_label = $$locale_label;
                                                                                echo $section_label;
                                                                            }
                                                                            ?>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                            <?php
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php
                                        }
                                    }

                                    if ($redir == 1) {
                                        ?>
                                        <div class="col-md-3">
                                            <label><?= $common_redirect ?> </label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="Url" id="first-name-icon" name="redirect" value="<?= $redirect ?>" />
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-link-45deg"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>


                                    <input type="hidden" name="operation" value="edit">
                                    <input type="hidden" name="idToMod" value="<?= $roleid ?>">
                                    <input type="hidden" name="origin" value="editRole">
                                    <input type="hidden" name="url_tablePage" value="<?= $url_tablePage ?>">
                                    <input type="hidden" name="url_pageName" value="<?= $url_pageName ?>">

                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1 shadow">
                                            <?= $common_update ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="card shadow">
                    <h4 class="card-title px-4 pt-3"><?= $common_info ?></h4>
                    <div class="card-content px-5 pb-4">
                        <ul>
                            <li><a href="http://dmweblab.com/portal/manual.php?prod=1&page=8" target="_blank"><?= $common_see_guide ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>