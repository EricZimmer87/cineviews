<?php $this->layout('master', ['title' => 'Forgot Password Form']) ?>
<h1 class="text-center">Forgot Password Form</h1>

<p class=" text-danger">
    <?= $msg ?? '' ?>
</p>

<form action="<?= $router->generate('passwordReset') ?>" method="post" class="form-container">
    <label class="form-label" for="email">Email</label>
    <input class="form-control" type="text" name="email" id="email" required>

    <button type="submit" class="btn btn-primary mt-3">Continue</button>
</form>