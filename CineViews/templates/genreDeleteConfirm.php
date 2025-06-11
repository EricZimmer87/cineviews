<?php $this->layout('master', ['title' => 'Genre Delete']) ?>
<h1 class="text-center">Genre Delete Confirm</h1>

<h3><strong><?= $this->e($genre['GenreName']) ?></strong></h3>

<p>Are you sure you want to delete this genre?</p>
<form action="<?= $router->generate('genreDelete', ['genreId' => $genre['GenreId']]) ?>" method="post">
    <button class="btn btn-danger"><i class="bi bi-trash"></i> Delete</button>
    <a href="<?= $router->generate('genres') ?>" class="btn btn-primary"><i class="bi bi-x-circle"></i> Cancel</a>
</form>