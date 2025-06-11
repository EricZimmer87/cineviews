<?php $this->layout('master', ['title' => 'Delete Confirm']) ?>
<h1 class="text-center">Delete Confirm</h1>

<div class="card mt-3">
    <div class="card-header bg-red text-light">
        <span><?= $this->e($review['MovieTitle']) ?></span>
        <span style="margin-left: 1em;"><?= $this->e($review['Score']) ?> / 5</span>
    </div>

    <div class="card-body bg-light">
        <p><?= $this->e($review['ReviewText']) ?></p>
    </div>
</div>

<p class="mt-1">Are you sure you want to delete this review?</p>
<form action="<?= $router->generate('reviewDelete', ['reviewId' => $review['ReviewId']]) ?>" method="post">
    <button class="btn btn-danger" type="submit"><i class="bi bi-trash"></i> Delete</button>
    <?php if ($_SESSION['id'] != 26 || ($_SESSION['id'] == 26 && $review['UserId'] == 26)) : ?>
        <a href="<?= $router->generate('userDetail') ?>" class="btn btn-primary"><i class="bi bi-x-circle"></i> Cancel</a>
    <?php else : ?>
        <a href="<?= $router->generate('users') ?>" class="btn btn-primary"><i class="bi bi-x-circle"></i> Cancel</a> <?php endif; ?>
</form>