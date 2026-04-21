<?php

// here it's possibile to add some extra operations for the installation

if ($op == 'add') {

    // operations during the installation
    $setting->name = 'role_redirect';
    $setting->value = 1;
    if ($setting->updateValue()) {

        $error = 0;
        // copy
        if (copy("$path/locale/en/redirect_en.php", "../locale/en/redirect_en.php")) {
            chmod("../locale/en/redirect_en.php", 0755);
        } else {
            $error++;
            echo "$dest_file -> $error<br>";
        }

        if (copy("$path/locale/it/redirect_it.php", "../locale/it/redirect_it.php")) {
            chmod("../locale/en/redirect_en.php", 0755);
        } else {
            $error++;
            echo "$dest_file -> $error<br>";
        }
        $plugin->id = $idPlugin;
        $plugin->installed = 1;
        $plugin->active = 1;
        $plugin->update(['installed', 'active'], 'id');

        $error_msg = "";
        if ($error == 0) {
            header("Location: ../index.php?p=allPlugins&msg=pluginAdd");
            exit;
        } else {
            header("Location: ../index.php?p=allPlugins&err=pluginAddErr");
            exit;
        }
    } else {
        header("Location: ../index.php?p=allPlugins&err=pluginAddErr");
        exit;
    }
} else if ($op == 'rm') {

    // operations during the remove
    $setting->name = 'role_redirect';
    $setting->value = 0;
    if ($setting->updateValue()) {

        $error = 0;
        // copy
        if (!unlink("../locale/en/redirect_en.php")) {
            $error++;
        }
        if (!unlink("../locale/it/redirect_it.php")) {
            $error++;
        }

        $plugin->id = $idPlugin;
        $plugin->installed = 0;
        $plugin->active = 0;
        $plugin->update(['installed', 'active'], 'id');
        if ($error == 0) {
            header("Location: ../index.php?p=allPlugins&msg=pluginRm");
            exit;
        } else {
            header("Location: ../index.php?p=allPlugins&err=pluginRmErr");
            exit;
        }
    } else {
        header("Location: ../index.php?p=allPlugins&err=pluginRmErr");
        exit;
    }
}

require "config.php";
