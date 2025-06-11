<?php $this->layout('master', ['title' => 'Error Sending Email']) ?>
<h1 class="text-center">Error</h1>
<p>There was an error sending the email.</p>
<p class="text-danger">
    <?= $msg ?? '' ?>
</p>