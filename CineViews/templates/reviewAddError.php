<?php $this->layout('master', ['title' => 'Add Review Error']) ?>
<h1 class="text-center">Error Adding Review</h1>

<p>There was an error adding the review:</p>

<p class="text-danger">
    <?= $error ?? '' ?>
</p>

<a href="<?= $router->generate('movies') ?>" class="btn btn-primary"><i class="bi bi-card-list"></i> Back to Browse</a>