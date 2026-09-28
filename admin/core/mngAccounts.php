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

// Check if there's an account to delete
$idToDel = filter_input(INPUT_GET, 'idToDel', FILTER_VALIDATE_INT);
if ($idToDel !== false && $idToDel !== null) {
    $accountroles->account_id = $idToDel;
    $accountroles->delete('account_id');

    $account->table = 'accounts';
    $account->id = $idToDel;

    if ($account->delete('id')) {
        header('Location: ../index.php?p=allAccounts&msg=accountDel');
        exit;
    }

    header('Location: ../index.php?p=allAccounts&err=accountNoDel');
    exit;
}

$operation = (string) (filter_input(INPUT_POST, 'operation', FILTER_DEFAULT) ?? '');
$idToMod = filter_input(INPUT_POST, 'idToMod', FILTER_VALIDATE_INT);

// Check if there's an account to edit
if ($idToMod !== false && $idToMod !== null) {
    $id = $idToMod;
    $account->id = $id;
    $account->table = 'accounts';

    $url_tablePage = (string) (filter_input(INPUT_POST, 'url_tablePage', FILTER_DEFAULT) ?? '');
    $url_pageName = (string) (filter_input(INPUT_POST, 'url_pageName', FILTER_DEFAULT) ?? '');
    $url_data = "&tablePage=" . urlencode($url_tablePage) . "&pageName=" . urlencode($url_pageName);

    if ($operation === 'password') {
        $password = (string) (filter_input(INPUT_POST, 'password', FILTER_DEFAULT) ?? '');
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $account->password = $password_hash;

        if ($account->update(['password'], 'id')) {
            header("Location: ../index.php?p=editAccount&idToMod={$id}&msg=passMod{$url_data}");
            exit;
        }

        header("Location: ../index.php?p=editAccount&idToMod={$id}&err=passNoMod{$url_data}");
        exit;
    }

    if ($operation === 'edit') {
        $account->id = $id;
        $stmt = $account->showAllWhere('id', ['id']);
        $old_email = '';
        if ($stmt) {
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($existing) {
                $old_email = (string) ($existing['email'] ?? '');
            }
        }

        $email = (string) (filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
        $auth->email = $email;

        if ($auth->emailExists() && $email !== $old_email) {
            if (filter_input(INPUT_POST, 'frontend')) {
                header("Location: ../../profile.php?err=accountExist{$url_data}");
                exit;
            }
            header("Location: ../index.php?p=editAccount&err=accountExist{$url_data}");
            exit;
        }

        $account->username = (string) (filter_input(INPUT_POST, 'username', FILTER_DEFAULT) ?? '');
        $account->email = $email;

        require __DIR__ . '/accountDetails.php';

        $details_arr = [];
        foreach ($account_details as $item) {
            $details_arr[] = [$item => (string) ($_POST[$item] ?? '')];
        }
        $account->details = !empty($details_arr) ? serialize($details_arr) : null;

        $details_opt_arr = [];
        foreach ($account_details_opt as $item) {
            $details_opt_arr[] = [$item => (string) ($_POST[$item] ?? '')];
        }
        $account->details_opt = !empty($details_opt_arr) ? serialize($details_opt_arr) : null;

        if (isset($_FILES['avatar']) && is_array($_FILES['avatar']) && (int) $_FILES['avatar']['size'] > 0) {
            $avatarName = basename((string) $_FILES['avatar']['name']);
            $file->filename = $avatarName;
            $file->inputFileName = (string) $_FILES['avatar']['tmp_name'];
            $file->label = 'avatar_' . random_int(10, 100);
            $file->path = '../uploads/avatar/';
            $file->origin = (string) filter_input(INPUT_POST, 'origin', FILTER_DEFAULT);
            $file->filename_orig = (string) filter_input(INPUT_POST, 'avatar_orig', FILTER_DEFAULT);
            $file->id = $file->showIdByFilename();
            $file->operation = $operation;

            if ($file->uploadFile()) {
                $account->avatar = $avatarName;
                if ((int) ($_SESSION['account_id'] ?? 0) === $id) {
                    $_SESSION['avatar'] = $avatarName;
                }
                $avatarOrig = (string) ($_POST['avatar_orig'] ?? 'default.png');
                if ($avatarOrig !== 'default.png' && is_file("../uploads/avatar/{$avatarOrig}")) {
                    @unlink("../uploads/avatar/{$avatarOrig}");
                }
            } else {
                header("Location: ../index.php?p=allAccounts&err=noAvatarUpload{$url_data}");
                exit;
            }
        } else {
            $account->avatar = (string) (filter_input(INPUT_POST, 'avatar_orig', FILTER_DEFAULT) ?? 'default.png');
        }

        if ($account->update(['username', 'email', 'avatar', 'details', 'details_opt'], 'id')) {
            if (filter_input(INPUT_POST, 'frontend')) {
                header('Location: ../../profile.php?msg=accountEdit');
                exit;
            }

            $accountroles->role_id = (int) filter_input(INPUT_POST, 'role', FILTER_VALIDATE_INT);
            $accountroles->account_id = $id;

            if ($accountroles->update(['role_id'], 'account_id')) {
                header("Location: ../index.php?p=editAccount&idToMod={$id}&msg=accountEdit{$url_data}");
                exit;
            }

            header("Location: ../index.php?p=editAccount&idToMod={$id}&err=accountRoleNoEdit{$url_data}");
            exit;
        }

        if (filter_input(INPUT_POST, 'frontend')) {
            header("Location: ../../profile.php?msg=accountNoEdit{$url_data}");
            exit;
        }

        header("Location: ../index.php?p=editAccount&idToMod={$id}&err=accountNoEdit{$url_data}");
        exit;
    }
} elseif ($operation === 'add') {
    $email = (string) (filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
    $auth->email = $email;

    if ($auth->emailExists()) {
        header('Location: ../index.php?p=addAccount&err=accountExist');
        exit;
    }

    $account->username = (string) (filter_input(INPUT_POST, 'username', FILTER_DEFAULT) ?? '');
    $account->email = $email;

    $password = (string) (filter_input(INPUT_POST, 'password', FILTER_DEFAULT) ?? '');
    $account->password = password_hash($password, PASSWORD_DEFAULT);

    require __DIR__ . '/accountDetails.php';

    $details_arr = [];
    foreach ($account_details as $item) {
        $details_arr[] = [$item => (string) ($_POST[$item] ?? '')];
    }
    $account->details = !empty($details_arr) ? serialize($details_arr) : null;

    $details_opt_arr = [];
    foreach ($account_details_opt as $item) {
        $details_opt_arr[] = [$item => (string) ($_POST[$item] ?? '')];
    }
    $account->details_opt = !empty($details_opt_arr) ? serialize($details_opt_arr) : null;

    // Avatar upload
    $errUpload = '';
    $file->operation = $operation;

    if (isset($_FILES['avatar']) && is_array($_FILES['avatar']) && (int) $_FILES['avatar']['size'] > 0) {
        $avatarName = basename((string) $_FILES['avatar']['name']);
        $file->filename = $avatarName;
        $file->inputFileName = (string) $_FILES['avatar']['tmp_name'];
        $file->label = 'avatar_' . random_int(10, 100);
        $file->path = '../uploads/avatar/';
        $file->origin = (string) filter_input(INPUT_POST, 'origin', FILTER_DEFAULT);

        if ($file->uploadFile()) {
            $account->avatar = $avatarName;
        } else {
            $errUpload = '&err=noAvatarUpload';
            $account->avatar = 'default.png';
        }
    } else {
        $account->avatar = 'default.png';
    }

    if ($account->insert(['username', 'email', 'password', 'avatar', 'details', 'details_opt'])) {
        $roleId = (int) filter_input(INPUT_POST, 'role', FILTER_VALIDATE_INT);
        $accountroles->role_id = $roleId;

        $account->email = $email;
        $stmt = $account->showAllWhere('id', ['email']);
        $insertedId = 0;
        if ($stmt) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $insertedId = (int) $row['id'];
            }
        }

        $accountroles->account_id = $insertedId;

        if ($accountroles->insert(['account_id', 'role_id'])) {
            header("Location: ../index.php?p=allAccounts&msg=accountSucc{$errUpload}");
            exit;
        }

        if (empty($errUpload) && $account->avatar !== 'default.png') {
            @unlink("../uploads/avatar/{$account->avatar}");
        }
        header('Location: ../index.php?p=allAccounts&err=accountFail');
        exit;
    }

    if (empty($errUpload) && $account->avatar !== 'default.png') {
        @unlink("../uploads/avatar/{$account->avatar}");
    }
    header('Location: ../index.php?p=allAccounts&err=accountFail');
    exit;
}

header('Location: ../index.php?p=allAccounts&err=noPost');
exit;
