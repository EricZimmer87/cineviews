<?php $this->layout('master', ['title' => 'Edit Movie Error']) ?>
<h1 class="text-center">Error Editing Movie</h1>

<p>There was an error editing the movie:</p>

<p class="text-danger">
    <?= $error ?? '' ?>
</p>

<a href="<?= $router->generate('movies') ?>" class="btn btn-primary"><i class="bi bi-card-list"></i> Back to Browse</a>