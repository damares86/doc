<?php

declare(strict_types=1);

if (($op ?? '') === 'add') {
    $setting->name = 'role_redirect';
    $setting->value = '1';

    if ($setting->updateValue()) {
        $error = 0;
        $path = $path ?? __DIR__;

        if (is_file("{$path}/locale/en/redirect_en.php")) {
            if (!@copy("{$path}/locale/en/redirect_en.php", '../locale/en/redirect_en.php')) {
                $error++;
            }
        }

        if (is_file("{$path}/locale/it/redirect_it.php")) {
            if (!@copy("{$path}/locale/it/redirect_it.php", '../locale/it/redirect_it.php')) {
                $error++;
            }
        }

        $plugin->id = (int) ($idPlugin ?? 0);
        $plugin->installed = 1;
        $plugin->active = 1;
        $plugin->update(['installed', 'active'], 'id');

        if ($error === 0) {
            header('Location: ../index.php?p=allPlugins&msg=pluginAdd');
        } else {
            header('Location: ../index.php?p=allPlugins&err=pluginAddErr');
        }
        exit;
    }

    header('Location: ../index.php?p=allPlugins&err=pluginAddErr');
    exit;
}

if (($op ?? '') === 'rm') {
    $setting->name = 'role_redirect';
    $setting->value = '0';

    if ($setting->updateValue()) {
        $error = 0;
        if (is_file('../locale/en/redirect_en.php')) {
            @unlink('../locale/en/redirect_en.php');
        }
        if (is_file('../locale/it/redirect_it.php')) {
            @unlink('../locale/it/redirect_it.php');
        }

        $plugin->id = (int) ($idPlugin ?? 0);
        $plugin->installed = 0;
        $plugin->active = 0;
        $plugin->update(['installed', 'active'], 'id');

        if ($error === 0) {
            header('Location: ../index.php?p=allPlugins&msg=pluginRm');
        } else {
            header('Location: ../index.php?p=allPlugins&err=pluginRmErr');
        }
        exit;
    }

    header('Location: ../index.php?p=allPlugins&err=pluginRmErr');
    exit;
}

require_once __DIR__ . '/config.php';
