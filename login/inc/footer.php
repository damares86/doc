<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const passwordIcon = this;

        // Controlla il tipo di input e cambia tra password e text
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('bi-eye');
            passwordIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('bi-eye-slash');
            passwordIcon.classList.add('bi-eye');
        }

        const passwordConfirmInput = document.getElementById('password_confirm');
        const passwordConfirmIcon = this;

        // Controlla il tipo di input e cambia tra password e text
        if (passwordConfirmInput.type === 'password') {
            passwordConfirmInput.type = 'text';
            passwordConfirmInput.classList.remove('bi-eye');
            passwordConfirmInput.classList.add('bi-eye-slash');
        } else {
            passwordConfirmIcon.type = 'password';
            passwordConfirmIcon.classList.remove('bi-eye-slash');
            passwordConfirmIcon.classList.add('bi-eye');
        }
    });
</script>

<script src="../admin/assets/js/bootstrap.js"></script>
<script src="../admin/assets/js/app.js"></script>
<script src="../admin/assets/js/pages/dashboard.js"></script>
<script src="../admin/assets/extensions/jquery/jquery.min.js"></script>
<script src="../admin/assets/extensions/parsleyjs/parsley.min.js"></script>
<script src="../admin/assets/js/pages/parsley.js"></script>
<script src="../admin/assets/js/pages/<?= $lang ?>.js"></script>
<script src="../admin/assets/js/pages/<?= $lang ?>.extra.js"></script>