<?php


##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################


require __DIR__ . "/coreConfig.php";

// check if there's a role to delete

if (filter_input(INPUT_GET, "idToDel")) {

    $idToDel = filter_input(INPUT_GET, "idToDel");

    $rolessection->role_id = $idToDel;

    $stmt = $rolessection->showAllWhere('id', ['role_id']);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $rolessection->id = $row['id'];
        $rolessection->delete('id');
    }

    $role->id = $idToDel;

    if ($role->delete('id')) {
        header("Location: ../index.php?p=allRoles&msg=roleDel");
        exit;
    } else {
        header("Location: ../index.php?p=allRoles&err=roleNoDel");
        exit;
    }
}

$operation = filter_input(INPUT_POST, "operation");

// check if there's a role to edit or add


if ($operation == "edit") {

    $idToMod = filter_input(INPUT_POST, "idToMod");
    $role->id = $idToMod;

    $url_tablePage = filter_input(INPUT_POST,'url_tablePage');
    $url_pageName = filter_input(INPUT_POST,'url_pageName');

    $url_data = "&tablePage=$url_tablePage&pageName=$url_pageName" ;

    $role->rolename = filter_input(INPUT_POST, "rolename");
    if (filter_input(INPUT_POST, "redirect")) {
        $role->redirect = filter_input(INPUT_POST, "redirect");
    } else {
        $role->redirect = "none";
    }

    if ($role->update(['rolename', 'redirect'], 'id')) {
        $sectionParent = $_POST['section'];
        if (is_array($sectionParent)) {
            $sectionParentStr = implode(',', $sectionParent);
        } else {
            $sectionParentStr = '';
        }
        $sectionChild = $_POST['sectionChild'];

        $sectionChildStr = '';
        if (is_array($sectionChild)) {
            $sectionChildArr = [];
            foreach ($sectionChild as $item) {
                $rolessection->table = 'sectionChild';
                $rolessection->id = $item;
                $stmt = $rolessection->showAllWhere('id', ['id']);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                if (is_array($sectionParent) && in_array($row['parent_id'], $sectionParent)) {
                    $sectionChildArr[] = $item;
                }
            }
            $sectionChildStr = implode(',', $sectionChildArr);
        } else {
            $sectionChildStr = '';
        }

        $role->rolename = filter_input(INPUT_POST, "rolename");
        $role->table = 'roles';

        $stmt = $role->showAllWhere('id', ['rolename']);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);

        $error = 0;

        $rolessection->table = 'rolesSection';
        $rolessection->role_id = $row['id'];
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

        $rolessection->table = 'rolesSectionChild';
        $rolessection->role_id = $row['id'];
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

        $errMsg = '';

        if ($error > 0) {
            $errMsg = 'err=rolePermFail';
        }


        header("Location: ../index.php?p=editRole$url_data&idToMod=$idToMod&msg=roleEdit$errMsg");
        exit;
    } else {
        header("Location: ../index.php?p=allRoles&err=roleNoEdit$url_data");
        exit;
    }
} else if ($operation == "add") {

    $rolename = filter_input(INPUT_POST, "rolename");
    $role->rolename = $rolename;

    if ($role->roleExists()) {
        header("Location: ../index.php?p=addRole&err=roleExist$url_data");
        exit;
    } else {
        $role->rolename = filter_input(INPUT_POST, "rolename");

        if (filter_input(INPUT_POST, "redirect")) {
            $role->redirect = filter_input(INPUT_POST, "redirect");
        } else {
            $role->redirect = "none";
        }

        if ($role->insert(['rolename', 'redirect'])) {

            $role->rolename = $rolename;
            $role->table = "roles";
            $stmt1 = $role->showAllWhere('id', ['rolename']);
            $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
            extract($row1);

            $sectionParent = $_POST['section'];
            $sectionParentStr = implode(',', $sectionParent);
            $sectionChild = $_POST['sectionChild'];

            $sectionChildStr = '';
            if (is_array($sectionChild)) {
                $sectionChildArr = [];
                foreach ($sectionChild as $item) {
                    $rolessection->table = 'sectionChild';
                    $rolessection->id = $item;
                    $stmt = $rolessection->showAllWhere('id', ['id']);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    extract($row);
                    if (in_array($row['parent_id'], $sectionParent)) {
                        $sectionChildArr[] = $item;
                    }
                }

                $sectionChildStr = implode(',', $sectionChildArr);
            }

            $error = 0;

            $rolessection->table = 'rolesSection';
            $rolessection->role_id = $row1['id'];
            $rolessection->section_id = $sectionParentStr;

            if (!$rolessection->insert(['section_id', 'role_id'])) {
                $error++;
            }

            $rolessection->table = 'rolesSectionChild';
            $rolessection->role_id = $row1['id'];
            $rolessection->section_id = $sectionChildStr;

            if (!$rolessection->insert(['section_id', 'role_id'])) {
                $error++;
            }

            $errMsg = '';

            if ($error > 0) {
                $errMsg = 'err=rolePermFail';
            }

            //success
            header("Location: ../index.php?p=allRoles$url_data&msg=roleSucc$errMsg");
            exit;
        } else {
            //success
            header("Location: ../index.php?p=allRoles&err=roleFail$url_data");
            exit;
        }
    }
} else {
    header("Location: ../index.php?p=allRoles&msg=noPost");
    exit;
}
