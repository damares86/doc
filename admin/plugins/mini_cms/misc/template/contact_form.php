<?php

$json_file = 'admin/inc/pages/2.json';
$data = file_get_contents($json_file);
$json_arr = json_decode($data, true);

?>
<div id="content" class="container">
    <?php
    $counter = $page_counter;
    
    $quote_counter = 0 ;

    if (isset($_SESSION['loggedin'])) {
    ?>
        <div class="text-right">

            <a href="admin/index.php?p=editDefaultPage&idToMod=<?= $page_id ?>&count=<?= $counter ?>" class="btn btn-primary btn-sm"><b>Modifica</b></a>
        </div>
    <?php
    }
    ?>
    <div id="contact">
        <div class="row">
            <div class="col-md-6">
                <div class="row address">
                    <div class="col-12">
                        <?php
                        echo $json_arr[1]['block1'];
                        ?>
                    </div>
                    <div class="col-12 maps">
                        <?php
                        echo $json_arr[2]['block2'];
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-body">
                    <?php
                    require "admin/template/inc/alert.php";

                    $send = "mngMail";
                    if ($recap) {
                        $send = "mngMailRecap";
                    }


                    ?>

                    <h3 class="mb-3"><?=$contact_title ?></h3>

                    <form id="contactForm" method="POST" action="admin/core/<?= $send ?>.php" data-parsley-validate>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="name" type="text" placeholder="<?=$contact_name?>"data-parsley-required="true" />
                            <label for="name"><?=$contact_name?> <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" type="email" name="email" placeholder="mail@mail.com"data-parsley-required="true" />
                            <label for="email"><?=$contact_email?> <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-floating mb-3">
                            <select name="contact" class="form-control">
                                <?php
                                $mc->table = "mc_contacts";

                                $stmt1 = $mc->showAll('id');
                                while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row1);

                                    echo "<option value='" . $row1['email'] . "'>" . $row1['label'] . "</option>";
                                }
                                ?>
                            </select>
                            <label for="contact"><?=$contact_choose ?>: <span class="text-danger">*</span></label>

                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="subject" type="text" placeholder="<?=$contact_subject ?>..." data-parsley-required="true" />
                            <label for="name"><?=$contact_subject ?> <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-group">
                            <label for="message"><?=$contact_message?> <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message" type="text" name="message" placeholder="<?=$contact_message?>..." style="height: 10rem" data-parsley-required="true"></textarea>
                        </div>

                        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

                        <br>

                        <div class="form-group m-0">
                            <button type="submit"  id="submitButton"  class="btn btn-primary btn-block">
                                <?= $common_submit ?>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
