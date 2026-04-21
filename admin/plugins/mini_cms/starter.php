<?php

$plugin_dir = '../plugins/mini_cms/';
$template_dir = '../template/';
$frontend_dir = '../../';
$json_dir = '../inc/pages/';
$menu_dir = '../inc/menu/';
if ($op == 'add') {
	
	if (!is_dir($template_dir)) {
		$oldmask = umask(0);
		mkdir($template_dir, 0777, true);
		umask($oldmask);
	}


	if (!is_dir($json_dir)) {
		$oldmask = umask(0);
		mkdir($json_dir, 0777, true);
		umask($oldmask);
	}

	if (!is_dir($menu_dir)) {
		$oldmask = umask(0);
		mkdir($menu_dir, 0777, true);
		umask($oldmask);
	}

	// copy template files
	if ($common->copyDirectory($plugin_dir . 'misc/template/', $template_dir)) {
		$common->chmod_R($template_dir, 0777);
	} else {
		$error++;
	}

	// copy frontend files
	if ($common->copyDirectory($plugin_dir . 'frontend/assets/', $frontend_dir . 'assets/')) {
		$common->chmod_R($frontend_dir . 'assets/', 0777);
	} else {
		$error++;
	}

	// copy frontend files
	if ($common->copyDirectory($plugin_dir . 'frontend/uploads/', $frontend_dir . 'uploads/')) {
		$common->chmod_R($frontend_dir . 'uploads/', 0777);
	} else {
		$error++;
	}

	if (copy($plugin_dir . 'misc/menu/menu.json', $menu_dir . 'menu.json')) {
		chmod($menu_dir . 'menu.json', 0777);
	} else {
		$error++;
	}

	rename($frontend_dir . 'index.php', $frontend_dir . '_index.php');

	foreach (glob($plugin_dir . 'misc/pages_file/*') as $row) {
		$item = pathinfo($row);

		if (copy($plugin_dir . 'misc/pages_file/' . $item['basename'] . '', $frontend_dir . $item['basename'])) {
			chmod($frontend_dir . $item['basename'], 0777);
		} else {
			$error++;
		}
	}

	foreach (glob($plugin_dir . 'misc/pages_json/*') as $row) {
		$item = pathinfo($row);

		if (copy($plugin_dir . 'misc/pages_json/' . $item['basename'] . '', $json_dir . $item['basename'])) {
			chmod($json_dir . $item['basename'], 0777);
		} else {
			$error++;
		}
	}

	$url = explode(".", $_SERVER['SERVER_NAME']);

	$website = "";
	$new_url = "";

	if ($url[0] == "www") {
		$website = implode(".", $url);
		array_shift($url);
		$new_url = implode(".", $url);
	} else {
		$new_url = implode(".", $url);
		array_unshift($url, "www");
		$website = implode(".", $url);
	}

	$file_path = 'site.php' ;
	
	if (!is_file($file_path)) {
		$file_handle = fopen($file_path, 'w');
		fwrite($file_handle, '<?php');
		fwrite($file_handle, "\n");
		fwrite($file_handle, '$site=array("' . $website . '","' . $new_url . '");');
		fwrite($file_handle, "\n");
		fwrite($file_handle, '?>');
	}
	
	chmod($file_path, 0777);

} else if ($op == 'rm') {

	// rimozione pagine
	$mc->table = 'mc_pages';
	$stmt = $mc->showAll('id');
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		extract($row);
		unlink($frontend_dir . $row['page_name'] . '.php');
	}

	unlink($frontend_dir . 'login.php');
	unlink($frontend_dir . 'index.php');
	unlink('../../core/site.php');

	rename($frontend_dir . '_index.php', $frontend_dir . 'index.php');

	$mc->rmdir_recursive($template_dir);
	$mc->rmdir_recursive($json_dir);
	$mc->rmdir_recursive($menu_dir);
	$mc->rmdir_recursive($frontend_dir . 'assets/');
	$mc->rmdir_recursive($frontend_dir . 'uploads/');
}
require "config.php";
