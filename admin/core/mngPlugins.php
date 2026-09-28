<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

require_once __DIR__ . '/coreConfig.php';

// Plugin ZIP upload
if (filter_input(INPUT_POST, 'new') && isset($_FILES['zip_file']) && is_array($_FILES['zip_file'])) {
    if (!empty($_FILES['zip_file']['name'])) {
        $filename = basename((string) $_FILES['zip_file']['name']);
        $source = (string) $_FILES['zip_file']['tmp_name'];
        $type = (string) $_FILES['zip_file']['type'];

        $nameParts = explode('.', $filename);
        $ext = strtolower(end($nameParts));

        if ($ext !== 'zip') {
            header('Location: ../index.php?p=allPlugins&msg=pluginUploadFormatErr');
            exit;
        }

        $pluginsDir = __DIR__ . '/../plugins/';
        if (!is_dir($pluginsDir)) {
            @mkdir($pluginsDir, 0755, true);
        }

        $targetPath = $pluginsDir . $filename;

        if (move_uploaded_file($source, $targetPath)) {
            $zip = new ZipArchive();
            if ($zip->open($targetPath) === true) {
                $zip->extractTo($pluginsDir);
                $zip->close();
                @unlink($targetPath);

                $pluginFolder = $nameParts[0];
                $configFile = "{$pluginsDir}{$pluginFolder}/config.php";

                if (is_file($configFile)) {
                    $pluginname = '';
                    $description = '';
                    require $configFile;

                    $plugin->pluginname = $pluginname;
                    $plugin->description = $description;

                    if ($plugin->insert(['pluginname', 'description'])) {
                        header('Location: ../index.php?p=allPlugins&msg=pluginUploadSucc');
                        exit;
                    }
                }
            }
            header('Location: ../index.php?p=allPlugins&err=pluginDbErr');
            exit;
        }

        header('Location: ../index.php?p=allPlugins&err=pluginUploadErr');
        exit;
    }
}

$op = (string) (filter_input(INPUT_GET, 'op', FILTER_DEFAULT) ?? '');
$idPlugin = (int) (filter_input(INPUT_GET, 'idPlugin', FILTER_VALIDATE_INT) ?? 0);
$plugin->id = $idPlugin;
$pluginFolder = (string) ($plugin->showPluginnameById() ?? '');
$path = __DIR__ . "/../plugins/{$pluginFolder}";

if (empty($pluginFolder) || !is_dir($path)) {
    header('Location: ../index.php?p=allPlugins&err=pluginNotFound');
    exit;
}

$starterFile = "{$path}/starter.php";
if (is_file($starterFile)) {
    $query_create_table = '';
    $query_drop_table = '';
    $menu_link = [];
    $link_parent = '';
    $description = '';
    $pluginname = '';
    include $starterFile;
}

