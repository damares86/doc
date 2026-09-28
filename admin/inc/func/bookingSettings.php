<?php

declare(strict_types=1);

if (!isset($doc) || !($doc instanceof Doc)) {
    $doc = new Doc($db);
    if (!empty($prefix)) {
        $doc->prx = $prefix . '_';
    }
}

$days = [
    1 => "Lunedì",
    2 => "Martedì",
    3 => "Mercoledì",
    4 => "Giovedì",
    5 => "Venerdì",
];

$doc->table = 'doc_timetable';
$stmtTime = $doc->showAll('weekday');
$timetableRows = $stmtTime ? $stmtTime->fetchAll(PDO::FETCH_ASSOC) : [];

$doc->table = 'doc_settings';
$doc->name = 'slot_length';
$stmtSlot = $doc->showAllWhere('id', ['name']);
$rowSlot = $stmtSlot ? $stmtSlot->fetch(PDO::FETCH_ASSOC) : null;
$slotLength = htmlspecialchars((string) ($rowSlot['value'] ?? '15'), ENT_QUOTES, 'UTF-8');

$doc->name = 'email';
$stmtEmail = $doc->showAllWhere('id', ['name']);
$rowEmail = $stmtEmail ? $stmtEmail->fetch(PDO::FETCH_ASSOC) : null;
$docEmail = htmlspecialchars((string) ($rowEmail['value'] ?? ''), ENT_QUOTES, 'UTF-8');
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3 class="d-inline">Impostazioni prenotazioni</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php"><?= htmlspecialchars((string) ($common_dashboard ?? 'Dashboard'), ENT_QUOTES, 'UTF-8') ?></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Impostazioni prenotazioni
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
                    <div class="card-header">
                        <h4 class="card-title">Orari ambulatorio</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" action="core/mngBooking.php" method="POST" data-parsley-validate>
                                <div class="form-body">
                                    <div class="row">
                                        <?php foreach ($timetableRows as $rowTime): ?>
                                            <?php
                                            $weekdayNum = (int) ($rowTime['weekday'] ?? 1);
                                            $dayName = $days[$weekdayNum] ?? "Giorno {$weekdayNum}";
                                            $startTime = htmlspecialchars((string) ($rowTime['start_time'] ?? '08:00'), ENT_QUOTES, 'UTF-8');
                                            $endTime = htmlspecialchars((string) ($rowTime['end_time'] ?? '12:00'), ENT_QUOTES, 'UTF-8');
                                            $checked = ((int) ($rowTime['booking'] ?? 1) === 0) ? 'checked' : '';
                                            ?>
                                            <div class="col-md-3 border-bottom py-2">
                                                <label><b><?= htmlspecialchars($dayName, ENT_QUOTES, 'UTF-8') ?></b></label>
                                            </div>
                                            <div class="col-md-3 border-bottom py-2">
                                                <div class="form-group">
                                                    <label>Inizio</label>
                                                    <input type="time" class="form-control" name="start_<?= $weekdayNum ?>" value="<?= $startTime ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3 border-bottom py-2">
                                                <div class="form-group">
                                                    <label>Fine</label>
                                                    <input type="time" class="form-control" name="end_<?= $weekdayNum ?>" value="<?= $endTime ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3 px-3 py-3 border-bottom">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" id="booking_<?= $weekdayNum ?>" name="booking_<?= $weekdayNum ?>" class="form-check-input" <?= $checked ?>>
                                                        <label class="form-check-label" for="booking_<?= $weekdayNum ?>">Accesso libero</label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                        <input type="hidden" name="operation" value="timetable">

                                        <div class="col-12 d-flex justify-content-end mt-3">
                                            <button type="submit" class="btn btn-primary me-1 mb-1 shadow">
                                                <?= htmlspecialchars((string) ($common_submit ?? 'Invia'), ENT_QUOTES, 'UTF-8') ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="card-title">Impostazioni</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" action="core/mngBooking.php" method="POST" data-parsley-validate>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Durata visite (minuti) <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-check mandatory">
                                                    <input type="number" class="form-control" name="slot_length" data-parsley-required="true" value="<?= $slotLength ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            &nbsp;
                                        </div>

                                        <div class="col-md-3 p-3">
                                            <label>Email per comunicazioni <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9 p-3">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="email" class="form-control" placeholder="Email" id="first-name-icon" name="email" data-parsley-required="true" value="<?= $docEmail ?>" />
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-envelope"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="operation" value="settings">

                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1 shadow">
                                                <?= htmlspecialchars((string) ($common_submit ?? 'Invia'), ENT_QUOTES, 'UTF-8') ?>
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
                            <li><a href="http://dmweblab.com/portal/manual.php?prod=1&page=11" target="_blank"><?= htmlspecialchars((string) ($common_see_guide ?? 'Vedi guida'), ENT_QUOTES, 'UTF-8') ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>