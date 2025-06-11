<?php $this->layout('master', ['title' => 'Edit Review Error']) ?>
<h1 class="text-center">Error Editing Review</h1>

<p>There was an error editing the review:</p>

<p class="text-danger">
    <?= $error ?? '' ?>
</p>

<a href="<?= $router->generate('userDetail') ?>" class="btn btn-primary"><i class="bi bi-person-circle"></i> Back to User Account</a>