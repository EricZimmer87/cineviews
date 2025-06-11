<?php $this->layout('master', ['title' => 'Reset Password Error']) ?>
<h1 class="text-center">There was an error resetting your password.</h1>

<p class="text-danger">
    <?= $error ?? '' ?>
</p>