<?php
$allfiles = $file->showAll('id');

$plugin->pluginname = "file_to_rate";
$fileToRate = false;
if ($plugin->itemExists('pluginname') && $plugin->isActive() == 1) {
  $fileToRate = true;
}

?>

<div class="page-title">
  <div class="row">
    <div class="col-12 col-md-6 order-md-1 order-last">
      <h3><?= $file_all_header ?></h3>
    </div>
    <div class="col-12 col-md-6 order-md-2 order-first">
      <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.php"><?= $common_dashboard ?></a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            <?= $file_all_header ?>
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
      <iframe src='core/tinyfilemanager.php?lang=<?php echo urlencode($lang); ?>' style="width: 100%; height:100%;">
      </iframe>
    </div>
  </div>
</section>