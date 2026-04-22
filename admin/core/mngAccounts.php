<?php


##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################


require __DIR__ . "/coreConfig.php";

// check if there's an account to delete

if (filter_input(INPUT_GET, "idToDel")) {

    // $idToDel = filter_input(INPUT_GET,"idToDel");
    $accountroles->account_id = filter_input(INPUT_GET, "idToDel");
    $accountroles->delete('account_id');
    $account->table = "accounts";

    $account->id = filter_input(INPUT_GET, "idToDel");

    if ($account->delete('id')) {
        header("Location: ../index.php?p=allAccounts&msg=accountDel");
        exit;
    } else {
        header("Location: ../index.php?p=allAccounts&err=accountNoDel");
        exit;
    }
}

$operation = filter_input(INPUT_POST, "operation");

// check if there's an account to edit or add

if (filter_input(INPUT_POST, "idToMod")) {

    $id = filter_input(INPUT_POST, "idToMod");
    $account->id = $id;
    $account->table = "accounts";

    $url_tablePage = filter_input(INPUT_POST,'url_tablePage');
    $url_pageName = filter_input(INPUT_POST,'url_pageName');

    $url_data = "&tablePage=$url_tablePage&pageName=$url_pageName" ;

    if ($operation == "password") {

        $password = filter_input(INPUT_POST, "password");
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $account->password = $password_hash;

        if ($account->update(['password'], 'id')) {
            header("Location: ../index.php?p=editAccount&idToMod=$id&msg=passMod$url_data");
            exit;
        } else {
            header("Location: ../index.php?p=editAccount&idToMod=$id&err=passNoMod$url_data");
            exit;
        }
    } else if ($operation == "edit") {

        $account->id = $id;
        $stmt = $account->showAllWhere('id', ['id']);
        $old_email = "";
        foreach ($stmt as $item) {
            $old_email = $item['email'];
        }
        $email = filter_input(INPUT_POST, "email");

        $auth->email = $email;

        if ($auth->emailExists() && $email != $old_email) {
            if (filter_input(INPUT_POST, 'frontend')) {
                header("Location: ../../profile.php?err=accountExist$url_data");
                exit;
            } else {
                header("Location: ../index.php?p=editAccount&err=accountExist$url_data");
                exit;
            }
        } else {

            $account->username = filter_input(INPUT_POST, "username");
            $account->email = filter_input(INPUT_POST, "email");

            require "accountDetails.php";

            $details_arr = [];
            $details_opt_arr = [];

            foreach ($account_details as $item) {
                $details_arr[] = array("$item" => "" . $_POST[$item] . "");
            }

            if ($details_arr) {
                $details_str = serialize($details_arr);
                $account->details = $details_str;
            }

            foreach ($account_details_opt as $item) {
                $details_opt_arr[] = array("$item" => "" . $_POST[$item] . "");
            }

            if ($details_opt_arr) {
                $details_opt_str = serialize($details_opt_arr);
                $account->details_opt = $details_opt_str;
            }

            if ($_FILES['avatar']['size'] > 0) {
                // set data for file uploading
                $file->filename = $_FILES['avatar']['name'];
                $file->inputFileName = $_FILES['avatar']['tmp_name'];
                $file->label = 'avatar_' . rand(10, 100);
                $file->path = "../uploads/avatar/";
                $file->origin = filter_input(INPUT_POST, "origin");
                $file->filename_orig = filter_input(INPUT_POST, "avatar_orig");
                $file->id = $file->showIdByFilename();
                $file->operation = $operation;

                if ($file->uploadFile()) {
                    $account->avatar = $_FILES['avatar']['name'];
                    if ($_SESSION['account_id'] == $id) {
                        $_SESSION['avatar'] = $_FILES['avatar']['name'];
                    }
                    if ($_POST['avatar_orig'] != "default.png") {
                        unlink("../uploads/avatar/" . filter_input(INPUT_POST, "avatar_orig"));
                    }
                } else {
                    header("Location: ../index.php?p=allAccounts&err=noAvatarUpload$url_data");
                    exit;
                }
            } else {
                $account->avatar = filter_input(INPUT_POST, "avatar_orig");
            }


            if ($account->update(['username', 'email', 'avatar', 'details', 'details_opt'], 'id')) {
                if (filter_input(INPUT_POST, 'frontend')) {
                    header("Location: ../../profile.php?msg=accountEdit");
                    exit;
                }
                $accountroles->role_id = filter_input(INPUT_POST, "role");
                $accountroles->account_id = $id;

                if ($accountroles->update(['role_id'], 'account_id')) {
                    header("Location: ../index.php?p=editAccount&idToMod=$id&msg=accountEdit$url_data");
                    exit;
                } else {
                    header("Location: ../index.php?p=editAccount&idToMod=$id&err=accountRoleNoEdit$url_data");
                    exit;
                }
            } else {
                if (filter_input(INPUT_POST, 'frontend')) {
                    header("Location: ../../profile.php?msg=accountNoEdit$url_data");
                    exit;
                } else {
                    header("Location: ../index.php?p=editAccount&idToMod=$id&err=accountNoEdit$url_data");
                    exit;
                }
            }


            exit;
        }
        exit;
    }
} else if ($operation == "add") {

    $auth->email = filter_input(INPUT_POST, "email");

    if ($auth->emailExists()) {
        header("Location: ../index.php?p=addAccount&err=accountExist");
        exit;
    } else {

        $account->username = filter_input(INPUT_POST, "username");
        $account->email = filter_input(INPUT_POST, "email");

        // hash password
        $password = filter_input(INPUT_POST, "password");
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $account->password = $password_hash;

        require "accountDetails.php";

        $details_arr = [];
        $details_opt_arr = [];

        foreach ($account_details as $item) {
            $details_arr[] = array("$item" => "" . $_POST[$item] . "");
        }

        $details_str = serialize($details_arr);
        $account->details = $details_str;

        foreach ($account_details_opt as $item) {
            $details_opt_arr[] = array("$item" => "" . $_POST[$item] . "");
        }
        $details_opt_str = serialize($details_opt_arr);
        $account->details_opt = $details_opt_str;

        // upload avatar
        $errUpload = "";
        $file->operation = filter_input(INPUT_POST, "operation");

        if ($_FILES['avatar']['size'] > 0) {

            // set data for file uploading
            $file->filename = $_FILES['avatar']['name'];
            $file->inputFileName = $_FILES['avatar']['tmp_name'];
            $file->label = 'avatar_' . rand(10, 100);
            $file->path = "../uploads/avatar/";
            $file->origin = filter_input(INPUT_POST, "origin");

            if ($file->uploadFile()) {
                $account->avatar = $_FILES['avatar']['name'];
            } else {
                $errUpload = "&err=noAvatarUpload";
                $account->avatar = "default.png";
            }
        } else {
            $account->avatar = "default.png";
        }

        if ($account->insert(['username', 'email', 'password', 'avatar', 'details', 'details_opt'])) {

            $accountroles->role_id = filter_input(INPUT_POST, "role");
            $insertedId = "";
            $account->email = filter_input(INPUT_POST, "email");

            $stmt = $account->showAllWhere('id', ['email']);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $insertedId = $row['id'];
            }

            $accountroles->account_id = $insertedId;

            // success, insert the role in accountsRoles table
            if ($accountroles->insert(['account_id', 'role_id'])) {

                //success
                header("Location: ../index.php?p=allAccounts&msg=accountSucc$errUpload");
                exit;
            } else {

                // failed, delete the user inserted

                if (!$errUpload) {
                    unlink("../uploads/avatar/" . $_FILES['avatar']['name'] . "");
                }
                header("Location: ../index.php?p=allAccounts&err=accountFail");
                exit;
            }
        } else {

            // error, removing avatar if uploaded
            if (!$errUpload) {
                unlink("../uploads/avatar/" . $_FILES['avatar']['name'] . "");
            }
            header("Location: ../index.php?p=allAccounts&err=accountFail");
            exit;
        }
    }
} else {
    header("Location: ../index.php?p=allAccounts&err=noPost");
    exit;
}
