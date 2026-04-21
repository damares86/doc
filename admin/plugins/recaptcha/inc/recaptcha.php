<?php
    $stmt=$verify->showAll('id');
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    $public=$row['public'];
?>



<script src="https://www.google.com/recaptcha/api.js?render=<?=$public?>"> </script>

<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('<?=$public?>', {
            action: 'submit'
        }).then(function(token) {
            var recaptchaResponse = document.getElementById('recaptchaResponse');
            recaptchaResponse.value = token;
        });
    });
</script>