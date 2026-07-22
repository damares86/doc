<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

$reg_mail_subject = "Prenotazione visita - ";
$doc_block1 = '<html><body>';
$doc_block1 .= '<p>Buongiorno</p>';
$doc_block1 .= '<p>Si conferma la prenotazione della visita. Di seguito i dati:</p>';

$doc_block2 = '<p>In caso non potesse più venire in visita, chiedo gentilmente di eliminare la prontazione
cliccando sul seguente link.<br>Se il link non dovesse funzionare, può copiarlo nella barra degli indirizzi del browser.</p>';

$doc_block3 = '<p>Grazie,</p>';
$doc_block3 .= '<p>Dottoressa Crosetto Maria Chiara</p>';
$doc_block3 .= '<p>-----------------------------------------------------------</p>';
$doc_block3 .= '<p>ATTENZIONE: questa è una mail automatica, non rispondere a questa mail. Per contattare la dottoressa andare sul <a href="doc.dmweblab.com">sito web</a>.</p>';
$doc_block3 .= '</body></html>';


// msg

$msg_timetableUpdate = "Orari dello studio medico aggiornati";
$msg_sentBookingMail = "Prenotazione confermata. Una mail di riepilogo è stata inviata all'indirizzo usato per la prenotazione";
$msg_bookingDeleteSucc = "La prenotazione è stata eliminata correttamente. Grazie";
$msg_sentContactMail = "Email di contatto inviata correttamente" ;


// err

$err_timetableUpdateErr = "Errore durante l'aggiornamento degli orari dello studio medico";
$err_bookingExists = "Errore: la prenotazione non è disponibile";
$err_errSendBookingMail = "La prenotazione è confermata, ma c'è stato un errore nell'invio della mail di riepilogo.";
$err_notBooked = "Errore nella prenotazione. Si prega di riprovare";
$err_bookingDeleteFail = "Errore durante l'eliminazione della prenotazione" ;
$err_errSendContactMail = "Errore nell'invio della mail di contatto. Si prega di riprovare più tardi" ;