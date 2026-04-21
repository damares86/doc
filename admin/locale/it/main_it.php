<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################


// TRADUZIONE SEZIONI SIDEBAR
// la label della sezione lowercase e con "_" invece degli spazi, preceduta da "label_"

$label_dashboard = "Dashboard";
$label_accounts = "Utenti";
$label_files = "File";
$label_program_file = "Programma";
$label_all_files = "Tutti i file";
$label_settings = "Impostazioni";
$label_all_settings = "Generali";
$label_modules = "Moduli";
$label_all_accounts = "Tutti gli utenti";
// $label_profile = "Modifica il tuo profilo";
$label_add_account = "Aggiungi utente";
$label_all_roles = "Tutti i ruoli";
$label_damares = "Damares" ;
$label_booking = "Prenotazioni";
$label_all_bookings = "Vedi prenotazioni";
$label_add_booking = "Aggiungi prenotazione";

//////  COMMON  //////

$common_dashboard = "Dashboard" ;
$common_logout = "Logout" ;
$common_link = "Link" ;
$common_username = "Username" ;
$common_name = "Nome" ;
$common_surname = "Cognome" ;
$common_email = "Email" ;
$common_role = "Ruolo" ;
$common_lastLogin = "Ultimo Login" ;
$common_actions = "Azioni" ;
$common_password = "Password" ;
$common_modal_title_sure = "Sei sicuro?" ;
$common_modal_confirm = "Conferma" ;
$common_modal_cancel = "Cancella" ;
$common_submit = "Invia" ;
$common_close = "Chiudi" ;
$common_update = "Aggiorna" ;
$common_reset = "Reset" ;
$common_info = "Info" ;
$common_rolename = "Nome del ruolo" ;
$common_section_auth = "Sezioni autorizzate" ;
$common_number_user = "Numero di utenti" ;
$common_profile = "Il mio profilo" ;
$common_see_guide = "Vedi la guida" ;
$common_back = "Torna indietro" ;
$common_loading = "Caricamento" ;
$common_delete = "Elimina" ;


//////  HOMEPAGE  //////

$welcome_title = "Benvenuto" ;
$welcome_desc1 = "Benvenuto nella dashboard di amministrazione di <b>damares</b>.<br>
Qui puoi trovare alcune informazioni e link utili per la gestione del tuo backend.<br>
Ci sono i manuali del core di <b>damares</b> e di ogni modulo installato linkati in questa pagina.<br><br>
Se ha bisogno di aiuto " ;
$welcome_desc2 = "contattaci" ;
$manuals_title = "Manuali" ;
$last_login_title = "Ultimi utenti loggati" ;


//////  ACCOUNT  //////

$account_add_header = "Aggiungi nuovo utente" ;
$account_all_header = "Tutti gli utenti" ;
$account_all_title = "Tutti gli utenti registrati" ;
$account_all_add = "Aggiungi nuovo utente" ;
$account_all_modal_body = "Se confermi, cancellerai definitivamente questo utente." ;
$account_add_title = "Completa le informazione per questo utente" ;
$account_add_avatar = "Avatar" ;
$account_add_optional = "(opzionale)" ;
$account_edit_header = "Modifica utente" ;
$account_edit_title = "Modifica le informazioni per questo utente" ;
$account_edit_password = "Modifica la password per questo utente" ;
// optional fields
$account_add_name = "Nome"; 
$account_add_surname = "Cognome"; 
$account_add_birth = "Data di nascita"; 
$account_add_phone = "Telefono"; 


//////  ROLE  //////

$role_all_header = "Tutti i ruoli" ;
$role_all_title = "Tutti i ruoli" ;
$role_all_add = "Aggiungi nuovo ruolo" ;
$role_all_modal_body = "Se confermi, cancellerai definitivamente questo ruolo." ;
$role_add_header = "Aggiungi ruolo" ;
$role_add_title = "Crea un nuovo ruolo" ;
$role_edit_header = "Modifica ruolo" ;
$role_edit_title = "Modifica la informazioni per " ;
$role_header_parent = "Sezioni" ;
$role_header_child = "Sottosezioni" ;


