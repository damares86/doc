
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3 class="d-inline"><?=$addgallery_header?></h3>
                <a href="index.php?p=allGalleries" class="btn icon btn-info shadow mx-3 px-3">
                    <i class="bi bi-arrow-left-circle"></i> &nbsp; <?= $common_back ?>
                </a>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php"><?= $common_dashboard ?></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?=$addgallery_header?>
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
                            <h4 class="card-title"><?=$addgallery_title?></b></h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form form-horizontal" action="core/mngGallery.php" method="POST" enctype="multipart/form-data"  data-parsley-validate>
                                    <div class="form-body">
                                        <div class="row">
                                        <div class="col-md-3">
                                        <label><?=$addgallery_name?> <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="form-check mandatory">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="<?=$addgallery_name?>" name="gallery_name" data-parsley-required="true" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                            <label><?=$addgallery_addphoto?> <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <div class="form-check mandatory">
                                                    <div class="position-relative">
                                                        <input class="form-control" type="file" name="myfile[]" multiple data-parsley-required="true" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                            
                                        <input type="hidden" name="operation" value="add">
                                        <input type="hidden" name="origin" value="addGallery">

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
                                <li><a href="https://www.dmweblab.com/portal/manual.php?prod=2&page=10" target="_blank"><?= $common_see_guide ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

