<?php

declare(strict_types=1);

$verify->table = 'verify';
$stmt = $verify->showAll('id');
$row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
$public = (string) ($row['public'] ?? '');
?>

<script src="https://www.google.com/recaptcha/api.js?render=<?= urlencode($public) ?>"></script>

<script>
    grecaptcha.ready(function() {
        grecaptcha.execute(<?= json_encode($public) ?>, {
            action: 'submit'
        }).then(function(token) {
            var recaptchaResponse = document.getElementById('recaptchaResponse');
            if (recaptchaResponse) {
                recaptchaResponse.value = token;
            }
        });
    });
</script>