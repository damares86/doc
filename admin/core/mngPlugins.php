<?php


##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################


require __DIR__ . "/coreConfig.php";

if (filter_input(INPUT_POST, "new")) {

  if ($_FILES["zip_file"]["name"]) {
    $filename = $_FILES["zip_file"]["name"];
    $source = $_FILES["zip_file"]["tmp_name"];
    $type = $_FILES["zip_file"]["type"];


    $name = explode(".", $filename);
    $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
    foreach ($accepted_types as $mime_type) {
      if ($mime_type == $type) {
        $okay = true;
        break;
      }
    }

    $continue = strtolower($name[1]) == 'zip' ? true : false;
    if (!$continue) {
      header("Location: ../index.php?p=allPlugins&msg=pluginUploadFormatErr");
      exit;
    }

    $path = "../plugins/";
    if (!is_dir($path)) {
      mkdir($path);
      chmod($path, 0777);
    }

    $target_path = "../plugins/" . $filename;  // change this to the correct site path


    if (move_uploaded_file($source, $target_path)) {
      $zip = new ZipArchive();
      $x = $zip->open($target_path);

      $folder = "../plugins/";
      if ($x === true) {
        $zip->extractTo($folder); // change this to the correct site path
        $zip->close();

        $plugin->chmod_R($folder, 0777);

        unlink($target_path);
      }

      require $folder . $name[0] . '/config.php';

      $plugin->pluginname = $pluginname;
      $plugin->description = $description;

      if ($plugin->insert(['pluginname', 'description'])) {
        header("Location: ../index.php?p=allPlugins&msg=pluginUploadSucc");
        exit;
      } else {
        header("Location: ../index.php?p=allPlugins&err=pluginDbErr");
        exit;
      }
    } else {
      header("Location: ../index.php?p=allPlugins&err=pluginUploadErr");
      exit;
    }
  }
}

$op = filter_input(INPUT_GET, "op");

$idPlugin = filter_input(INPUT_GET, "idPlugin");
$plugin->id = $idPlugin;
$pluginFolder = $plugin->showPluginnameById();
$path = "../plugins/$pluginFolder";

include "$path/starter.php";
$exclude = array('..', '.', 'alert', 'func', '.gitkeep');

