<?php $this->layout('master', ['title' => 'Genres']) ?>
<h1 class="text-center">Genres</h1>

<p class="text-danger">
    <?= $error ?? '' ?>
</p>

<div class="overflow-x-scroll">
    <table class="table table-responsive overflow-x-scroll table-striped">
        <thead class="table-light">
            <tr>
                <th scope="col">Genre ID</th>
                <th scope="col">Genre Name</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($genres as $g) : ?>
                <tr>
                    <th scope="row"><?= $this->e($g['GenreId']) ?></th>

                    <td><?= $this->e($g['GenreName']) ?></td>

                    <td><a href="<?= $router->generate('genreUpdateForm', ['genreId' => $g['GenreId']]) ?>"><i class="bi bi-pencil"></i> Edit</a>
                        <a href="<?= $router->generate('genreDeleteConfirm', ['genreId' => $g['GenreId']]) ?>"><i class="bi bi-trash"></i> Delete</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <a href="<?= $router->generate('genreAddForm') ?>" class="btn btn-success"><i class="bi bi-plus-circle"></i> Add Genre</a>
</div>