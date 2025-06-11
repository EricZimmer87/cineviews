<?php $this->layout('master', ['title' => 'Reset Password']) ?>

<form class="form-container" action="<?= $router->generate('resetPassword', ['userId' => $userId]) ?>" method="post">
    <h1 class="text-center">Reset Password</h1>

    <p class="text-danger">
        <?= $error ?? '' ?>
    </p>

    <label class="form-label" for="password">New Password</label>
    <input class="form-control" id="password" name="password" type="password" required />
    <small id="passwordHelp" class="form-text text-muted">Password must be at least 8 characters long, contain at least one special character (@$!%*?&), one number, one capital letter, and one lowercase letter.</small><br />

    <label class="form-label" for="password-confirm">Confirm Password</label>
    <input class="form-control" id="password-confirm" name="password-confirm" type="password" required />

    <div class="mt-1">
        <input class="form-check-input" type="checkbox" id="showPassword">
        <label class="form-check-label" for="showPassword">Show Passwords</label>
    </div><br><br>

    <div class="text-center">
        <button type="submit" class="btn btn-success">
            <i class="bi bi-key"></i> Change Password
        </button>
    </div>

</form>

<script>
    document.getElementById('showPassword').addEventListener('change', function() {
        var currentPassword = document.getElementById('current-password');
        var passwordField = document.getElementById('password');
        var passwordConfirmField = document.getElementById('password-confirm');
        if (this.checked) {
            passwordField.type = 'text';
            passwordConfirmField.type = 'text';
            currentPassword.type = 'text';
        } else {
            passwordField.type = 'password';
            passwordConfirmField.type = 'password';
            currentPassword.type = 'password';
        }
    });

    document.getElementById('password-confirm').addEventListener('input', function() {
        var passwordField = document.getElementById('password');
        var passwordConfirmField = document.getElementById('password-confirm');
        var confirmErrorMsg = document.getElementById('confirm-error-msg');

        if (passwordField.value !== passwordConfirmField.value) {
            passwordConfirmField.classList.add('is-invalid');
            confirmErrorMsg.style.display = 'block';
        } else {
            passwordConfirmField.classList.remove('is-invalid');
            confirmErrorMsg.style.display = 'none';
        }
    });

    document.getElementById('password').addEventListener('input', function() {
        var passwordField = document.getElementById('password');
        var password = passwordField.value;
        var passwordHelp = document.getElementById('passwordHelp');
        var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        if (!passwordRegex.test(password)) {
            passwordField.classList.add('is-invalid');
            passwordHelp.innerText = "Password must be at least 8 characters long, contain at least one special character (@$!%*?&), one number, one capital letter, and one lowercase letter.";
        } else {
            passwordField.classList.remove('is-invalid');
            passwordHelp.innerText = "Strong password!";
        }
    });
</script>