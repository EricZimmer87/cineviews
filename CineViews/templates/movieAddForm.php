<?php $this->layout('master', ['title' => 'Add Movie']) ?>

<form action="<?= $router->generate('movieAdd') ?>" method="post" class="form-container">
    <h1 class="text-center">Add Movie</h1>

    <p class="text-danger">
        <?= $error ?? '' ?>
    </p>

    <div class="mb-3">
        <label for="movie-title" class="form-label">Movie Title</label>
        <input type="text" class="form-control" name="movie-title" id="movie-title" required>
    </div>

    <div class="mb-3">
        <label for="release-date" class="form-label">Release Date</label>
        <input type="date" class="form-control" name="release-date" id="release-date" required>
    </div>

    <div class="mb-3">
        <label for="art" class="form-label">Art</label>
        <input type="text" class="form-control" name="art" id="art" required>
    </div>

    <div class="mb-3">
        <label for="is-series" class="form-label">Is it a series?</label>
        <select class="form-select" name="is-series" id="is-series" required>
            <option value="">Select an option</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="genres" class="form-label">Genres</label>
        <select id="genres" name="genres[]" class="form-select" size="10" multiple required>
            <?php
            // Get genres from database
            $genres = GetGenres();
            // Loop through genres to display in dropdown
            foreach ($genres as $genre) {
                echo '<option value="' . $genre['GenreId'] . '">' . $genre['GenreName'] . '</option>';
            }
            ?>
        </select>
        <p>Hold ctrl (Windows) or cmd (Mac) and click to select multiple values.</p>
    </div>


    <div class="text-center d-flex flex-wrap justify-content-around">
        <button class="btn btn-success" type="submit"><i class="bi bi-plus-circle"></i> Add Movie</button>
    </div>
</form>