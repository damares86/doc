<header class="mb-5">
  <div class="header-top">
    <div class="container">
      <div class="logo">
        <a href="index.php"><img src="assets/images/logo/damares_logo.png" alt="Logo"></a>
      </div>
      <div class="header-top-right">

        <div class="dropdown">
          <a href="#" id="topbarUserDropdown" class="user-dropdown d-flex align-items-center dropend dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false">
            <div class="avatar avatar-md2">
              <img src="uploads/avatar/<?=$_SESSION['avatar']?>" alt="Avatar">
            </div>
            <div class="text">
              <h6 class="user-dropdown-name"><?= $_SESSION['username'] ?></h6>
              <p class="user-dropdown-status text-sm text-muted"><?= $_SESSION['rolename'] ?></p>
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="topbarUserDropdown">
            <li><a class="dropdown-item" href="index.php?p=editProfile"><?=$common_profile?></a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="core/logout.php"><?= $common_logout ?></a></li>
          </ul>
        </div>

        <!-- Burger button responsive -->
        <a href="#" class="burger-btn d-block d-xl-none">
          <i class="bi bi-justify fs-3"></i>
        </a>
      </div>
    </div>
  </div>
  <nav class="main-navbar shadow">
    <div class="container">
      <ul>


      <?php
        $active = "";
        if ($page == "dashboard") {
          $active = "active";
        }
        ?>

        <?php

        $role_id = $_SESSION['role_id'];

        $rolessection->table = 'rolesSectionChild';
        $rolessection->role_id = $role_id;
        $permissionChild = $rolessection->showAllWhere('id', ['role_id']);
        $permChildArr = $permissionChild->fetch(PDO::FETCH_ASSOC);
        extract($permChildArr);
        $sectionChild = explode(',', $permChildArr['section_id']);
        $section->table = 'sectionParent' ;
        $stmt = $section->showAll('id');

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

          extract($row);


          $hasSub = "";
          $active = "";
          $link = $row['link'] == "index" ? "" : "?p=" . $row['link'] . "";

          $section->table = 'sectionChild';
          $section->parent_id = $row['id'];
          $child = $section->showAllWhere('id', ['parent_id']);
          $countChildPermissions = 0;

          while ($row2 = $child->fetch(PDO::FETCH_ASSOC)) {

            if (in_array($row2['id'], $sectionChild)) {
              $countChildPermissions++;
            }
          }

          $disabled = '';
                
          if ($section->countChild($row['id']) > 0 && $countChildPermissions > 0) {
              $hasSub = "has-sub";
              $link = "#";
              $disabled = ' disabled';
          }


          if ($page == $row['link']) {
            $active = "active";
          }

          // SECTION PERMISSIONS
          $rolessection->role_id = $role_id;
          $rolessection->table = 'rolesSection';
          $permissionParent = $rolessection->showAllWhere('id', ['role_id']);
          $row3 = $permissionParent->fetch(PDO::FETCH_ASSOC);
          extract($row3);
          $perm = explode(',',$row3['section_id'] );
          
          $sectionParent = [];
          foreach ($perm as $item) {
            $sectionParent[] = $item;
          }
          
          if ($role_id == 1 || in_array($row['id'], $sectionParent)) {

        ?>
            <li class="menu-item <?= $active ?> <?= $hasSub ?>">
              <a href="index.php<?= $link ?>" class="menu-link <?=$disabled?>">
                <i class="bi bi-<?= $row['icon'] ?>"></i>
                <span>
                  <?php
                  if ($lang == "en") {
                    echo $row['label'];
                  } else {
                    $locale_label = strtolower($row['label']);
                    $locale_label = str_replace(" ", "_", $locale_label);
                    $locale_label = "label_$locale_label";
                    $section_label = $$locale_label;
                    echo $section_label;
                  }
                  ?>
                </span>
              </a>
              <?php
              if ($hasSub) {
                $where = ['parent_id'];
                $section->parent_id = $row['id'];

                $child = $section->showAllChild();
                if ($role_id == 1 || count($sectionChild) > 0) {
              ?>
                <div class="submenu">
                  <ul class="submenu-group">
                    <?php
                    while ($row1 = $child->fetch(PDO::FETCH_ASSOC)) {
                      if ($role_id == 1 ||  in_array($row1['id'], $sectionChild)) {

                        $display = '' ;
                        if($row1['show_menu']==0){
                            $display = 'style="display:none;"';
                        }

                        $active1 = "";

                        extract($row1);

                        if ($page == $row1['link']) {
                          $active1 = "active";
                        }

                    ?>
                        <li class="submenu-item topbar <?= $active1 ?>" <?=$display?>>
                          <a href="index.php?p=<?= $row1['link'] ?>" class="submenu-link">
                            <i class="bi bi-<?= $row1['icon'] ?>"></i>
                            <span>
                              <?php
                              if ($lang == "en") {
                                echo $row1['label'];
                              } else {
                                $locale_label = strtolower($row1['label']);
                                $locale_label = str_replace(" ", "_", $locale_label);
                                $locale_label = "label_$locale_label";
                                $section_label = $$locale_label;
                                echo $section_label;
                              }
                              ?>
                            </span></a>
                        </li>

                    <?php
                      }
                    }
                    ?>
                  </ul>
                </div>
              <?php
                }
              }
              ?>
            </li>

        <?php
          }
        }
        ?>
      </ul>
    </div>
  </div>