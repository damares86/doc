<?php

require "inc/funcHeader.php";
$setting->name="reg_role";
$stmt=$setting->showAllWhere('id',['name']);
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$reg_role = $row['value'];

?>

<section class="section">
    <div class="row">
        <div class="col-md-8 col-12">
            <div class="card shadow">
                <div class="card-header">
                <h4 class="card-title"><?=$regset_title?></h4>
                </div>
                <div class="card-content">
                <div class="card-body">
                    <form class="form form-horizontal" action="core/mngRegister.php" method="POST"  enctype="multipart/form-data" data-parsley-validate>
                    <div class="form-body">
                        <div class="row">
                        <div class="col-md-3">
                            <label><?=$common_role?> <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="form-check mandatory">
                                    <div class="position-relative">
                                    <fieldset class="form-group">
                                        <select
                                        class="form-select"
                                        id="role"
                                        name="role"
                                        >
                                        <?php

                                        

                                            $stmt = $role->showAll('id');

                                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                $selected = "" ;
                                                if($row['id']>1){
                                                
                                                    if($reg_role == $row['rolename']){
                                                        $selected = "selected" ;
                                                    }
                                        ?>

                                            <option value="<?=$row['id']?>" <?=$selected?>><?=$row['rolename']?></option>

                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="reg_role" value="1">
             
                        <div class="col-12 d-flex justify-content-end">
                            <button
                            type="submit"
                            class="btn btn-primary me-1 mb-1 shadow"
                            >
                            <?=$common_submit?>
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
                <div class="card-header">
                    <h4 class="card-title"><?=$common_info?></h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>