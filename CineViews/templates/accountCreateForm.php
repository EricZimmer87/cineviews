<?php $this->layout('master', ['title' => 'Create Account']) ?>

<form class="form-container" action="<?= $router->generate('accountCreate') ?>" method="post">
    <h1 class="text-center">Create Account</h1>

    <p class="text-danger">
        <?= $error ?? '' ?>
    </p>

    <label class="form-label" for="username">Username:</label>
    <input class="form-control" type="text" id="username" name="username" required /><br /><br />

    <label class="form-label" for="email">Email:</label>
    <input class="form-control" id="email" name="email" required type="email" /><br /><br />

    <label class="form-label" for="password">Password:</label>
    <input class="form-control" id="password" name="password" type="password" required />
    <small id="passwordHelp" class="form-text text-muted">Password must be at least 8 characters long, contain at least one special character (@$!%*?&), one number, one capital letter, and one lowercase letter.</small><br />

    <label class="form-label" for="password-confirm">Confirm Password:</label>
    <input class="form-control" id="password-confirm" name="password-confirm" type="password" required />

    <div class="mt-1">
        <input class="form-check-input" type="checkbox" id="showPassword">
        <label class="form-check-label" for="showPassword">Show Passwords</label>
    </div><br><br>

    <div class="text-center">
        <button class="btn btn-success" name="register" type="submit">
            <i class="bi bi-person-plus"></i> Create Account
        </button>
    </div>

</form>

<script>
    // Listener for toggling text and * for password fields
    document.getElementById('showPassword').addEventListener('change', function() {
        var passwordField = document.getElementById('password');
        var passwordConfirmField = document.getElementById('password-confirm');
        if (this.checked) {
            passwordField.type = 'text';
            passwordConfirmField.type = 'text';
        } else {
            passwordField.type = 'password';
            passwordConfirmField.type = 'password';
        }
    });

    // Listener to edit password confirm display based on password confirm and password matching
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

    // Listener to determine if password field meets all criteria
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