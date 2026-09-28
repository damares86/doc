<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

$reg_mail_subject = "Prenotazione visita - ";
$doc_block1 = '<html><body>';
$doc_block1 .= '<p>Buongiorno,</p>';
$doc_block1 .= '<p>Si conferma la prenotazione della visita. Di seguito i dati:</p>';

$doc_block2 = '<p>In caso non potesse più venire in visita, chiedo gentilmente di eliminare la prenotazione
cliccando sul seguente link.<br>Se il link non dovesse funzionare, può copiarlo nella barra degli indirizzi del browser.</p>';

$doc_block3 = '<p>Grazie,<br>Dottoressa Crosetto Maria Chiara</p>';
$doc_block3 .= '<p>-----------------------------------------------------------</p>';
$doc_block3 .= '<p>ATTENZIONE: questa è una mail automatica, non rispondere a questo messaggio. Per contattare la dottoressa visitare il sito web.</p>';
$doc_block3 .= '</body></html>';

// Messaggi di successo
$msg_timetableUpdate = "Orari dello studio medico aggiornati con successo";
$msg_sentBookingMail = "Prenotazione confermata. Una email di riepilogo è stata inviata all'indirizzo indicato";
$msg_bookingDeleteSucc = "La prenotazione è stata eliminata correttamente. Grazie";
$msg_sentContactMail = "Email di contatto inviata correttamente";
$msg_msgUpdate = "Avviso in homepage aggiornato con successo";

// Messaggi di errore
$err_timetableUpdateErr = "Errore durante l'aggiornamento degli orari dello studio medico";
$err_bookingExists = "Errore: lo slot orario selezionato non è più disponibile";
$err_errSendBookingMail = "La prenotazione è confermata, ma si è verificato un errore nell'invio dell'email di riepilogo";
$err_notBooked = "Errore durante la prenotazione. Si prega di riprovare";
$err_bookingDeleteFail = "Errore durante l'eliminazione della prenotazione";
$err_errSendContactMail = "Errore nell'invio dell'email di contatto. Si prega di riprovare più tardi";
$err_uploadError = "Errore durante il caricamento del file allegato";
$err_fileTooLarge = "Dimensioni file troppo elevate: dimensione massima consentita 10MB";
$err_invalidFileType = "Formato di file non consentito. Formati accettati: PDF, JPG, JPEG, PNG, WEBP, HEIC, HEIF";
$err_errRecaptcha = "Verifica reCAPTCHA non riuscita. Si prega di riprovare";
$err_noBooking = "Nessuna prenotazione trovata per i parametri specificati";
$err_msgUpdateErr = "Errore durante l'aggiornamento dell'avviso in homepage";