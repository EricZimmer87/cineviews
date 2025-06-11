<?php $this->layout('master', ['title' => 'Edit Movie']) ?>

<form action="<?= $router->generate('movieUpdate', ['movieId' => $movie['MovieId']]) ?>" method="post" class="form-container">
    <h1 class="text-center">Edit Movie</h1>
    <h6 class="text-center mb-0"><?= $this->e($movie['MovieTitle']) ?></h6>
    <p class="text-center mt-0"><?= date('F j, Y', strtotime($movie['ReleaseDate'])) ?></p>

    <p class="text-danger">
        <?= $error ?? '' ?>
    </p>

    <div class="mb-3">
        <label for="movie-title" class="form-label">Movie Title</label>
        <input type="text" class="form-control" name="movie-title" id="movie-title" value="<?= $this->e($movie['MovieTitle']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="release-date" class="form-label">Release Date</label>
        <input type="date" class="form-control" name="release-date" id="release-date" value="<?= $this->e($movie['ReleaseDate']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="art" class="form-label">Art</label>
        <input type="text" class="form-control" name="art" id="art" value="<?= $this->e($movie['Art']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="is-series" class="form-label">Is it a series?</label>
        <select class="form-select" name="is-series" id="is-series" required>
            <option value="Yes" <?= $this->e($movie['IsSeries']) == 1 ? 'selected' : '' ?>>Yes</option>
            <option value="No" <?= $this->e($movie['IsSeries']) == 0 ? 'selected' : '' ?>>No</option>
        </select>
    </div>

    <div class="text-center d-flex flex-wrap justify-content-around">
        <button class="btn btn-warning" type="submit"><i class="bi bi-pencil"></i> Edit Movie</button>
    </div>
</form>

<form class="form-container" action="<?= $router->generate('movieGenreUpdate', ['movieId' => $movie['MovieId']]) ?>" method="post">
    <h1 class="text-center">Edit Genres</h1>
    <h6 class="text-center mb-0"><?= $this->e($movie['MovieTitle']) ?></h6>
    <p class="text-center mt-0"><?= date('F j, Y', strtotime($movie['ReleaseDate'])) ?></p>

    <?php
    // Get all genres from the database
    $genres = GetGenres();

    // Get the genres associated with the movie
    $movieGenres = explode(", ", $movie['Genres']);

    // Loop through each genre
    foreach ($genres as $genre) {
        // Check if the current genre is associated with the movie
        $isChecked = in_array($genre['GenreName'], $movieGenres);
    ?>
        <!-- Display genres checkboxes -->
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="genre_<?php echo $genre['GenreId']; ?>" name="genres[]" value="<?php echo $genre['GenreId']; ?>" <?php if ($isChecked) echo 'checked'; ?>>
            <label class="form-check-label" for="genres[]"><?php echo htmlspecialchars($genre['GenreName']); ?></label>
        </div>
    <?php
    }
    ?>
    <button type="submit" class="btn btn-warning mt-3"><i class="bi bi-pencil"></i> Edit Genres</button>
</form>