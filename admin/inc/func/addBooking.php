<?php

declare(strict_types=1);

if (!headers_sent()) {
    header('Location: ../booking.php');
    exit;
}
?>
<script>
    window.location.replace('../booking.php');
</script>
<noscript>
    <meta http-equiv="refresh" content="0;url=../booking.php">
</noscript>
<div class="container text-center py-5">
    <div class="spinner-border text-primary mb-3" role="status">
        <span class="visually-hidden"><?= htmlspecialchars((string) ($common_loading ?? 'Loading...'), ENT_QUOTES, 'UTF-8') ?></span>
    </div>
    <p>Reindirizzamento in corso a <a href="../booking.php">booking.php</a>...</p>
</div>