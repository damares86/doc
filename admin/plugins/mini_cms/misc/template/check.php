<?php

$mc->name = 'maintenance' ;
$mc->table = 'mc_settings' ;
$mc_set = $mc->showAllWhere('id',['name']);
$maintenance = $mc_set->fetch(PDO::FETCH_ASSOC);
extract($maintenance);

require "admin/core/site.php";

if(($maintenance['value']==1)&&(!isset($_SESSION['loggedin']))&&($file!="login.php")){
    ?>
</head>

<body>
    <div id="siteContainer">
        <div id="bottomContainer" class="mt-5 p-5"> 
            <div style="text-align:center;" class="mt-5">
                <img src="admin/assets/images/logo/damares.png" style="width:15%; margin-bottom:3em;">
            </div>
            <h1 style="text-align:center; font-size:3em;">Site under maintenance</h1>
            <br>
            <h1 style="text-align:center; font-size:3em;">Sito in manutenzione</h1>
            <br>
            <div class="text-center my-5" style="text-align:center;" >
                <p class="copyright" style="font-size:0.7em;">
                        <img class="align-middle" src="uploads/img/logo_mc_rid.png"><br><br>by <br><br>
                        <a href="https://www.dmweblab.com"><img class="align-middle" src="admin/assets/images/logo/dmweblab_logo.png"></a>
                    </p>
               
            </div>
        </div>
    </div>
</body>
</html>


    <?php
    exit;
}


if(!in_array($_SERVER['SERVER_NAME'],$site)){
    ?>
    </head>
    
    <body>
        <div id="siteContainer">
            <div id="bottomContainer" class="mt-5 p-5">  
                <h1 style="text-align:center; font-size:3em;">Licenza non valida</h1>
                <br><br>
                <h1 style="text-align:center; font-size:3em;">Invalid license</h1>
            </div>
        </div>
    </body>
    </html>
    
    
        <?php
        exit;
}

?>