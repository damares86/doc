<?php




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
                            <a href="index.php"><?= $common_dashboard ?></a>
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
                                        <?php

                                        $days = [
                                            1 => "Lunedì",
                                            2 => "Martedì",
                                            3 => "Mercoledì",
                                            4 => "Giovedì",
                                            5 => "Venerdì"
                                        ];


                                        $doc->table = 'doc_timetable';

                                        $stmt_time = $doc->showAll('id');
                                        while ($row_time = $stmt_time->fetch(PDO::FETCH_ASSOC)) {

                                            extract($row_time);

                                            $day_name = $days[$row_time['weekday']];
                                        ?>
                                            <div class="col-md-3 border-bottom">
                                                <label><b><?= $day_name ?></b> </label>
                                            </div>
                                            <div class="col-md-3 border-bottom">
                                                <div class="form-group">
                                                    <label>Inizio</label>
                                                    <input type="time" class="form-control" name="start_<?= $row_time['weekday'] ?>" value="<?= $row_time['start_time'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3 border-bottom">
                                                <div class="form-group">
                                                    <label>Fine</label>
                                                    <input type="time" class="form-control" name="end_<?= $row_time['weekday'] ?>" value="<?= $row_time['end_time'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3 px-5 py-3 border-bottom">
                                                <div class="form-group">
                                                    <div class="position-relative">
                                                        <div class="form-check">
                                                            <div class="checkbox">
                                                                <?php 
                                                                $checked = '';
                                                                if($row_time['booking'] == 0){
                                                                    $checked = 'checked' ;
                                                                }
                                                                ?>
                                                                <input type="checkbox" id="checkbox1" name="booking_<?= $row_time['weekday']?>" class="form-check-input" <?=  $checked ?>>
                                                                <label for="checkbo1">&nbsp; &nbsp;Accesso libero</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>

                                        <input type="hidden" name="operation" value="timetable">

                                        <div class="col-12 d-flex justify-content-end mt-3">
                                            <button type="submit" class="btn btn-primary me-1 mb-1 shadow">
                                                <?= $common_submit ?>
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
                                        <?php
                                        $doc->table = "doc_settings";
                                        $doc->name = "slot_length";
                                        $stmt = $doc->showAllWhere('id', ['name']);
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $slot = $row['value'];
                                        ?>
                                        <div class="col-md-3">
                                            <label>Durata visite <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-check mandatory">
                                                    <div class="position-relative">
                                                        <input type="number" class="form-control" name="slot_length" data-parsley-required="true" value="<?= $slot ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            &nbsp;
                                        </div>

                                        <?php
                                        $doc->name = "email";
                                        $stmt = $doc->showAllWhere('id', ['name']);
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $email = $row['value'];
                                        ?>

                                        <div class="col-md-3 p-3">
                                            <label>Email per comunicazioni <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9 p-3">
                                            <div class="form-group has-icon-left">
                                                <div class="form-check mandatory">
                                                    <div class="position-relative">
                                                        <input type="email" class="form-control" placeholder="Email" id="first-name-icon" name="email" data-parsley-required="true" value="<?= $email ?>" />
                                                        <div class="form-control-icon">
                                                            <i class="bi bi-envelope"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="operation" value="settings">

                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1 shadow">
                                                <?= $common_submit ?>
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
                    <h4 class="card-title px-4 pt-3"><?= $common_info ?></h4>
                    <div class="card-content px-5 pb-4">
                        <ul>
                            <li><a href="http://dmweblab.com/portal/manual.php?prod=1&page=11" target="_blank"><?= $common_see_guide ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>