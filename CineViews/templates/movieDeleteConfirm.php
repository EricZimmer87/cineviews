<?php $this->layout('master', ['title' => 'Movie Delete Confirm']) ?>
<h1 class="text-center">Movie Delete Confirm</h1>

<h3><?= $this->e($movie['MovieTitle']) ?></h3>
<h6><?= date('F j, Y', strtotime($movie['ReleaseDate'])) ?></h6>

<p>Are you sure you want to delete this movie?</p>
<form action="<?= $router->generate('movieDelete', ['movieId' => $movie['MovieId']]) ?>" method="post">
    <button class="btn btn-danger"><i class="bi bi-trash"></i> Delete</button>
    <a href="<?= $router->generate('movies') ?>" class="btn btn-primary"><i class="bi bi-x-circle"></i> Cancel</a>
</form>