//////  FILE  //////

$file_all_header = "Tutti i file" ;
$file_fm = "File Manager" ;
$file_fm_open = "Apri file manager" ;


//////  PLUGIN  //////

$plugin_all_title = "Tutti i moduli" ;
$plugin_all_add = "Carica un nuovo modulo" ;
$plugin_all_name = "Nome del modulo" ;
$plugin_all_description = "Descrizione" ;
$plugin_all_modal_body = "Se confermi, cancellerai definitivamente questo modulo." ;


//////  SETTINGS  //////

$settings_all_title = "Tutte le impostazioni" ;
$settings_all_lang = "Lingua" ;
$settings_all_noreply = "Email per reset password" ;
$settings_layout_title = "Layout" ;
$settings_layout_horizontal = "Orizzontale" ;
$settings_layout_vertical = "Verticale" ;


//////  DAMARES  //////

$damares_title = "Gestione Root Damares" ;
$damares_enable = "Abilita" ;
$damares_clear_title = "Reset dell'installazione";
$damares_clear_button = "Reset" ;
$damares_modal_body = "Se confermi, tutti i dati del db e tutti i file personalizzati saranno ELIMINATI DEFINITIVAMENTE!" ;


//////  MAIN MSG ALERT  //////

$msg_accountDel = "Utente eliminato" ;
$msg_passMod = "Password modificata" ;
$msg_accountEdit = "Utente modificato correttamente" ;
$msg_accountSucc = "Utente creato" ;
$msg_roleDel = "Ruolo eliminato" ;
$msg_roleEdit = "Ruolo modificato correttamente" ;
$msg_roleSucc = "Ruolo creato" ;
$msg_fileDel = "File eliminato" ;
$msg_fileEditSucc = "File modificato correttamente" ;
$msg_fileSucc = "File caricato" ;
$msg_pluginUploadSucc = "Modulo caricato correttamente" ;
$msg_pluginAdd = "Modulo aggiunto e attivato" ;
$msg_pluginDis = "Modulo disattivato" ;
$msg_pluginRm = "Modulo eliminato" ;
$msg_settingUpdate = "Impostazioni modificate" ;
$msg_debugUpdate = "Debug attivato" ;


//////  MAIN ERR ALERT  //////

$err_accountNoDel = "Utente non eliminato" ;
$err_passNoMod = "Password non modificata" ;
$err_noAvatarUpload = "Avatar non caricato" ;
$err_accountRoleNoEdit = "Ruolo utente non modificato" ;
$err_accountNoEdit = "Utente non modificato" ;
$err_accountExist = "Email utente già presente" ;
$err_accountFail = "Utente non creato" ;
$err_roleNoDel = "Ruolo non eliminato" ;
$err_roleNoEdit = "Ruolo non modificato" ;
$err_roleExist = "Il ruolo esiste già" ;
$err_roleFail = "Ruolo non creato" ;
$err_formatErr = "Formato file errato (permessi solo file 'pdf', 'doc', 'docx', 'zip')" ;
$err_fileNoDel = "File non eliminato" ;
$err_fileEditFail = "File non modificato" ;
$err_fileExists = "Il file esiste già" ;
$err_fileFail = "File non caricato" ;
$err_fileErr = "Errore con il file" ;
$err_pluginUploadFormatErr = "File danneggiato o formato file errato" ;
$err_pluginUploadErr = "Modulo non caricato" ;
$err_pluginDbErr = "Modulo non aggiunto al db" ;
$err_pluginAddErr = "Modulo non aggiunto e attivato" ;
$err_pluginDisErr = "Modulo non disattivato" ;
$err_pluginRmErr = "Modulo non rimosso" ;
$err_settingUpdateErr = "Impostazioni non modificate" ;
$err_debugUpdateErr = "Debug non attivato" ;
$err_rolePermFail = "Errore durante la configurazione dei permessi per questo ruolo" ;
$errPermPlugin = "Errori durante l'aggiornamento dei permessi di sezione per l'utente" ;
