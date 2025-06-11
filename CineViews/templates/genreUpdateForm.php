<?php $this->layout('master', ['title' => 'Genre Update']) ?>

<form class="form-container" action="<?= $router->generate('genreUpdate', ['genreId' => $genre['GenreId']]) ?>" method="post">
    <h1 class="text-center">Genre Update</h1>

    <label class="form-label" for="genre-name">Genre Name</label>
    <input class="form-control" type="text" id="genre-name" name="genre-name" value="<?= $this->e($genre['GenreName']) ?>" required>

    <div class="text-center mt-3">
        <button class="btn btn-warning" type="submit"><i class="bi bi-pencil"></i> Edit</button>
        <a href="<?= $router->generate('genres') ?>" class="btn btn-danger"><i class="bi bi-x-circle"></i> Cancel</a>
    </div>
</form>