if ($op == "add") {

  // create table

  $error = 0;
  $errorPerm = 0;
  if ($query_create_table) {
    if (!$db->query($query_create_table)) {
      $error++;
    }
  }

  //echo "create table -> ".$error."<br>" ;

  if (isset($menu_link)) {
    for ($i = 0; $i < count($menu_link); $i++) {
      if ($menu_link[$i]['link'] != 'link_parent') {
        $section->link = $menu_link[$i]['link'];
        $section->label = $menu_link[$i]['label'];
        $section->icon = $menu_link[$i]['icon'];

        if (!$section->insertParent()) {
          $error++;
        }
        //echo "insert parent-> ".$error."<br>" ;

        // get the section parent inserted
        $section->table = 'sectionParent';
        $section->link = $menu_link[$i]['link'];
        $stmt = $section->showAllWhere('id', ['link']);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);

        // get the permission for the user that added the plugin
        $rolessection->table = 'rolesSection';
        $rolessection->role_id = $_SESSION['role_id'];
        $stmt1 = $rolessection->showAllWhere('id', ['role_id']);
        $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        extract($row1);

        $permissions = explode(',', $row1['section_id']);
        $permissions[] = $row['id'];

        $permissions_str = implode(',', $permissions);

        $rolessection->section_id = $permissions_str;

        // set permission for the user that added the plugin
        if (!$rolessection->update(['section_id'], 'role_id')) {
          $errorPerm++;
        }

        //echo "perm parent-> ".$errorPerm."<br>" ;


        // set permission for the root user
        if ($_SESSION['role_id'] != 1) {
          $rolessection->table = 'rolesSection';
          $rolessection->section_id = $permissions_str;
          $rolessection->role_id = 1;
          if (!$rolessection->update(['section_id'], 'role_id')) {
            $errorPerm++;
          }
          //echo "perm parent root-> ".$errorPerm."<br>" ;

        }
        $section->table = 'sectionParent';
        $stmt5 = $section->showAllLimitDesc('id', 1);
        $row5 = $stmt5->fetch(PDO::FETCH_ASSOC);
        extract($row5);
      } else {
        $section->table = 'sectionParent';
        $section->link = $link_parent;
        $stmt5 = $section->showAllWhere('id', ['link']);
        $row5 = $stmt5->fetch(PDO::FETCH_ASSOC);
        extract($row5);
      }

      if (isset($menu_link[$i]['child'])) {

        $child_link = $menu_link[$i]['child'];
        for ($idx = 0; $idx < count($child_link); $idx++) {

          $section->parent_id = $row5['id'];
          $section->link = $child_link[$idx]['link'];
          $section->label = $child_link[$idx]['label'];
          $section->icon = $child_link[$idx]['icon'];
          $section->show_menu = $child_link[$idx]['show_menu'];

          if (!$section->insertChild()) {
            $error++;
          }
          //echo "insert child-> ".$error."<br>" ;


          // get the section parent inserted
          $section->table = 'sectionChild';
          $section->link = $child_link[$idx]['link'];
          $stmt = $section->showAllWhere('id', ['link']);
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          extract($row);

          // get the permission for the user that added the plugin
          $rolessection->table = 'rolesSectionChild';
          $rolessection->role_id = $_SESSION['role_id'];
          $stmt1 = $rolessection->showAllWhere('id', ['role_id']);
          $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
          extract($row1);

          $permissions = explode(',', $row1['section_id']);
          $permissions[] = $row['id'];

          $permissions_str = implode(',', $permissions);

          $rolessection->section_id = $permissions_str;

          // set permission for the user that added the plugin
          if (!$rolessection->update(['section_id'], 'role_id')) {
            $errorPerm++;
            //echo "perm child-> ".$errorPerm."<br>" ;

          }

          // set permission for the root user
          if ($_SESSION['role_id'] != 1) {
            $rolessection->table = 'rolesSectionChild';
            $rolessection->section_id = $permissions_str;
            $rolessection->role_id = 1;
            if (!$rolessection->update(['section_id'], 'role_id')) {
              $errorPerm++;
              //echo "perm child root-> ".$errorPerm."<br>" ;

            }
          }
        }
      }
    }
  }

  $plugin->installed = 1;
  $plugin->active = 1;
  $plugin->pluginname = $pluginname;

  if (!$plugin->update(['installed', 'active'], 'pluginname')) {
    $error++;
    //echo "update plugin-> ".$error."<br>" ;

  }

  // echo "update -> $error<br>";
  $root = '../';


  $exclude_folder = ['frontend', 'misc'];
  foreach (glob("$path/*") as $row) {
    $item = pathinfo($row);

    if (is_dir($row) && !in_array($item['basename'], $exclude_folder)) {

      foreach (glob($row . '/*') as $elem) {

        if (is_dir($elem)) {

          $item1 = pathinfo($elem);
          foreach (glob($elem . '/*') as $elem_child) {

            // skip if it's a directory
            if (is_dir($elem_child)) {
              continue;
            }

            $file_child = pathinfo($elem_child);

            $source_file = $path . '/' . $item['basename'] . '/' . $item1['basename'] . '/' . $file_child['basename'];
            $dest_file = $root . $item['basename'] . '/' . $item1['basename'] . '/' . $file_child['basename'];

            if (!is_dir(dirname($dest_file))) {
              mkdir(dirname($dest_file), 0755, true);
            }

            if (copy($source_file, $dest_file)) {
              chmod($dest_file, 0755);
            } else {
              $error++;
              // echo "$dest_file -> $error<br>";
            }
          }
        } else {

          // salta se è una directory
          if (is_dir($elem)) {
            continue;
          }

          $file_parent = pathinfo($elem);

          $source_file = $elem;
          $dest_file = $root . $item['basename'] . '/' . $file_parent['basename'];

          if (!is_dir(dirname($dest_file))) {
            mkdir(dirname($dest_file), 0755, true);
          }

          if (copy($source_file, $dest_file)) {
            chmod($dest_file, 0755);
          } else {
            $error++;
            // echo "$dest_file -> $error<br>";
          }
        }
      }
    }
  }
  unlink("../inc/class_initialize.php");
  if ($error == 0) {

    $perm = '';
    if ($errorPerm > 0) {
      $perm = '&err=pluginPerm';
    }
    header("Location: ../index.php?p=allPlugins&msg=pluginAdd$perm");
    exit;
  } else {
    header("Location: ../index.php?p=allPlugins&err=pluginAddErr");
    exit;
  }
} else if ($op == "dis") {

  $error = 0;
  $errorPerm = 0;

  $pluginId = filter_input(INPUT_GET, 'idPlugin');
  $plugin->id = $pluginId;
  $stmt = $plugin->showAllWhere('id', ['id']);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  extract($row);
  $pluginname = $row['pluginname'];

  if (!$db->query("UPDATE " . $prefix . "plugins SET active = 0 WHERE pluginname = '$pluginname'")) {
    $error++;
  }

  if (isset($menu_link)) {

    for ($i = 0; $i < count($menu_link); $i++) {

      // $section->link = $menu_link[$i]['link'];
      // $stmt = $plugin->showAllWhere('id',['link']) ;
      // $row = $stmt->fetch(PDO::FETCH_ASSOC);
      // extract($row);

      if (isset($menu_link[$i]['child'])) {
        $childSection = [];
        $permissions_child_updated = [];

        $child_link = $menu_link[$i]['child'];

        for ($idx = 0; $idx < count($child_link); $idx++) {

          $section->link = $child_link[$idx]['link'];
          $section->table = 'sectionChild';
          $stmt1 = $section->showAllWhere('id', ['link']);
          $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
          extract($row1);

          $childSection[] = $row1['id'];

          $section->link = $child_link[$idx]['link'];
          if (!$section->deleteByLink("sectionChild")) {
            $error++;
          }
        }

        // get the permission for the user that disabled the plugin
        $rolessection->table = 'rolesSectionChild';
        $rolessection->role_id = $_SESSION['role_id'];
        $stmt2 = $rolessection->showAllWhere('id', ['role_id']);
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        extract($row2);

        $permissions = explode(',', $row2['section_id']);


        foreach ($childSection as $item) {
          $permissions[] = $item;
        }
      }

      $rolessection->table = 'rolesSectionChild';
      $rolessection->role_id = $_SESSION['role_id'];
      !is_null($permissions) ? $perm_child_str = implode(',', $permissions) : $perm_child_str = '';
      $rolessection->section_id = $perm_child_str;

      // set permission for the user that disabled the plugin
      if (!$rolessection->update(['section_id'], 'role_id')) {
        $errorPerm++;
      }

      // set permission for the root user
      if ($_SESSION['role_id'] != 1) {
        $rolessection->table = 'rolesSectionChild';
        $rolessection->role_id = 1;
        $rolessection->section_id = $perm_child_str;
        if (!$rolessection->update(['section_id'], 'role_id')) {
          $errorPerm++;
        }
      }

      $parentSection = [];
      $permissions_parent_updated = [];


      if ($menu_link[$i]['link'] != 'link_parent') {
        $section->link = $menu_link[$i]['link'];
        $section->table = 'sectionParent';
        $stmt3 = $section->showAllWhere('id', ['link']);
        $row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
        extract($row3);

        $parentSection[] = $row3['id'];

        $section->link = $menu_link[$i]['link'];

        if (!$section->deleteByLink("sectionParent")) {
          $error++;
        }

        // get the permission for the user that disabled the plugin
        $rolessection->table = 'rolesSection';
        $rolessection->role_id = $_SESSION['role_id'];
        $stmt4 = $rolessection->showAllWhere('id', ['role_id']);
        $row4 = $stmt4->fetch(PDO::FETCH_ASSOC);
        extract($row4);

        $permissions = explode(',', $row4['section_id']);

        foreach ($permissions as $item) {
          if (!in_array($item, $parentSection)) {
            $permissions_parent_updated[] = $item;
          }
        }
      }
    }

    $rolessection->table = 'rolesSection';
    $rolessection->role_id = $_SESSION['role_id'];
    !is_null($perm_parent_str) ? $perm_parent_str = implode(',', $permissions_child_updated) : $perm_parent_str = '';
    $rolessection->section_id = $perm_parent_str;

    // set permission for the user that disabled the plugin
    if (!$rolessection->update(['section_id'], 'role_id')) {
      $errorPerm++;
    }

    // set permission for the root user
    if ($_SESSION['role_id'] != 1) {
      $rolessection->table = 'rolesSection';
      $rolessection->role_id = 1;
      $rolessection->section_id = $perm_parent_str;
      if (!$rolessection->update(['section_id'], 'role_id')) {
        $errorPerm++;
      }
    }
  }

  $err_perm_msg = '';
  if ($errorPerm > 0) {
    $err_perm_msg = '&err=errPermPlugin';
  }

  if ($error == 0) {
    header("Location: ../index.php?p=allPlugins&msg=pluginDis$err_perm_msg ");
    exit;
  } else {
    header("Location: ../index.php?p=allPlugins&err=pluginDisErr$err_perm_msg ");
    exit;
  }
} else if ($op == "rm") {

  // REMOVE
  $error = 0;
  $errorPerm = 0;

  if ($query_drop_table) {
    if (!$db->query($query_drop_table)) {
      $error++;
    }
  }

  // Recupera info plugin
  $plugin->id = filter_input(INPUT_GET, 'idPlugin');
  $plugin->table = 'plugins';
  $stmt = $plugin->showAllWhere('id', ['id']);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row && $row['active'] == 1 && isset($menu_link)) {
    $currentRoleId = $_SESSION['role_id'];
    $childSectionsToRemove = [];
    $parentSectionsToRemove = [];

    for ($i = 0; $i < count($menu_link); $i++) {

      // === CHILD SECTION ===
      if (isset($menu_link[$i]['child'])) {
        $childLinks = $menu_link[$i]['child'];
        foreach ($childLinks as $child) {
          $section->link = $child['link'];
          $section->table = 'sectionChild';

          $stmt = $section->showAllWhere('id', ['link']);
          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          if ($row && isset($row['id'])) {
            $childSectionsToRemove[] = $row['id'];
          }

          // Elimina la sectionChild
          if (!$section->deleteByLink('sectionChild')) {
            $error++;
          }
        }
      }

      // === PARENT SECTION ===
      if ($menu_link[$i]['link'] != 'link_parent') {
        $section->link = $menu_link[$i]['link'];
        $section->table = 'sectionParent';

        $stmt = $section->showAllWhere('id', ['link']);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && isset($row['id'])) {
          $parentSectionsToRemove[] = $row['id'];
        }

        // Elimina la sectionParent
        if (!$section->deleteByLink('sectionParent')) {
          $error++;
        }
      }
    }

    // === AGGIORNA PERMESSI CHILD ===
    $rolessection->table = 'rolesSectionChild';

    $stmt = $rolessection->showAllWhere('id', ['role_id']);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $roleId = $row['role_id'];
      $existing = array_filter(explode(',', $row['section_id']), 'strlen');
      $updated = array_diff($existing, $childSectionsToRemove);
      $sectionStr = implode(',', $updated);

      // Non aggiornare il superuser se non necessario
      if ($roleId == 1 && $currentRoleId == 1) continue;

      $rolessection->role_id = $roleId;
      $rolessection->section_id = $sectionStr;

      if (!$rolessection->update(['section_id'], 'role_id')) {
        $errorPerm++;
      }
    }

    // === AGGIORNA PERMESSI PARENT ===
    $rolessection->table = 'rolesSection';

    $stmt = $rolessection->showAllWhere('id', ['role_id']);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $roleId = $row['role_id'];
      $existing = array_filter(explode(',', $row['section_id']), 'strlen');
      $updated = array_diff($existing, $parentSectionsToRemove);
      $sectionStr = implode(',', $updated);

      // Non aggiornare il superuser se non necessario
      if ($roleId == 1 && $currentRoleId == 1) continue;

      $rolessection->role_id = $roleId;
      $rolessection->section_id = $sectionStr;

      if (!$rolessection->update(['section_id'], 'role_id')) {
        $errorPerm++;
      }
    }
  }

  // Disattiva plugin
  $plugin->id = filter_input(INPUT_GET, 'idPlugin');
  $plugin->installed = 0;
  $plugin->active = 0;

  if (!$plugin->update(['installed', 'active'], 'id')) {
    $error++;
  }

  // Rimuovi class_initialize
  @unlink("../inc/class_initialize.php");

  // DELETE ALL FILES (tranne frontend e misc)
  $root = '../';
  $exclude_folder = ['frontend', 'misc'];

  foreach (glob("$path/*") as $folderPath) {
    $folderInfo = pathinfo($folderPath);

    if (is_dir($folderPath) && !in_array($folderInfo['basename'], $exclude_folder)) {
      foreach (glob($folderPath . '/*') as $inner) {
        if (is_dir($inner)) {
          foreach (glob($inner . '/*') as $childFile) {
            $fileInfo = pathinfo($childFile);
            $destFile = $root . $folderInfo['basename'] . '/' . basename($inner) . '/' . $fileInfo['basename'];
            if (file_exists($destFile) && !unlink($destFile)) {
              $error++;
            }
          }
        } else {
          $fileInfo = pathinfo($inner);
          $destFile = $root . $folderInfo['basename'] . '/' . $fileInfo['basename'];
          if (file_exists($destFile) && !unlink($destFile)) {
            $error++;
          }
        }
      }
    }
  }

  // REDIRECT
  $err_perm_msg = ($errorPerm > 0) ? '&err=errPermPlugin' : '';

  if ($error == 0) {
    header("Location: ../index.php?p=allPlugins&msg=pluginRm$err_perm_msg");
    exit;
  } else {
    header("Location: ../index.php?p=allPlugins&err=pluginRmErr$err_perm_msg");
    exit;
  }
}
