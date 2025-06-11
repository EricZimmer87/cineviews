<?php $this->layout('master', ['title' => 'Login']) ?>

<form class="form-container" action="<?= $router->generate('login') ?>" method="post">
    <h1 class="text-center">Log in</h1>
    <p class="text-danger">
        <?= $error ?? '' ?>
    </p>
    <label class="form-label" for="username">Username:</label>
    <input class="form-control" type="text" id="username" name="username" required><br><br>

    <label class="form-label" for="password">Password:</label>
    <div class="input-group">
        <input class="form-control" type="password" id="password" name="password" required>

    </div>
    <div class="mt-1">
        <input class="form-check-input" type="checkbox" id="showPassword">
        <label class=" form-check-label" for="showPassword">Show Password</label>
    </div>

    <div class="text-center">
        <a href="<?= $router->generate('forgotPasswordForm') ?>">Forgot your password?</a>
    </div>

    <br />

    <div class="text-center">
        <button class="btn btn-primary" type="submit"><i class="bi bi-box-arrow-in-right"></i> Log in</button>
    </div>

    <div class="mt-3">
        <a href="<?= $router->generate('accountCreateForm') ?>">Don't have an account? Click here to create one.</a>
    </div>
</form>

<script>
    // Listener for toggling text and * for password field
    document.getElementById('showPassword').addEventListener('change', function() {
        var passwordField = document.getElementById('password');
        if (this.checked) {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password';
        }
    });
</script>