if ($op === 'add') {
    $error = 0;
    $errorPerm = 0;

    if (!empty($query_create_table) && $db) {
        try {
            $db->exec($query_create_table);
        } catch (PDOException) {
            $error++;
        }
    }

    if (!empty($menu_link) && is_array($menu_link)) {
        foreach ($menu_link as $mItem) {
            $parentInsertedId = 0;
            if (($mItem['link'] ?? '') !== 'link_parent') {
                $section->link = (string) ($mItem['link'] ?? '');
                $section->label = (string) ($mItem['label'] ?? '');
                $section->icon = (string) ($mItem['icon'] ?? '');

                if (!$section->insertParent()) {
                    $error++;
                }

                $section->table = 'section_parent';
                $stmt = $section->showAllWhere('id', ['link']);
                $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
                $pId = $row ? (int) $row['id'] : 0;
                $parentInsertedId = $pId;

                // User permissions
                $rolessection->table = 'roles_section';
                $rolessection->role_id = (int) ($_SESSION['role_id'] ?? 0);
                $stmt1 = $rolessection->showAllWhere('id', ['role_id']);
                $row1 = $stmt1 ? $stmt1->fetch(PDO::FETCH_ASSOC) : null;

                $permissions = !empty($row1['section_id']) ? explode(',', (string) $row1['section_id']) : [];
                if ($pId && !in_array((string) $pId, $permissions, true)) {
                    $permissions[] = (string) $pId;
                }
                $permissions_str = implode(',', $permissions);
                $rolessection->section_id = $permissions_str;

                if (!$rolessection->update(['section_id'], 'role_id')) {
                    $errorPerm++;
                }

                if ((int) ($_SESSION['role_id'] ?? 0) !== 1) {
                    $rolessection->role_id = 1;
                    $rolessection->section_id = $permissions_str;
                    if (!$rolessection->update(['section_id'], 'role_id')) {
                        $errorPerm++;
                    }
                }
            } else {
                $section->table = 'section_parent';
                $section->link = (string) ($link_parent ?? '');
                $stmt5 = $section->showAllWhere('id', ['link']);
                $row5 = $stmt5 ? $stmt5->fetch(PDO::FETCH_ASSOC) : null;
                $parentInsertedId = $row5 ? (int) $row5['id'] : 0;
            }

            if (!empty($mItem['child']) && is_array($mItem['child'])) {
                foreach ($mItem['child'] as $cItem) {
                    $section->parent_id = $parentInsertedId;
                    $section->link = (string) ($cItem['link'] ?? '');
                    $section->label = (string) ($cItem['label'] ?? '');
                    $section->icon = (string) ($cItem['icon'] ?? '');
                    $section->show_menu = (int) ($cItem['show_menu'] ?? 1);

                    if (!$section->insertChild()) {
                        $error++;
                    }

                    $section->table = 'section_child';
                    $stmtC = $section->showAllWhere('id', ['link']);
                    $rowC = $stmtC ? $stmtC->fetch(PDO::FETCH_ASSOC) : null;
                    $cId = $rowC ? (int) $rowC['id'] : 0;

                    $rolessection->table = 'roles_section_child';
                    $rolessection->role_id = (int) ($_SESSION['role_id'] ?? 0);
                    $stmt1C = $rolessection->showAllWhere('id', ['role_id']);
                    $row1C = $stmt1C ? $stmt1C->fetch(PDO::FETCH_ASSOC) : null;

                    $permissionsC = !empty($row1C['section_id']) ? explode(',', (string) $row1C['section_id']) : [];
                    if ($cId && !in_array((string) $cId, $permissionsC, true)) {
                        $permissionsC[] = (string) $cId;
                    }
                    $permissionsCStr = implode(',', $permissionsC);
                    $rolessection->section_id = $permissionsCStr;

                    if (!$rolessection->update(['section_id'], 'role_id')) {
                        $errorPerm++;
                    }

                    if ((int) ($_SESSION['role_id'] ?? 0) !== 1) {
                        $rolessection->role_id = 1;
                        $rolessection->section_id = $permissionsCStr;
                        if (!$rolessection->update(['section_id'], 'role_id')) {
                            $errorPerm++;
                        }
                    }
                }
            }
        }
    }

    $plugin->installed = 1;
    $plugin->active = 1;
    $plugin->pluginname = $pluginFolder;

    if (!$plugin->update(['installed', 'active'], 'pluginname')) {
        $error++;
    }

    $root = __DIR__ . '/../';
    $exclude_folder = ['frontend', 'misc'];

    $folders = glob("{$path}/*") ?: [];
    foreach ($folders as $row) {
        $item = pathinfo($row);
        if (is_dir($row) && !in_array($item['basename'], $exclude_folder, true)) {
            $inner = glob($row . '/*') ?: [];
            foreach ($inner as $elem) {
                if (is_dir($elem)) {
                    $item1 = pathinfo($elem);
                    $children = glob($elem . '/*') ?: [];
                    foreach ($children as $elem_child) {
                        if (is_dir($elem_child)) {
                            continue;
                        }
                        $file_child = pathinfo($elem_child);
                        $dest_file = $root . $item['basename'] . '/' . $item1['basename'] . '/' . $file_child['basename'];
                        if (!is_dir(dirname($dest_file))) {
                            @mkdir(dirname($dest_file), 0755, true);
                        }
                        if (!copy($elem_child, $dest_file)) {
                            $error++;
                        }
                    }
                } else {
                    $file_parent = pathinfo($elem);
                    $dest_file = $root . $item['basename'] . '/' . $file_parent['basename'];
                    if (!is_dir(dirname($dest_file))) {
                        @mkdir(dirname($dest_file), 0755, true);
                    }
                    if (!copy($elem, $dest_file)) {
                        $error++;
                    }
                }
            }
        }
    }

    @unlink(__DIR__ . '/../inc/class_initialize.php');

    $permMsg = $errorPerm > 0 ? '&err=pluginPerm' : '';
    if ($error === 0) {
        header("Location: ../index.php?p=allPlugins&msg=pluginAdd{$permMsg}");
        exit;
    }

    header('Location: ../index.php?p=allPlugins&err=pluginAddErr');
    exit;
}

