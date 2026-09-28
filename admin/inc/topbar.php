<?php

declare(strict_types=1);

$userAvatar = htmlspecialchars((string) ($_SESSION['avatar'] ?? 'default.png'), ENT_QUOTES, 'UTF-8');
$userName = htmlspecialchars((string) ($_SESSION['username'] ?? ''), ENT_QUOTES, 'UTF-8');
$roleName = htmlspecialchars((string) ($_SESSION['rolename'] ?? ''), ENT_QUOTES, 'UTF-8');
?>
<header class="mb-5">
  <div class="header-top">
    <div class="container">
      <div class="logo">
        <a href="index.php"><img src="assets/images/logo/damares_logo.png" alt="Logo"></a>
      </div>
      <div class="header-top-right">

        <div class="dropdown">
          <a href="#" id="topbarUserDropdown" class="user-dropdown d-flex align-items-center dropend dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="avatar avatar-md2">
              <img src="uploads/avatar/<?= $userAvatar ?>" alt="Avatar">
            </div>
            <div class="text">
              <h6 class="user-dropdown-name"><?= $userName ?></h6>
              <p class="user-dropdown-status text-sm text-muted"><?= $roleName ?></p>
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="topbarUserDropdown">
            <li><a class="dropdown-item" href="index.php?p=editProfile"><?= htmlspecialchars((string) ($common_profile ?? 'Profile'), ENT_QUOTES, 'UTF-8') ?></a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="core/logout.php"><?= htmlspecialchars((string) ($common_logout ?? 'Logout'), ENT_QUOTES, 'UTF-8') ?></a></li>
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
        $role_id = (int) ($_SESSION['role_id'] ?? 0);
        $rolessection->table = 'roles_section_child';
        $rolessection->role_id = $role_id;
        $permissionChild = $rolessection->showAllWhere('id', ['role_id']);
        $permChildArr = $permissionChild ? $permissionChild->fetch(PDO::FETCH_ASSOC) : null;
        $sectionChild = !empty($permChildArr['section_id']) ? explode(',', (string) $permChildArr['section_id']) : [];

        $rolessection->role_id = $role_id;
        $rolessection->table = 'roles_section';
        $permissionParent = $rolessection->showAllWhere('id', ['role_id']);
        $row3 = $permissionParent ? $permissionParent->fetch(PDO::FETCH_ASSOC) : null;
        $sectionParent = !empty($row3['section_id']) ? explode(',', (string) $row3['section_id']) : [];

        $section->table = 'section_parent';
        $stmt = $section->showAll('id');

        if ($stmt instanceof PDOStatement) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $hasSub = '';
                $active = '';
                $link = ($row['link'] === 'index') ? '' : '?p=' . urlencode((string) $row['link']);
                $parent_id = (int) $row['id'];

                $section->table = 'section_child';
                $section->parent_id = $parent_id;
                $child = $section->showAllWhere('id', ['parent_id']);
                $countChildPermissions = 0;

                if ($child instanceof PDOStatement) {
                    while ($row2 = $child->fetch(PDO::FETCH_ASSOC)) {
                        if (in_array((string) $row2['id'], $sectionChild, true)) {
                            $countChildPermissions++;
                        }
                    }
                }

                $disabled = '';
                if ($section->countChild($parent_id) > 0 && $countChildPermissions > 0) {
                    $hasSub = 'has-sub';
                    $link = '#';
                    $disabled = ' disabled';
                }

                if (($page ?? '') === $row['link']) {
                    $active = 'active';
                }

                if ($role_id === 1 || in_array((string) $row['id'], $sectionParent, true)) {
                    $labelDisplay = (string) $row['label'];
                    if (($lang ?? 'en') !== 'en') {
                        $locale_label = 'label_' . str_replace(' ', '_', strtolower((string) $row['label']));
                        if (isset($$locale_label)) {
                            $labelDisplay = (string) $$locale_label;
                        }
                    }
                    ?>
                    <li class="menu-item <?= $active ?> <?= $hasSub ?>">
                      <a href="index.php<?= $link ?>" class="menu-link <?= $disabled ?>">
                        <i class="bi bi-<?= htmlspecialchars((string) ($row['icon'] ?? 'circle'), ENT_QUOTES, 'UTF-8') ?>"></i>
                        <span><?= htmlspecialchars($labelDisplay, ENT_QUOTES, 'UTF-8') ?></span>
                      </a>
                      <?php if ($hasSub): ?>
                        <div class="submenu">
                          <ul class="submenu-group">
                            <?php
                            $section->parent_id = $parent_id;
                            $child = $section->showAllChild();
                            if ($child instanceof PDOStatement) {
                                while ($row1 = $child->fetch(PDO::FETCH_ASSOC)) {
                                    if ($role_id === 1 || in_array((string) $row1['id'], $sectionChild, true)) {
                                        $display = ((int) ($row1['show_menu'] ?? 1) === 0) ? 'style="display:none;"' : '';
                                        $active1 = (($page ?? '') === $row1['link']) ? 'active' : '';

                                        $childLabel = (string) $row1['label'];
                                        if (($lang ?? 'en') !== 'en') {
                                            $locale_label = 'label_' . str_replace(' ', '_', strtolower((string) $row1['label']));
                                            if (isset($$locale_label)) {
                                                $childLabel = (string) $$locale_label;
                                            }
                                        }
                                        ?>
                                        <li class="submenu-item topbar <?= $active1 ?>" <?= $display ?>>
                                          <a href="index.php?p=<?= urlencode((string) $row1['link']) ?>" class="submenu-link">
                                            <i class="bi bi-<?= htmlspecialchars((string) ($row1['icon'] ?? 'circle'), ENT_QUOTES, 'UTF-8') ?>"></i>
                                            <span><?= htmlspecialchars($childLabel, ENT_QUOTES, 'UTF-8') ?></span>
                                          </a>
                                        </li>
                                        <?php
                                    }
                                }
                            }
                            ?>
                          </ul>
                        </div>
                      <?php endif; ?>
                    </li>
                    <?php
                }
            }
        }
        ?>
      </ul>
    </div>
  </nav>
</header>