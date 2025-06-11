<?php $this->layout('master', ['title' => 'User Delete']) ?>
<h1 class="text-center">User Delete Confirm</h1>

<h3><strong><?= $this->e($user['user']['UserName']) ?></strong></h3>

<p>Are you sure you want to delete this user?</p>
<form action="<?= $router->generate('userDelete', ['userId' => $user['user']['UserId']]) ?>" method="post">
    <button class="btn btn-danger"><i class="bi bi-trash"></i> Delete</button>
    <a href="<?= $router->generate('users') ?>" class="btn btn-primary"><i class="bi bi-x-circle"></i> Cancel</a>
</form>