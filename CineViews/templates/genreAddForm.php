<?php $this->layout('master', ['title' => 'Genre Add']) ?>

<form class="form-container" action="<?= $router->generate('genreAdd') ?>" method="post">
    <h1 class="text-center">Genre Add</h1>

    <p class="text-danger">
        <?= $error ?? '' ?>
    </p>


    <label class="form-label" for="genre-name">Genre Name</label>
    <input class="form-control" type="text" id="genre-name" name="genre-name" required>

    <div class="text-center mt-3">
        <button class="btn btn-success" type="submit"><i class="bi bi-plus-circle"></i> Add</button>
        <a href="<?= $router->generate('genres') ?>" class="btn btn-danger"><i class="bi bi-x-circle"></i> Cancel</a>
    </div>
</form>