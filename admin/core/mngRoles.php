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

// Delete role
$idToDel = filter_input(INPUT_GET, 'idToDel', FILTER_VALIDATE_INT);
if ($idToDel !== false && $idToDel !== null) {
    $rolessection->role_id = $idToDel;
    $stmt = $rolessection->showAllWhere('id', ['role_id']);

    if ($stmt) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rolessection->id = $row['id'];
            $rolessection->delete('id');
        }
    }

    $role->id = $idToDel;

    if ($role->delete('id')) {
        header('Location: ../index.php?p=allRoles&msg=roleDel');
        exit;
    }

    header('Location: ../index.php?p=allRoles&err=roleNoDel');
    exit;
}

$operation = (string) (filter_input(INPUT_POST, 'operation', FILTER_DEFAULT) ?? '');

if ($operation === 'edit') {
    $idToMod = (int) (filter_input(INPUT_POST, 'idToMod', FILTER_VALIDATE_INT) ?? 0);
    $role->id = $idToMod;

    $url_tablePage = (string) (filter_input(INPUT_POST, 'url_tablePage', FILTER_DEFAULT) ?? '');
    $url_pageName = (string) (filter_input(INPUT_POST, 'url_pageName', FILTER_DEFAULT) ?? '');
    $url_data = "&tablePage=" . urlencode($url_tablePage) . "&pageName=" . urlencode($url_pageName);

    $role->rolename = (string) (filter_input(INPUT_POST, 'rolename', FILTER_DEFAULT) ?? '');
    $redirectVal = (string) (filter_input(INPUT_POST, 'redirect', FILTER_DEFAULT) ?? '');
    $role->redirect = !empty($redirectVal) ? $redirectVal : 'none';

    if ($role->update(['rolename', 'redirect'], 'id')) {
        $sectionParent = $_POST['section'] ?? [];
        $sectionParentStr = is_array($sectionParent) ? implode(',', array_map('strval', $sectionParent)) : '';

        $sectionChild = $_POST['sectionChild'] ?? [];
        $sectionChildStr = '';
        if (is_array($sectionChild)) {
            $sectionChildArr = [];
            foreach ($sectionChild as $item) {
                $section->table = 'section_child';
                $section->id = $item;
                $stmt = $section->showAllWhere('id', ['id']);
                if ($stmt) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($row && is_array($sectionParent) && in_array($row['parent_id'], $sectionParent, false)) {
                        $sectionChildArr[] = (string) $item;
                    }
                }
            }
            $sectionChildStr = implode(',', $sectionChildArr);
        }

        $error = 0;

        $rolessection->table = 'roles_section';
        $rolessection->role_id = $idToMod;
        $rolessection->section_id = $sectionParentStr;

        if ($rolessection->itemExists('role_id')) {
            if (!$rolessection->update(['section_id'], 'role_id')) {
                $error++;
            }
        } else {
            if (!$rolessection->insert(['section_id', 'role_id'])) {
                $error++;
            }
        }

        $rolessection->table = 'roles_section_child';
        $rolessection->role_id = $idToMod;
        $rolessection->section_id = $sectionChildStr;

        if ($rolessection->itemExists('role_id')) {
            if (!$rolessection->update(['section_id'], 'role_id')) {
                $error++;
            }
        } else {
            if (!$rolessection->insert(['section_id', 'role_id'])) {
                $error++;
            }
        }

        $errMsg = $error > 0 ? '&err=rolePermFail' : '';
        header("Location: ../index.php?p=editRole{$url_data}&idToMod={$idToMod}&msg=roleEdit{$errMsg}");
        exit;
    }

    header("Location: ../index.php?p=allRoles&err=roleNoEdit{$url_data}");
    exit;
}

if ($operation === 'add') {
    $rolename = (string) (filter_input(INPUT_POST, 'rolename', FILTER_DEFAULT) ?? '');
    $role->rolename = $rolename;

    $url_tablePage = (string) (filter_input(INPUT_POST, 'url_tablePage', FILTER_DEFAULT) ?? '');
    $url_pageName = (string) (filter_input(INPUT_POST, 'url_pageName', FILTER_DEFAULT) ?? '');
    $url_data = "&tablePage=" . urlencode($url_tablePage) . "&pageName=" . urlencode($url_pageName);

    if ($role->roleExists()) {
        header("Location: ../index.php?p=addRole&err=roleExist{$url_data}");
        exit;
    }

    $redirectVal = (string) (filter_input(INPUT_POST, 'redirect', FILTER_DEFAULT) ?? '');
    $role->redirect = !empty($redirectVal) ? $redirectVal : 'none';

    if ($role->insert(['rolename', 'redirect'])) {
        $stmt1 = $role->showAllWhere('id', ['rolename']);
        $newRoleId = 0;
        if ($stmt1) {
            $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
            if ($row1) {
                $newRoleId = (int) $row1['id'];
            }
        }

        $sectionParent = $_POST['section'] ?? [];
        $sectionParentStr = is_array($sectionParent) ? implode(',', array_map('strval', $sectionParent)) : '';

        $sectionChild = $_POST['sectionChild'] ?? [];
        $sectionChildStr = '';
        if (is_array($sectionChild)) {
            $sectionChildArr = [];
            foreach ($sectionChild as $item) {
                $section->table = 'section_child';
                $section->id = $item;
                $stmt = $section->showAllWhere('id', ['id']);
                if ($stmt) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($row && is_array($sectionParent) && in_array($row['parent_id'], $sectionParent, false)) {
                        $sectionChildArr[] = (string) $item;
                    }
                }
            }
            $sectionChildStr = implode(',', $sectionChildArr);
        }

        $error = 0;

        $rolessection->table = 'roles_section';
        $rolessection->role_id = $newRoleId;
        $rolessection->section_id = $sectionParentStr;

        if (!$rolessection->insert(['section_id', 'role_id'])) {
            $error++;
        }

        $rolessection->table = 'roles_section_child';
        $rolessection->role_id = $newRoleId;
        $rolessection->section_id = $sectionChildStr;

        if (!$rolessection->insert(['section_id', 'role_id'])) {
            $error++;
        }

        $errMsg = $error > 0 ? '&err=rolePermFail' : '';
        header("Location: ../index.php?p=allRoles{$url_data}&msg=roleSucc{$errMsg}");
        exit;
    }

    header("Location: ../index.php?p=allRoles&err=roleFail{$url_data}");
    exit;
}

header('Location: ../index.php?p=allRoles&msg=noPost');
exit;
