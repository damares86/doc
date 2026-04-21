<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################


//////  LOGIN  //////

$login_titlebar = "Login";
$login_title = "Login" ;
$login_desc = "Log in with your data that you entered during registration.";
$login_button = "Login";
$login_remember = "Keep me logged in";
$login_forgot = "Forgot password?";


//////  RESET  //////

$forgot_titlebar = "Reset password";
$forgot_title = "Forgot Password";
$forgot_desc = "Input your email and we will send you reset password link.";
$forgot_button = "Send";
$forgot_back = "No reset request present. Go back to the reset request form.";
$forgot_choose = "Choose the new Password" ;
$forgot_token = "Reset request expired";

//////  ALERT: ERR  //////

$err_noResetDelete = "Error during reset process. Please contact us" ;
$err_mailNotReg = "Mail not registered" ;
$errSendMail = "Error sending reset email. Please contact us" ;
$err_noReset = "Error during reset process. Please contact us" ;
$err_errResetRequest = "No reset request present or request expired" ;
$err_keyDelErr = "Password modified, but there where some problems. Please contatct us" ;
$err_pswEditErr = "Password not modified. Please contact us" ;
$err_noLogin = "You must be logged in to access that page.<br>HINT: if you are sure of the credentials try to check 'Remember me'" ;
$err_errUserPsw = "Username or password wrong" ;

//////  ALERT: MSG  //////

$msg_sentMail = "Reset email sent to your address" ;
$msg_newPass = "Password changed successfully" ;

//////  RESET MAIL //////

$block1 ='<html><body>';
$block1.='<p>Dear user,</p>';
$block1.='<p>Please click on the following link to reset your password.</p>';
$block1.='<p>-------------------------------------------------------------</p>';
$block2='<p>Please be sure to copy the entire link into your browser.
The link will expire after 1 hour for security reason.</p>';
$block2.='<p>If you did not request this forgotten password email, no action 
is needed, your password will not be reset. However, you may want to log into 
your account and change your security password as someone may have guessed it.</p>';   	
$block2.='<p>Thanks,</p>';
$block2.='<p>Damares</p>';
$block2.='</body></html>';
