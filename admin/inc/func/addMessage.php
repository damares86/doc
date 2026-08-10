<?php
    $doc->table = 'doc_messages';
    $stmt_msg = $doc->showAll('id');
    $row_msg = $stmt_msg->fetch(PDO::FETCH_ASSOC);
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
                        <a href="index.php"><?= $common_dashboard ?></a>
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
                                            <input type="date" class="form-control" name="start" value="<?= $row_msg['start_time'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Data fine</label>
                                            <input type="date" class="form-control" name="end" value="<?= $row_msg['end_time'] ?>">
                                        </div>
                                    </div>

                                    <div class="col-12 border-top mt-3 pt-3">
                                        <label>Testo del messaggio <span class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-12 pb-3 mt-2">
                                        <textarea class="tiny" name="msg"> <?= $row_msg['msg'] ?></textarea>
                                    </div>


                                    <input type="hidden" name="operation" value="msg">
                                    <input type="hidden" name="origin" value="addMessage">

                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1 shadow">
                                            <?= $common_submit ?>
                                        </button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1 shadow">
                                            <?= $common_reset ?>
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
                        <li><a href="http://dmweblab.com/portal/manual.php?prod=1&page=6" target="_blank"><?= $common_see_guide ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>