<!DOCTYPE html>
<html lang="en">

<head>
    <meta name=viewport content="width=device-width, initial-scale=1">
    <title><?= $this->e($title) ?></title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="camera-reels.svg">

    <style>
        .form-container {
            max-width: 500px;
            margin: 15px auto 0 auto;
            border: .5px solid black;
            box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.5);
            border-radius: 5px;
            padding: 1em;
        }

        .bg-red {
            background-color: #8B0000;
        }

        .card {
            border: .5px solid black;
            box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.5);
            border-radius: 5px;
        }

        #home-info {
            margin: auto;
            max-width: 300px;
        }

        img {
            border-radius: 5px;
            border: .5px solid black;
            width: 100%;
            max-width: 300px;
            box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.5);
        }

        .is-invalid {
            border-color: #dc3545;
        }

        #confirm-error-msg {
            color: #dc3545;
            display: none;
            font-size: 80%;
            margin-top: 0.25rem;
        }
    </style>

</head>

<body>
    <?php
    session_start();
    ?>
    <header>
        <div class="form-check form-switch bg-red text-light mb-0 d-flex justify-content-end">
            <input class="form-check-input" type="checkbox" role="switch" id="light-dark-switch">
            <label class="form-check-label" for="light-dark-switch">Dark Mode</label>
        </div>

        <nav class="navbar navbar-expand-lg navbar-light bg-red">
            <div class="container-fluid">
                <a class="navbar-brand text-light" href="<?= $router->generate('home') ?>">CineViews <i class="bi bi-camera-reels"></i></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active text-light" aria-current="page" href="<?= $router->generate('home') ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  text-light" href="<?= $router->generate('movies') ?>">Browse</a>
                        </li>
                    </ul>
                    <form class="row g-0" action="<?= $router->generate('searchMovies') ?>" method="get">
                        <div class="col-auto">
                            <input class="form-control" type="search" placeholder="Search movie by title" aria-label="Search" name="search" id="search">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary" type="submit" aria-label="Search"><i class="bi bi-search"></i></button>
                        </div>
                    </form>

                </div>

                <div class="p-3">
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) { ?>
                        <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Welcome, <br />
                            <?php echo $_SESSION['username']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end nav-item" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?php echo $router->generate('userDetail'); ?>">Account Information</a></li>
                            <li class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= $router->generate('logout') ?>">Logout</a></li>
                            <?php if ($_SESSION['id'] == 26) { ?>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo $router->generate('movieAddForm'); ?>">Add Movie</a></li>
                                <li><a class="dropdown-item" href="<?php echo $router->generate('genres'); ?>">All Genres</a></li>
                                <li><a class="dropdown-item" href="<?php echo $router->generate('users'); ?>">View Users</a></li>
                            <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person"></i> Log in <i class="bi bi-slash"></i> Create Account
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end nav-item" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?php echo $router->generate('loginForm'); ?>">Log in</a></li>
                            <li class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= $router->generate('accountCreateForm') ?>">Create Account</a></li>
                        </ul>
                    <?php } ?>
                </div>

            </div>
        </nav>
    </header>

    <div class="container">
        <?= $this->section('content') ?>
    </div>

    <footer class="footer bg-red mt-5">
        <div class="container text-center text-light">
            <p>Copyright &copy; CineViews 2024</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



    <script>
        /***  Light/dark mode toggle script ***/

        function setTheme(theme) {
            document.documentElement.setAttribute('data-bs-theme', theme);
            // Store the selected theme in local storage
            localStorage.setItem('theme', theme);
        }

        function toggleTheme() {
            if (document.documentElement.getAttribute('data-bs-theme') === 'dark') {
                setTheme('light');
            } else {
                setTheme('dark');
            }
        }

        const switchInput = document.getElementById('light-dark-switch');
        switchInput.addEventListener('click', toggleTheme);

        // Check if theme is stored in local storage
        const storedTheme = localStorage.getItem('theme');

        // If a theme is stored, set it
        if (storedTheme) {
            setTheme(storedTheme);
        }

        // Update switch state based on stored theme
        if (storedTheme === 'dark') {
            switchInput.checked = true;
        } else {
            switchInput.checked = false;
        }
    </script>

</body>

</html>