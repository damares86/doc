<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################


// LOGIN 

$login_titlebar = "Login";
$login_title = "Login" ;
$login_desc = "Effettua il login con i dati forniti durante la registrazione.";
$login_button = "Login";
$login_remember = "Ricordami";
$login_forgot = "Password dimenticata?";

//  RESET

$forgot_titlebar = "Reset password";
$forgot_title = "Password dimenticata";
$forgot_desc = "Scrivi la tua mail e ti manderemo un link per resettare la password.";
$forgot_button = "Invia";
$forgot_back = "Nessuna richiesta di reset password presente. Torna al form per la richiesta di reset.";
$forgot_choose = "Scegli la nuova password" ;
$forgot_token = "Link scaduto";

//  ALERT: ERR 

$err_noResetDelete = "Errore nel reset della password. Contattaci" ;
$err_mailNotReg = "Mail non registrata" ;
$errSendMail = "Errore nell'invio della mail. Contattaci" ;
$err_noReset = "Erorre nel reset della password. Contattaci" ;
$err_errResetRequest = "Nessuna richiesta di reset in sospeso o link scaduto" ;
$err_keyDelErr = "Password modificata, ma c'è stato qualche problema. Contattaci" ;
$err_pswEditErr = "Password non modificata. Contattaci" ;
$err_noLogin = "Devi essere loggato per vedere quella pagina<br>SUGGERIMENTO: se sei sicuro delle credenziali prova a selezionare 'Ricordami'" ;
$err_errUserPsw = "Username o password errati" ;

//  ALERT: MSG 

$msg_sentMail = "Email di reset inviata al tuo indirizzo mail" ;
$msg_newPass = "Password modificata" ;

//  RESET MAIL 

$block1 ='<html><body>';
$block1.='<p>Caro utente,</p>';
$block1.='<p>Clicca sul link per resettare la password.</p>';
$block1.='<p>-------------------------------------------------------------</p>';
$block2='<p>Se non funziona copia l\'intero link e incollalo nella barra del browser. Il link sarà valido solo per un\'ora.</p>';
$block2.='<p>Se non hai richiesto il reset della password non è necessaria nessuna azione.</p>';   	
$block2.='<p>Grazie,</p>';
$block2.='<p>Damares</p>';
$block2.='</body></html>';
