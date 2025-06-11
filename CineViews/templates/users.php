<?php $this->layout('master', ['title' => 'Users']) ?>
<h1 class="text-center">Users</h1>

<p class="text-danger">
    <?= $error ?? '' ?>
</p>

<div class="overflow-x-scroll">
    <table class="table table-striped">
        <thead class="table-light">
            <tr>
                <th scope="col">User ID</th>
                <th scope="col">User Name</th>
                <th scope="col">User Email</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u) : ?>
                <tr>
                    <th scope="row"><?= $this->e($u['UserId']) ?></th>

                    <td><?= $this->e($u['UserName']) ?></td>

                    <td><?= $this->e($u['UserEmail']) ?></td>

                    <td>
                        <a href="<?= $router->generate('userDetailAdmin', ['userId' => $u['UserId']]) ?>"><i class="bi bi-pencil"></i> Edit</a>
                        <a href="<?= $router->generate('userDeleteConfirm', ['userId' => $u['UserId']]) ?>"><i class="bi bi-trash"></i> Delete</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>