<?php

declare(strict_types=1);

$idToMod = (int) (filter_input(INPUT_GET, 'idToMod', FILTER_VALIDATE_INT) ?? 0);
$role->id = $idToMod;
$stmt1 = $role->showAllWhere('id', ['id']);

$setting->name = 'role_redirect';
$stmt = $setting->showAllWhere('id', ['name']);
$row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
$redir = (string) ($row['value'] ?? '0');

$url_tablePage = (string) (filter_input(INPUT_GET, 'tablePage', FILTER_DEFAULT) ?? '1');
$url_pageName = (string) (filter_input(INPUT_GET, 'pageName', FILTER_DEFAULT) ?? 'allRoles');
?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3 class="d-inline"><?= htmlspecialchars((string) ($role_edit_header ?? 'Edit role'), ENT_QUOTES, 'UTF-8') ?></h3>
                <a href="index.php?p=<?= urlencode($url_pageName) ?>&tablePage=<?= urlencode($url_tablePage) ?>&pageName=<?= urlencode($url_pageName) ?>" class="btn icon btn-info shadow mx-3 px-3">
                    <i class="bi bi-arrow-left-circle"></i> &nbsp; <?= htmlspecialchars((string) ($common_back ?? 'Back'), ENT_QUOTES, 'UTF-8') ?>
                </a>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php"><?= htmlspecialchars((string) ($common_dashboard ?? 'Dashboard'), ENT_QUOTES, 'UTF-8') ?></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= htmlspecialchars((string) ($role_edit_header ?? 'Edit role'), ENT_QUOTES, 'UTF-8') ?>
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
                        $roleid = $idToMod;
                        $rolename = '';
                        $redirect = '';
                        if ($stmt1 instanceof PDOStatement) {
                            $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                            if ($row1) {
                                $roleid = (int) ($row1['id'] ?? $idToMod);
                                $rolename = (string) ($row1['rolename'] ?? '');
                                $redirect = (string) ($row1['redirect'] ?? '');
                            }
                        }
                        ?>
                        <h4 class="card-title"><?= htmlspecialchars((string) ($role_edit_title ?? 'Edit role'), ENT_QUOTES, 'UTF-8') ?> <b><?= htmlspecialchars($rolename, ENT_QUOTES, 'UTF-8') ?></b></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" action="core/mngRoles.php" method="POST" data-parsley-validate>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label><?= htmlspecialchars((string) ($common_rolename ?? 'Role name'), ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group has-icon-left">
                                                <div class="form-check mandatory">
                                                    <div class="position-relative">
                                                        <input type="text" class="form-control" placeholder="Rolename" id="rolename" name="rolename" data-parsley-required="true" value="<?= htmlspecialchars($rolename, ENT_QUOTES, 'UTF-8') ?>" />
                                                        <div class="form-control-icon">
                                                            <i class="bi bi-person"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label><?= htmlspecialchars((string) ($common_section_auth ?? 'Authorized sections'), ENT_QUOTES, 'UTF-8') ?> <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-12 rounded px-5 py-2 my-1 border" style="background-color: #008db1;">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <h5 class="text-white"><?= htmlspecialchars((string) ($role_header_parent ?? 'Parent section'), ENT_QUOTES, 'UTF-8') ?></h5>
                                                </div>
                                                <div class="col-md-7">
                                                    <h5 class="text-white"><?= htmlspecialchars((string) ($role_header_child ?? 'Child section'), ENT_QUOTES, 'UTF-8') ?></h5>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $section->table = 'section_parent';
                                        $stmt = $section->showAll('id');

                                        $role_id = (int) ($_SESSION['role_id'] ?? 0);
                                        $rolessection->table = 'roles_section';
                                        $rolessection->role_id = $role_id;
                                        $permission = $rolessection->showAllPermission('id', ['role_id']);
                                        $sectionOk = [];

                                        if ($permission instanceof PDOStatement) {
                                            while ($item = $permission->fetch(PDO::FETCH_ASSOC)) {
                                                if ((int) $item['role_id'] === $role_id && !empty($item['section_id'])) {
                                                    $sectionOk = explode(',', (string) $item['section_id']);
                                                }
                                            }
                                        }

                                        $rolessection->table = 'roles_section';
                                        $rolessection->role_id = $idToMod;
                                        $permissionParent = $rolessection->showAllWhere('id', ['role_id']);
                                        $permArr = $permissionParent ? $permissionParent->fetch(PDO::FETCH_ASSOC) : null;
                                        $sectionParent = !empty($permArr['section_id']) ? explode(',', (string) $permArr['section_id']) : [];

                                        $rolessection->table = 'roles_section_child';
                                        $rolessection->role_id = $idToMod;
                                        $permissionChild = $rolessection->showAllWhere('id', ['role_id']);
                                        $permChildArr = $permissionChild ? $permissionChild->fetch(PDO::FETCH_ASSOC) : null;
                                        $sectionChild = !empty($permChildArr['section_id']) ? explode(',', (string) $permChildArr['section_id']) : [];

                                        if ($stmt instanceof PDOStatement) {
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $pId = (int) $row['id'];
                                                if ($role_id === 1 || in_array((string) $pId, $sectionOk, true)) {
                                                    $checkedParent = in_array((string) $pId, $sectionParent, true) ? 'checked' : '';
                                                    $pLabel = (string) $row['label'];
                                                    if (($lang ?? 'en') !== 'en') {
                                                        $locale_label = 'label_' . str_replace(' ', '_', strtolower($pLabel));
                                                        if (isset($$locale_label)) {
                                                            $pLabel = (string) $$locale_label;
                                                        }
                                                    }
                                                    ?>
                                                    <div class="col-md-12 rounded bg-light px-5 py-2 my-1 border">
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <div class="form-check">
                                                                        <div class="checkbox">
                                                                            <input type="checkbox" name="section[]" class="form-check-input" value="<?= $pId ?>" <?= $checkedParent ?>>
                                                                            <label><?= htmlspecialchars($pLabel, ENT_QUOTES, 'UTF-8') ?></label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <?php
                                                                    $section->table = 'section_child';
                                                                    $section->parent_id = $pId;
                                                                    $stmt1 = $section->showAllWhere('id', ['parent_id']);

                                                                    $rolessection->role_id = $role_id;
                                                                    $rolessection->table = 'roles_section_child';
                                                                    $permChildQuery = $rolessection->showAllPermission('id', ['role_id']);
                                                                    $sectionChildOk = [];
                                                                    if ($permChildQuery instanceof PDOStatement) {
                                                                        while ($cItem = $permChildQuery->fetch(PDO::FETCH_ASSOC)) {
                                                                            if (!empty($cItem['section_id'])) {
                                                                                $sectionChildOk = explode(',', (string) $cItem['section_id']);
                                                                            }
                                                                        }
                                                                    }

                                                                    if ($stmt1 instanceof PDOStatement) {
                                                                        while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                                                                            $cId = (int) $row1['id'];
                                                                            if ($role_id === 1 || in_array((string) $cId, $sectionChildOk, true)) {
                                                                                if ((int) ($row1['show_menu'] ?? 1) === 1) {
                                                                                    $checkedChild = in_array((string) $cId, $sectionChild, true) ? 'checked' : '';
                                                                                    $cLabel = (string) $row1['label'];
                                                                                    if (($lang ?? 'en') !== 'en') {
                                                                                        $locale_label = 'label_' . str_replace(' ', '_', strtolower($cLabel));
                                                                                        if (isset($$locale_label)) {
                                                                                            $cLabel = (string) $$locale_label;
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                    <div class="form-check">
                                                                                        <div class="checkbox">
                                                                                            <input type="checkbox" name="sectionChild[]" class="form-check-input" value="<?= $cId ?>" <?= $checkedChild ?>>
                                                                                            <label><?= htmlspecialchars($cLabel, ENT_QUOTES, 'UTF-8') ?></label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <?php
                                                                                }
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
                                        }

                                        if ($redir === '1') {
                                            ?>
                                            <div class="col-md-3">
                                                <label><?= htmlspecialchars((string) ($common_redirect ?? 'Redirect'), ENT_QUOTES, 'UTF-8') ?></label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group has-icon-left">
                                                    <div class="position-relative">
                                                        <input type="text" class="form-control" placeholder="Url" name="redirect" value="<?= htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8') ?>" />
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
                                        <input type="hidden" name="url_tablePage" value="<?= htmlspecialchars($url_tablePage, ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="url_pageName" value="<?= htmlspecialchars($url_pageName, ENT_QUOTES, 'UTF-8') ?>">

                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1 shadow">
                                                <?= htmlspecialchars((string) ($common_update ?? 'Update'), ENT_QUOTES, 'UTF-8') ?>
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
                    <h4 class="card-title px-4 pt-3"><?= htmlspecialchars((string) ($common_info ?? 'Info'), ENT_QUOTES, 'UTF-8') ?></h4>
                    <div class="card-content px-5 pb-4">
                        <ul>
                            <li><a href="http://dmweblab.com/portal/manual.php?prod=1&page=8" target="_blank"><?= htmlspecialchars((string) ($common_see_guide ?? 'See guide'), ENT_QUOTES, 'UTF-8') ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>