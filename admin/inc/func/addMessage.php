<?php

declare(strict_types=1);

$doc->table = 'doc_messages';
$stmtMsg = $doc->showAll('id');
$rowMsg = $stmtMsg ? $stmtMsg->fetch(PDO::FETCH_ASSOC) : [];
$msgText = (string) ($rowMsg['msg'] ?? '');
$startTime = htmlspecialchars((string) ($rowMsg['start_time'] ?? ''), ENT_QUOTES, 'UTF-8');
$endTime = htmlspecialchars((string) ($rowMsg['end_time'] ?? ''), ENT_QUOTES, 'UTF-8');
?>

<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Avviso in homepage</h3>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php"><?= htmlspecialchars((string) ($common_dashboard ?? 'Dashboard'), ENT_QUOTES, 'UTF-8') ?></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Avviso in homepage
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<br>

<section class="section">
    <div class="row">
        <div class="col-md-8 col-12">
            <div class="card shadow">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="core/mngBooking.php" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label><b>Periodo del messaggio</b> </label>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Data inizio</label>
                                            <input type="date" class="form-control" name="start" value="<?= $startTime ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Data fine</label>
                                            <input type="date" class="form-control" name="end" value="<?= $endTime ?>">
                                        </div>
                                    </div>

                                    <div class="col-12 border-top mt-3 pt-3">
                                        <label>Testo del messaggio <span class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-12 pb-3 mt-2">
                                        <textarea class="tiny" name="msg"><?= htmlspecialchars($msgText, ENT_QUOTES, 'UTF-8') ?></textarea>
                                    </div>

                                    <input type="hidden" name="operation" value="msg">
                                    <input type="hidden" name="origin" value="addMessage">

                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1 shadow">
                                            <?= htmlspecialchars((string) ($common_submit ?? 'Invia'), ENT_QUOTES, 'UTF-8') ?>
                                        </button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1 shadow">
                                            <?= htmlspecialchars((string) ($common_reset ?? 'Reset'), ENT_QUOTES, 'UTF-8') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card shadow">
                <h4 class="card-title px-4 pt-3"><?= htmlspecialchars((string) ($common_info ?? 'Info'), ENT_QUOTES, 'UTF-8') ?></h4>
                <div class="card-content px-5 pb-4">
                    <ul>
                        <li><a href="http://dmweblab.com/portal/manual.php?prod=1&page=6" target="_blank"><?= htmlspecialchars((string) ($common_see_guide ?? 'Vedi guida'), ENT_QUOTES, 'UTF-8') ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>