if ($op === 'dis') {
    $error = 0;
    $errorPerm = 0;

    $plugin->active = 0;
    $plugin->pluginname = $pluginFolder;
    if (!$plugin->update(['active'], 'pluginname')) {
        $error++;
    }

    if (!empty($menu_link) && is_array($menu_link)) {
        foreach ($menu_link as $mItem) {
            if (!empty($mItem['child']) && is_array($mItem['child'])) {
                foreach ($mItem['child'] as $cItem) {
                    $section->link = (string) ($cItem['link'] ?? '');
                    if (!$section->deleteByLink('section_child')) {
                        $error++;
                    }
                }
            }

            if (($mItem['link'] ?? '') !== 'link_parent') {
                $section->link = (string) ($mItem['link'] ?? '');
                if (!$section->deleteByLink('section_parent')) {
                    $error++;
                }
            }
        }
    }

    if ($error === 0) {
        header('Location: ../index.php?p=allPlugins&msg=pluginDis');
        exit;
    }

    header('Location: ../index.php?p=allPlugins&err=pluginDisErr');
    exit;
}

if ($op === 'rm') {
    $error = 0;
    $errorPerm = 0;

    if (!empty($query_drop_table) && $db) {
        try {
            $db->exec($query_drop_table);
        } catch (PDOException) {
            $error++;
        }
    }

    if (!empty($menu_link) && is_array($menu_link)) {
        foreach ($menu_link as $mItem) {
            if (!empty($mItem['child']) && is_array($mItem['child'])) {
                foreach ($mItem['child'] as $cItem) {
                    $section->link = (string) ($cItem['link'] ?? '');
                    $section->deleteByLink('section_child');
                }
            }

            if (($mItem['link'] ?? '') !== 'link_parent') {
                $section->link = (string) ($mItem['link'] ?? '');
                $section->deleteByLink('section_parent');
            }
        }
    }

    $plugin->installed = 0;
    $plugin->active = 0;
    $plugin->id = $idPlugin;
    if (!$plugin->update(['installed', 'active'], 'id')) {
        $error++;
    }

    @unlink(__DIR__ . '/../inc/class_initialize.php');

    $root = __DIR__ . '/../';
    $exclude_folder = ['frontend', 'misc'];

    $folders = glob("{$path}/*") ?: [];
    foreach ($folders as $folderPath) {
        $folderInfo = pathinfo($folderPath);
        if (is_dir($folderPath) && !in_array($folderInfo['basename'], $exclude_folder, true)) {
            $inners = glob("{$folderPath}/*") ?: [];
            foreach ($inners as $inner) {
                if (is_dir($inner)) {
                    $childFiles = glob("{$inner}/*") ?: [];
                    foreach ($childFiles as $childFile) {
                        $fileInfo = pathinfo($childFile);
                        $destFile = $root . $folderInfo['basename'] . '/' . basename($inner) . '/' . $fileInfo['basename'];
                        if (is_file($destFile)) {
                            @unlink($destFile);
                        }
                    }
                } else {
                    $fileInfo = pathinfo($inner);
                    $destFile = $root . $folderInfo['basename'] . '/' . $fileInfo['basename'];
                    if (is_file($destFile)) {
                        @unlink($destFile);
                    }
                }
            }
        }
    }

    if ($error === 0) {
        header('Location: ../index.php?p=allPlugins&msg=pluginRm');
        exit;
    }

    header('Location: ../index.php?p=allPlugins&err=pluginRmErr');
    exit;
}

header('Location: ../index.php?p=allPlugins&msg=noPost');
exit;
