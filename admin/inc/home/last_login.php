<?php

declare(strict_types=1);

?>
<div class="card-header">
    <h4><?= htmlspecialchars((string) ($last_login_title ?? 'Recent Logins'), ENT_QUOTES, 'UTF-8') ?></h4>
</div>
<div class="card-content pb-4">
    <?php
    $lastLog = $account->getLastLogin();
    if ($lastLog instanceof PDOStatement) {
        while ($row = $lastLog->fetch(PDO::FETCH_ASSOC)) {
            $avatar = !empty($row['avatar']) ? (string) $row['avatar'] : 'default.png';
            $uName = (string) ($row['username'] ?? '');
            $lLogin = (string) ($row['last_login'] ?? '');
            ?>
            <div class="recent-message d-flex px-4 py-3">
                <div class="avatar avatar-lg">
                    <img src="uploads/avatar/<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar" />
                </div>
                <div class="name ms-4">
                    <h5 class="mb-1"><?= htmlspecialchars($uName, ENT_QUOTES, 'UTF-8') ?></h5>
                    <h6 class="text-muted mb-0">Log: <?= htmlspecialchars($lLogin, ENT_QUOTES, 'UTF-8') ?></h6>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>