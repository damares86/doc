<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

//  pages

$login_reg = "Don't have an account?" ;
$login_signup = "Register" ;
$reg_title = "Register" ;
$reg_desc = "Input your data to register to our website.";
$reg_account = "Already have an account?" ;
$reg_account_button = "Log in";
$reg_noreg = "<-- No pending registration with this data. Please retry or contact us ";
$reg_errreg = "Error during registration process. Please retry or contact us";
$reg_token = "Registration request expired" ;
$reg_back = "<-- Go back to register page" ;
$reg_conf_psw_ph = "Confirm Password";
$reg_agree_1 = "I agree with the ";
$reg_agree_2 = "terms";
$reg_agree_3 = " and the ";
$reg_agree_4 = "privacy policy";

// settings

$regset_title = "Default role for registered accounts" ;


// block mail

$reg_mail_subject = "Confirm registration - damares" ;
$reg_block1 ='<html><body>';
$reg_block1.= '<p>Dear user,</p>';
$reg_block1.='<p>Please click on the following link to complete your registration.</p>';
$reg_block1.='<p>-------------------------------------------------------------</p>';
$reg_block2='<p>If the link doesn\' work, copy the entire link into your browser.
The link will expire after 1 hour for security reason.</p>';
$reg_block2.='<p>If you did not request this registration, no action 
is needed, this request will expire and there will be no effects.</p>';   	
$reg_block2.='<p>Thanks,</p>';
$reg_block2.='<p>Damares</p>';
$reg_block2.='</body></html>';


//  msg alert

$msg_sentRegMail = "An email has been sent to your email address. Follow the instruction to complete the registration" ;
$msg_accountReg = "Account successfully registered. Login with your email and password" ;
$msg_regRoleUpdated = "Default role updated";


//  err alert

$err_mailExists = "An account with this email already exists." ;
$err_noRegDelete = "Some errors during registration process. Please contact us." ;
$err_errSendMail = "Error while sending you the registration email. Please contact us." ;
$err_noReg = "Error on registration request. Please contact us" ;
$err_errRegRequest = "No pending registration request or registration request expired" ;
$err_accountNoReg = "Account not registered" ;
$err_regRoleNotUpdated = "Default role not updated" ;

?>