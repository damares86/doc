<?php
require 'inc/header.php';
require 'inc/sidebar.php';

$page_name = 'info';

?>

<div class="page-content-wrapper">

  <div>

    <div class="tiny-slider-one-wrapper" dir="ltr">
      <div class="tiny-slider-one">
        <!-- Single Hero Slide -->
        <div>
          <div class="single-hero-slide bg-overlay" style="background-image: url('assets/img/bg_visual.png'); background-position:center center">
            <div class="h-100 d-flex align-items-center text-center">
              <div class="container">
                <h2 class="text-white mb-1">Dottoressa Crosetto Maria Chiara </h2>
                <h5 class="text-white">Via Stazione 2, Giaveno (TO)</h5>
                <!-- <h5 class="text-white">3450579662</h5> -->
                <!-- <a class="btn btn-creative btn-warning" href="#">Get Started <i class="ti ti-arrow-right"></i></a> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="pt-3"></div>

    <div id="home" class="container">
      <h1 class="text-center">Informazioni utili</h1>
      <p class="info">
        Lo studio medico è situato temporaneamente in <b>via Stazione 2 a Giaveno (TO)</b>.<br>
        Attualmente osserva i seguenti orari:
      <ul class="info">
        <li><b>Lunedì:</b> 10.30-11.30</li>
        <li><b>Martedì:</b> 17.00-18.00</li>
        <li><b>Mercoledì:</b> 10.30-11.30</li>
        <li><b>Giovedì:</b> 18.00-19.00</li>
        <li><b>Venerdì:</b> 14.30-15.30</li>
      </ul>
      <h3 class="mt-3 mb-4 text-center">Modalità di accesso</h3>
      <p class="info">
        <b>Lunedì:</b> accesso diretto per visite e richiesta ricette in orario di ambulatorio.<br>
        <b>Dal martedì al venerdì:</b>
      <ul class="info space">
        <li><u>visite, presa visione referti, richiesta esami e certificati:</u> accesso su prenotazione <b>preferibilmente</b> utilizzando il seguente
          <a href="booking.php">link</a>. In alternativa chiamare il numero 3450579662.
        </li>
        <li><u>richiesta farmaci quotidiani:</u> usare <b>preferibilmente</b> il <a href="contact.php">modulo</a> <i>(le richieste verranno evase entro 48h)</i> o accedere direttamente negli orari di ambulatorio</li>
        <li><u>solo per certificati di malattia e necessità di visita medica in giornata:</u> accesso diretto <b>entro la prima ora di ambulatorio</b> e attesa della chiamata del medico</li>
      </ul>
      <h3 class="mt-3 mb-4 text-center">Contatti</h3>
      <p class="info">
        Per consulti telefonici o richiesta di visita domiciliare chiamare il numero <b>3450579662</b> dalle ore 8 alle ore 19.
        In caso di mancata risposta e/o attivazione del servizio di segreteria telefonica (lasciare un messaggio specificando
        <b>nome, cognome, numero di telefono e motivo della chiamata</b>) verrete ricontattati appena possibile.
      </p>
      </p>
      </p>
      <div class="pb-3"></div>
    </div>

    <?php
    require 'inc/nav.php';
    require 'inc/footer.php';
    ?>