<?php $this->layout('master', ['title' => 'User Account']) ?>
<h1 class="text-center">User Account</h1>

<p class="text-danger">
    <?= $error ?? '' ?>
</p>

<div id="display-form" class="form-container">

    <div class="mb-3 border border-1 rounded p-1">
        <label for="username-read" class="form-label"><strong>User Name</strong></label>
        <input type="text" class="form-control border-0" id="username-read" value="<?= $this->e($user['UserName']) ?>" readonly required>
    </div>

    <div class="mb-3 border border-1 rounded p-1">
        <label for="user-email" class="form-label"><strong>Email</strong></label>
        <input type="text" class="form-control border-0" id="user-email" value="<?= $this->e($user['UserEmail']) ?>" readonly required>
    </div>

    <div class="text-center d-flex flex-wrap">
        <button class="btn btn-warning" onClick="EditFormToggle()"><i class="bi bi-pencil"></i> Edit</button>
    </div>
</div>

<form id="edit-form" action="<?= $router->generate('usernameEmailUpdate', ['userId' => $user['UserId']]) ?>" method="post" class="form-container" style="display: none;">
    <h3 class="text-center">Edit Username & Email</h3>
    <div class="mb-3">
        <label for="username" class="form-label">User Name</label>
        <input type="text" class="form-control" name="username" id="username" value="<?= $this->e($user['UserName']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" class="form-control" name="email" id="email" value="<?= $this->e($user['UserEmail']) ?>" required>
    </div>

    <div class="text-center d-flex flex-wrap justify-content-around">
        <button class="btn btn-success" type="submit"><i class="bi bi-arrow-up-circle"></i> Submit</button>
        <div class="btn btn-danger" onClick="EditFormToggle()"><i class="bi bi-x-circle"></i> Cancel</div>
    </div>
</form>

<h2 class="text-center mt-5">Reviews</h2>
<?php if (empty($reviews)) : ?>
    <p>You have not written any reviews.</p>
<?php else : ?>
    <?php foreach ($reviews as $review) : ?>
        <div class="card mt-3">
            <div class="card-header bg-red text-light">
                <span><?= $this->e($review['MovieTitle']) ?></span>
                <span style="margin-left: 1em;"><?= $this->e($review['Score']) ?> / 5</span>
            </div>

            <div class="card-body">
                <p><?= $this->e($review['ReviewText']) ?></p>
                <a href="<?= $router->generate('reviewUpdateForm', ['reviewId' => $review['ReviewId']]) ?>"><i class="bi bi-pencil"></i> Edit</a>
                <span class="mx-2"></span>
                <a href="<?= $router->generate('reviewDeleteConfirm', ['reviewId' => $review['ReviewId']]) ?>"><i class="bi bi-trash"></i> Delete</a>
            </div>
        </div>
    <?php endforeach ?>
<?php endif ?>

<script>
    function EditFormToggle() {
        var displayForm = document.getElementById('display-form');
        var editForm = document.getElementById('edit-form');
        if (editForm.style.display === 'none') {
            editForm.style.display = 'block';
            displayForm.style.display = 'none';
        } else {
            editForm.style.display = 'none';
            displayForm.style.display = 'block';
        }
    }
</script>