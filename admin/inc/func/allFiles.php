<?php

declare(strict_types=1);

$allfiles = $file->showAll('id');

$plugin->pluginname = 'file_to_rate';
$fileToRate = false;
if ($plugin->itemExists('pluginname') && (int) $plugin->isActive() === 1) {
    $fileToRate = true;
}
?>

<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3><?= htmlspecialchars((string) ($file_all_header ?? 'File Manager'), ENT_QUOTES, 'UTF-8') ?></h3>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.php"><?= htmlspecialchars((string) ($common_dashboard ?? 'Dashboard'), ENT_QUOTES, 'UTF-8') ?></a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <?= htmlspecialchars((string) ($file_all_header ?? 'File Manager'), ENT_QUOTES, 'UTF-8') ?>
          </li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<br>

<!-- Basic Tables start -->
<section class="section">
  <div class="card shadow">
    <div class="card-body vh-100">
      <iframe src="core/tinyfilemanager.php?lang=<?= urlencode((string) ($lang ?? 'en')) ?>" style="width: 100%; height:100%; border: none;">
      </iframe>
    </div>
  </div>
</section>