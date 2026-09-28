<?php

declare(strict_types=1);

?>
<div class="card-header">
    <h4><?= htmlspecialchars((string) ($welcome_title ?? 'Welcome'), ENT_QUOTES, 'UTF-8') ?></h4>
</div>
<div class="card-content p-4">
    <?= htmlspecialchars((string) ($welcome_desc1 ?? 'Welcome to Damares CMS. '), ENT_QUOTES, 'UTF-8') ?>
    <a href="https://www.dmweblab.com" target="_blank"><?= htmlspecialchars((string) ($welcome_desc2 ?? 'DM WebLab'), ENT_QUOTES, 'UTF-8') ?></a>.
</div>