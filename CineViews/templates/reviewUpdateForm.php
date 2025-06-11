<?php $this->layout('master', ['title' => 'Edit Review']) ?>
<h1 class="text-center">Edit Review</h1>

<p class="text-danger">
    <?= $error ?? '' ?>
</p>

<form action="<?= $router->generate('reviewUpdate', ['reviewId' => $review['ReviewId']]) ?>" method="post" class="form-container">
    <h2><?= $this->e($review['MovieTitle']) ?></h2>

    <div class="mb-3">
        <label for="score" class="form-label">Score (out of 5)</label>
        <input type="number" step="0.1" min="0" max="5" class="form-control" id="score" name="score" value="<?= $this->e($review['Score']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="review-text" class="form-label">Review</label>
        <textarea class="form-control" id="review-text" rows="3" name="review-text" required><?= $this->e($review['ReviewText']) ?></textarea>

    </div>

    <div class="text-center d-flex flex-wrap justify-content-around">
        <button class="btn btn-warning" type="submit"><i class="bi bi-pencil"></i> Edit</button>
        <?php if ($_SESSION['id'] != 26 || ($_SESSION['id'] == 26 && $review['UserId'] == 26)) : ?>
            <a href="<?= $router->generate('userDetail') ?>" class="btn btn-danger"><i class="bi bi-x-circle"></i> Cancel</a>
        <?php else : ?>
            <a href="<?= $router->generate('users') ?>" class="btn btn-danger"><i class="bi bi-x-circle"></i> Cancel</a> <?php endif; ?>

    </div>

</form>