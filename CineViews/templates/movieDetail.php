<?php $this->layout('master', ['title' => 'Movie Info']) ?>
<div class="container-sm d-flex flex-wrap justify-content-around text-center mt-3">
    <div class="">
        <img class="" src="<?= $this->e($movie['Art']) ?>" alt="picture of <?= $this->e($movie['MovieTitle']) ?>">
    </div>
    <div>
        <h3>
            <?= $this->e($movie['MovieTitle']) ?>
        </h3>
        <p class="fs-6">
            <?= $this->e($movie['IsSeries'] == 1 ? 'Series' : 'Movie') ?>
        </p>
        <p>
            <?= date('F j, Y', strtotime($movie['ReleaseDate'])) ?>
            <br />
            <?= $this->e($movie['Genres']) ?>
        </p>
    </div>
    <h3 class="">
        Average Score <br />
        <?= !isset($movie['AverageScore']) ? "* / 5" : $this->e($movie['AverageScore']) . " / 5" ?>
    </h3>

</div>

<h2 class="text-center mt-3">Reviews</h2>

<p class="text-danger">
    <?= $error ?? '' ?>
</p>

<!-- Check if user is logged in.
Displays a button to add review if user is logged in.
Displays a button to log in to add a review if nobody is logged in. -->
<?php session_start(); ?>
<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) { ?>
    <button class="btn btn-success" id="add-review-btn" onClick="AddReviewFormToggle()"><i class="bi bi-plus-circle"></i> Add Review</button>
<?php } else { ?>
    <a class="btn btn-primary" href="<?= $router->generate('loginForm') ?>"><i class="bi bi-person-circle"></i> Log in to write a review.</a>
<?php } ?>

<div id="add-review-form" style="display: none;">
    <form action="<?= $router->generate('reviewAdd', ['movieId' => $movie['MovieId']]) ?>" method="post" class="form-container">
        <h2 class="text-center"><?= $movie['MovieTitle'] ?></h2>

        <p class="text-danger">
            <?= $error ?? '' ?>
        </p>

        <div class="mb-3">
            <label for="score" class="form-label">Score (out of 5)</label>
            <input name="score" type="number" step="0.1" min="0" max="5" class="form-control" id="score" placeholder="0" required>
        </div>
        <div class="mb-3">
            <label for="review-text" class="form-label">Review</label>
            <textarea name="review-text" class="form-control" id="review-text" rows="3" required></textarea>
        </div>
        <div class="text-center d-flex flex-wrap justify-content-around">
            <button class="btn btn-success" type="submit"><i class="bi bi-plus-circle"></i> Add Review</button>
        </div>
    </form>
</div>

<?php if (empty($movie['Reviews'])) : ?>
    <div class="card mt-3">
        <div class="card-body">
            <p>There are no reviews for this movie.</p>
        </div>
    </div>
<?php else : ?>
    <?php foreach ($movie['Reviews'] as $mr) : ?>
        <div class="card mt-3">
            <div class="card-header bg-red text-light">
                <span><?= $this->e($mr['ReviewerName']) ?></span>
                <span style="margin-left: 1em;"><?= $this->e($mr['Score']) ?> / 5</span>
            </div>
            <div class="card-body">
                <p><?= $this->e($mr['ReviewText']) ?></p>
            </div>
        </div>
    <?php endforeach ?>
<?php endif ?>


<script>
    function AddReviewFormToggle() {
        var reviewForm = document.getElementById('add-review-form');
        var addReviewBtn = document.getElementById('add-review-btn');
        if (reviewForm.style.display === 'none') {
            reviewForm.style.display = 'block';
            addReviewBtn.textContent = "";
            addReviewBtn.classList.remove("btn-success");
            addReviewBtn.classList.add("btn-danger");

            var icon = document.createElement("i");
            icon.className = "bi bi-dash-circle";
            addReviewBtn.appendChild(icon);
            addReviewBtn.appendChild(document.createTextNode(" Close"));
        } else {
            reviewForm.style.display = 'none';
            addReviewBtn.textContent = "";
            addReviewBtn.classList.remove("btn-danger");
            addReviewBtn.classList.add("btn-success");

            var icon = document.createElement("i");
            icon.className = "bi bi-plus-circle";
            addReviewBtn.appendChild(icon);
            addReviewBtn.appendChild(document.createTextNode(" Add Review"));
        }
    }
</script>