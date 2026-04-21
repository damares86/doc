<?php

?>

<div class="card-header">
<h4><?=$last_login_title?></h4>
</div>
<div class="card-content pb-4">

<?php
    $lastLog = $account->getLastLogin();
    foreach($lastLog as $row){
?>
    
    <div class="recent-message d-flex px-4 py-3">
    <div class="avatar avatar-lg">
        <img src="uploads/avatar/<?=$row['avatar']?>" />
    </div>
    <div class="name ms-4">
        <h5 class="mb-1"><?=$row['username']?></h5>
        <h6 class="text-muted mb-0">Log: <?=$row['last_login']?></h6>
    </div>
    </div>

<?php
    }
?>

</div>