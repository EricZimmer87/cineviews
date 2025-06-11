<?php $this->layout('master', ['title' => 'Update User Error']) ?>
<h1 class="text-center">Error Updating User Information</h1>

<p class="">
    There was an error updating the user:
</p>

<p class="text-danger">
    <?= $error ?? '' ?>
</p>

<a href="<?= $router->generate('movies') ?>" class="btn btn-primary"><i class="bi bi-card-list"></i> Back to Browse</a>