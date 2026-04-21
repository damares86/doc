<?php
// $summernote = true;
?>

<style>
    .row.page {
        display: none
    }
</style>

<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3><?=$addpopup_header?></h3>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php"><?= $common_dashboard ?></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?=$addpopup_header?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<br>



<section class="section">
    <div class="row">
        <div class="col-md-10 col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="card-title"><?=$addpopup_title ?></h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="core/mngPopup.php" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label><?=$addpopup_name?> <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="form-check mandatory">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="<?=$addpopup_name?>" name="title" data-parsley-required="true" />

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-12 my-3">
                                        <textarea class="tiny" name="content"></textarea>
                                    </div>


                                    <div class="col-md-3 my-3">
                                        <label><?=$addpopup_onpage?> <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9 my-3">
                                        <div class="form-group">
                                            <div class="form-check mandatory">
                                                <div class="position-relative">
                                                    <fieldset class="form-group">
                                                        <select class="form-select w-75" name="page_id">
                                                            <?php
                                                            $mc->table = 'mc_pages';
                                                            $stmt = $mc->showAll('page_name');
                                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                                extract($row);

                                                                $str = $row['page_name'];
                                                                $str = str_replace('_', ' ', $str);
                                                                $str = ucfirst($str);

                                                            ?>
                                                                <option value="<?= $row['id'] ?>"><?= $str ?></option>
                                                            <?php
                                                            }
                                                            ?>

                                                        </select>

                                                    </fieldset>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 my-3">
                                        <label><?=$addpopup_category?> <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9 my-3">
                                        <div class="form-group">
                                            <div class="form-check mandatory">
                                                <div class="position-relative">
                                                    <fieldset class="form-group">
                                                        <select class="form-select w-75" name="cat_id">
                                                            <?php
                                                            $mc->table = 'mc_popup_cat';
                                                            $stmt1 = $mc->showAll('category');
                                                            while ($row1= $stmt1->fetch(PDO::FETCH_ASSOC)) {
                                                                extract($row1);
                                                            ?>
                                                                <option value="<?=$row1['id']?>"><?=$row1['category']?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>

                                                    </fieldset>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="operation" value="add">
                                    <input type="hidden" name="origin" value="addPopup">


                                    <div class="col-12 mt-3 d-flex justify-content-end">
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
        <div class="col-md-2 col-12">
            <div class="card shadow">
                <h4 class="card-title px-4 pt-3"><?= $common_info ?></h4>
                <div class="card-content px-5 pb-4">
                    <ul>
                        <li><a href="https://www.dmweblab.com/portal/manual.php?prod=2&page=16" target="_blank"><?= $common_see_guide ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>