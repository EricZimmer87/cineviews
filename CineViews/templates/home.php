<?php $this->layout('master', ['title' => 'Home']) ?>
<h1 class="text-center"><i class="bi bi-film"></i> Welcome to CineViews! <i class="bi bi-film"></i></h1>
<div id="home-info" class="text-center">
    <p class="mt-5">CineViews is a website where anyone can write a review for a movie or TV show. Start browsing movies!</p>
    <a class="btn btn-primary" href="<?= $router->generate('movies') ?>"><i class="bi bi-card-list"></i> Browse</a>
    <p class="mt-5">Don't have an account?</p>
    <a class="btn btn-primary" href="<?= $router->generate('accountCreateForm') ?>"><i class="bi bi-person-plus"></i> Create Account</a>
</div>