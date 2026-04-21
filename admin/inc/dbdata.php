<?php
// require 'vendor/autoload.php';		// If installed via composer
// $debug = new \bdk\Debug(array(
// 	'collect' => true,
// 	'output' => true,
// ));

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>damares - configure your backend</title>

    <link rel="stylesheet" href="../admin/assets/css/main/app.css" />
    <link rel="stylesheet" href="../admin/assets/css/pages/auth.css" />
    <link rel="stylesheet" href="../admin/assets/css/main/app-dark.css" />
    <link rel="stylesheet" href="../admin/assets/css/custom.css" />
    <link
      rel="shortcut icon"
      href="../admin/assets/images/logo/favicon.svg"
      type="image/x-icon"
    />
    <link
      rel="shortcut icon"
      href="../admin/assets/images/logo/favicon.png"
      type="image/png"
    />

    <link rel="stylesheet" href=../admin/assets/css/shared/iconly.css" />

    <!--
    ##############    Damares    ###############
    #                                          #
    #    A backend project by DM WebLab        #
    #   Website: https://www.dmweblab.com      #
    #   GitHub: https://github.com/damares86   #
    #                                          #
    ############################################
    -->
    
  </head>

  <body>
    <div id="auth">
      <div class="row h-100">
        <div class="col-lg-7 col-12">
          <div id="auth-left">
            <div class="auth-logo">
                <img src="../admin/assets/images/logo/damares_logo.png" alt="Logo"
              />
            </div>
            <h1 class="auth-title">Insert your database data</h1>
            <form class="form form-horizontal" action="../admin/core/configdb.php" method="POST"  data-parsley-validate>
                <div class="form-body">
                  <div class="row">
                    <div class="col-md-4">
                      <label>Server host (es. localhost) <span class="text-danger font-weight-bold">*</span></label>
                    </div>
                    <div class="col-md-8 form-group">

                      <div class="form-check mandatory">
                        <input
                          type="text"
                          id="host"
                          class="form-control"
                          name="host"
                          placeholder="Host"
                          data-parsley-required="true"
                        />
                      </div>
                    </div>

                    <div class="col-md-4">
                      <label>Database name <span class="text-danger font-weight-bold">*</span></label>
                    </div>
                    <div class="col-md-8 form-group">

                      <div class="form-check mandatory">
                        <input
                          type="text"
                          id="dbname"
                          class="form-control"
                          name="dbname"
                          placeholder="Database name"
                          data-parsley-required="true"
                        />
                      </div>
                    </div>
                  
                    <div class="col-md-4">
                      <label>Database user <span class="text-danger font-weight-bold">*</span></label>
                    </div>
                    <div class="col-md-8 form-group">

                      <div class="form-check mandatory">
                        <input
                          type="text"
                          id="username"
                          class="form-control"
                          name="username"
                          placeholder="Database user"
                          data-parsley-required="true"
                        />
                      </div>
                    </div>

                    <div class="col-md-4">
                      <label>Database password <span class="text-danger font-weight-bold">*</span></label>
                    </div>
                    <div class="col-md-8 form-group">

                      <div class="form-check mandatory">
                        <input
                          type="password"
                          id="db_password"
                          class="form-control"
                          name="db_password"
                          placeholder="Database password"
                          data-parsley-required="true"
                        />
                      </div>
                    </div>

                    <div class="col-md-4">
                      <label>Table prefix (if you have more website)</label>
                    </div>
                    <div class="col-md-8 form-group">
                      <div class="form-check mandatory">

                        <input
                          type="text"
                          id="prefix"
                          class="form-control"
                          name="prefix"
                          placeholder="Table prefix"
                        />
                      </div>
                    </div>

                    <div class="col-md-4">
                      <label>Your admin email <span class="text-danger font-weight-bold">*</span></label>
                    </div>
                    <div class="col-md-8 form-group">
                      <div class="form-check mandatory">

                        <input
                          type="text"
                          id="email"
                          class="form-control"
                          name="email"
                          placeholder="Your email"
                          data-parsley-required="true"
                        />
                      </div>
                    </div>

                    <div class="col-md-4">
                      <label>Your admin password <span class="text-danger font-weight-bold">*</span></label>
                    </div>
                    <div class="col-md-8 form-group">

                      <div class="form-check mandatory">
                        <input
                          type="password"
                          id="password"
                          class="form-control"
                          name="password"
                          placeholder="Your password"
                          data-parsley-required="true"
                        />
                      </div>
                    </div>

                    <div class="col-sm-12 d-flex justify-content-end">
                      <button
                        type="submit"
                        class="btn btn-primary me-1 mb-1"
                      >
                        Submit
                      </button>
                    </div>
                  </div>
                </div>
              </form>           
          </div>
        </div>
        <div class="col-lg-5 d-none d-lg-block">
          <div id="auth-right">
              <img src="../login/img/visual.jpg">
          </div>
        </div>
      </div>
    </div>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/app.js"></script>

    <script src="assets/extensions/jquery/jquery.min.js"></script>
    <script src="assets/extensions/parsleyjs/parsley.min.js"></script>
    <script src="assets/js/pages/parsley.js"></script>
  </body>
</html>

