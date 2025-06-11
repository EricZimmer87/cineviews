<?php

require 'database.php';
require 'vendor/autoload.php';
$router = new AltoRouter();
$router->setBasePath("/CineViews");

$templates = new League\Plates\Engine('templates');
$templates->addData(["router" => $router]);

$adminId = 26;

/***    Routes    ***/

// Home
$router->map('GET', '/', function () use ($templates) {
  echo ($templates->render('home'));
}, "home");

// Movies list (browse)
$router->map('GET', '/movies', function () use ($templates) {
  echo ($templates->render("movies", ["movies" => GetMovies()]));
}, "movies");

// Search Movies from search bar
$router->map('GET', '/movies/search', function () use ($templates, $router) {
  $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
  if (empty($searchQuery)) {
    // No input in search bar, go to movies (browse)
    header('Location: ' . $router->generate("movies"));
    exit();
  } else {
    echo $templates->render("movies", ["movies" => SearchMovies($searchQuery)]);
  }
}, "searchMovies");

// Movie Title Details
$router->map('GET', '/movies/[i:movieId]', function ($movieId) use ($templates) {
  echo ($templates->render("movieDetail", ["movie" => GetMovieDetail($movieId)]));
}, "movieDetail");

// Movie Add Form
$router->map('GET', '/movie/add', function () use ($templates, $adminId) {
  // Check if the current user ID matches admin user ID
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  } else {
    echo ($templates->render('movieAddForm'));
  }
}, "movieAddForm");

// Movie Add - Actually adds the movie
$router->map('POST', '/movie/add', function () use ($router, $templates, $adminId) {
  // Allow access to only admin account
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  $addMovieResult = CreateMovie();
  if ($addMovieResult === true) {
    header('Location: ' . $router->generate('movies'));
  } else {
    echo $templates->render("movieAddForm", ["error" => $addMovieResult]);
  }
}, "movieAdd");

// Movie Update Form
$router->map('GET', '/update/[i:movieId]', function ($movieId) use ($templates, $adminId) {
  // Allow access to only admin account
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  echo ($templates->render("movieUpdateForm", ["movie" => GetMovieDetail($movieId)]));
}, "movieUpdateForm");

// Movie Update - Acutally updates the movie
$router->map('POST', '/update/[i:movieId]', function ($movieId) use ($router, $adminId, $templates) {
  // Allow access to only admin account
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  $updateMovieResult = UpdateMovie($movieId);
  if ($updateMovieResult === true) {
    header('Location: ' . $router->generate('movies'));
  } else {
    echo $templates->render("movieUpdateFormError", ["error" => $updateMovieResult]);
  }
}, "movieUpdate");

// Updates Movie Genres
$router->map('POST', '/update/genres/[i:movieId]', function ($movieId) use ($router, $adminId, $templates) {
  // Allow access to only admin account
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  $updateMovieGenreResult = UpdateMovieGenre($movieId);
  if ($updateMovieGenreResult === true) {
    header('Location: ' . $router->generate('movies'));
  } else {
    echo $templates->render("movieUpdateFormError", ["error" => $updateMovieGenreResult]);
  }
}, "movieGenreUpdate");

// Movie Delete Confirm
$router->map('GET', '/delete/[i:movieId]', function ($movieId) use ($templates, $adminId) {
  // Allow access to only admin account
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  echo ($templates->render('movieDeleteConfirm', ["movie" => GetMovieDetail($movieId)]));
}, "movieDeleteConfirm");

// Movie Delete - Acutally deletes the movie
$router->map('POST', '/delete/[i:movieId]', function ($movieId) use ($templates, $router, $adminId) {
  // Allow access to only admin account
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  DeleteMovie($movieId);
  header('Location: ' . $router->generate('movies'));
}, 'movieDelete');

// Genres - Displays all the genres
$router->map('GET', '/genres', function () use ($templates, $adminId) {
  // Allow access to only admin account
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  echo ($templates->render('genres', ["genres" => GetGenres()]));
}, "genres");

// Genre Add Form
$router->map('GET', '/genres/add', function () use ($templates, $adminId) {
  // Allow access to only admin account
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  echo ($templates->render('genreAddForm'));
}, "genreAddForm");

// Genre Add - Actually adds the genre
$router->map('POST', '/genres/add', function () use ($templates, $adminId, $router) {
  // Allow access to only admin account
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  $addGenreResult = AddGenre();
  if ($addGenreResult === true) {
    header('Location: ' . $router->generate('genres'));
  } else {
    echo $templates->render("genreAddForm", ["error" => $addGenreResult]);
  }
}, "genreAdd");

// Genre Update Form
$router->map('GET', '/genres/update/[i:genreId]', function ($genreId) use ($templates, $adminId) {
  // Allow access to only admin account
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  echo ($templates->render('genreUpdateForm', ["genre" => GetGenreDetail($genreId)]));
}, "genreUpdateForm");

// Genre Update - Actually updates the genre
$router->map('POST', '/genres/update/[i:genreId]', function ($genreId) use ($templates, $adminId, $router) {
  // Allow access to only admin account
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  $updateGenreResult = UpdateGenre($genreId);
  if ($updateGenreResult === true) {
    header('Location: ' . $router->generate('genres'));
  } else {
    echo $templates->render("genres", ["error" => $updateGenreResult]);
  }
}, "genreUpdate");

// Genre Delete Confirm
$router->map('GET', '/genres/delete/[i:genreId]', function ($genreId) use ($templates, $adminId) {
  // Allow access to only admin account
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  echo ($templates->render('genreDeleteConfirm', ["genre" => GetGenreDetail($genreId)]));
}, "genreDeleteConfirm");

// Genre Delete - Actually deletes the genre
$router->map('POST', '/genres/delete/[i:genreId]', function ($genreId) use ($templates, $adminId, $router) {
  // Allow access to only admin account
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  DeleteGenre($genreId);
  // Redirect to genres page
  header('Location: ' . $router->generate('genres'));
}, "genreDelete");

// Users - Displays all the users
$router->map('GET', '/users+list', function () use ($templates, $adminId) {
  // Allow access to only admin account
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  echo ($templates->render('users', ["users" => GetUsers()]));
}, "users");

// User Details for Admin
$router->map('GET', '/users/admin/[i:userId]', function ($userId) use ($templates, $adminId) {
  // Allow access to only admin account
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  $userData = GetUserDetail($userId);
  echo $templates->render("userDetailAdmin", ["user" => $userData['user'], "reviews" => $userData['reviews']]);
}, "userDetailAdmin");

// Users Delete Confirm
$router->map('GET', '/users/delete/[i:userId]', function ($userId) use ($templates, $adminId) {
  // Allow access to only admin account
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  echo ($templates->render('userDeleteConfirm', ["user" => GetUserDetail($userId)]));
}, "userDeleteConfirm");

// Users Delete - Actually delete the user
$router->map('POST', '/users/delete/[i:userId]', function ($userId) use ($templates, $adminId, $router) {
  // Allow access to only admin account
  session_start();
  if ($_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  DeleteUser($userId);
  // Redirect to users page
  header('Location: ' . $router->generate('users'));
}, "userDelete");

// Account Create Form
$router->map('GET', '/account/create', function () use ($templates) {
  echo ($templates->render('accountCreateForm'));
}, "accountCreateForm");

// Account Create - Actually creates the account
$router->map('POST', '/account/create', function () use ($templates, $router) {
  $createAccountResult = CreateAccount();
  if ($createAccountResult === true) {
    header('Location: ' . $router->generate('userDetail'));
  } else {
    echo $templates->render("accountCreateForm", ["error" => $createAccountResult]);
  }
}, "accountCreate");

// Updates Username & Email
$router->map('POST', '/account/update/[i:userId]', function ($userId) use ($templates, $router, $adminId) {
  // Check if the current user's ID matches the provided $userId, or if adminId
  session_start();
  if ($_SESSION['id'] != $userId && $_SESSION['id'] != $adminId) {
    echo ($templates->render('notfound'));
    exit();
  }
  $updateAccountResult = UsernameEmailUpdate($userId);
  if ($updateAccountResult === true) {
    if ($_SESSION['id'] != $adminId || ($_SESSION['id'] == $adminId && $userId == $adminId)) {
      // Redirects to user detail
      header('Location: ' . $router->generate('userDetail'));
    } else {
      // Redirects to users if admin updates someone else's account info
      header('Location: ' . $router->generate('users'));
    }
  } else {
    echo $templates->render("userDetailError", ["error" => $updateAccountResult]);
  }
}, "usernameEmailUpdate");

// Change Password Form
$router->map('GET', '/account/update/password/[i:userId]', function ($userId) use ($templates, $router) {
  // Check if the current user's ID matches the provided $userId
  session_start();
  if ($_SESSION['id'] != $userId) {
    echo ($templates->render('notfound'));
    exit();
  }
  echo $templates->render("changePasswordForm", ["userId" => $userId]);
}, "changePasswordForm");

// Change Password - Actually changes password
$router->map('POST', '/account/update/password/[i:userId]', function ($userId) use ($templates, $router) {
  // Check if the current user's ID matches the provided $userId
  session_start();
  if ($_SESSION['id'] != $userId) {
    echo ($templates->render('notfound'));
    exit();
  }
  $changePasswordResult = ChangePassword($userId);
  if ($changePasswordResult === true) {
    header('Location: ' . $router->generate('userDetail'));
  } else {
    echo $templates->render("userDetailError", ["error" => $changePasswordResult]);
  }
}, "changePassword");

// Forgot Password Form
$router->map('GET', '/forgotpassword', function () use ($templates) {
  echo ($templates->render('forgotPasswordForm'));
}, "forgotPasswordForm");

// Forgot Password - Generates a random token and sends and email with a link to change password
$router->map('POST', '/reset-password', function () use ($templates) {
  $msg = SendEmailTest();
  if ($msg === true) {
    echo ($templates->render('forgotPasswordSuccess', ['msg' => $msg]));
  } else {
    echo ($templates->render('forgotPasswordError', ['msg' => $msg]));
  }
}, "passwordReset");

// Change Password from email link - from forgot password process
$router->map('GET', '/reset-password/[i:userId]/[a:token]', function ($userId, $token) use ($templates) {
  // Check if the user ID exists and the token is valid
  if (ValidateResetPasswordForm($userId, $token)) {
    echo $templates->render('resetPasswordForm', ['userId' => $userId, 'token' => $token]);
  } else {
    echo $templates->render('notfound');
  }
}, 'resetPasswordForm');

// Reset Password - Resets the password after the forgot password process
$router->map('POST', '/account/reset/password/[i:userId]', function ($userId) use ($templates, $router) {
  $changePasswordResult = ResetPassword($userId);
  if ($changePasswordResult === true) {
    header('Location: ' . $router->generate('loginForm'));
  } else {
    echo $templates->render("resetPasswordFormError", ["error" => $changePasswordResult]);
  }
}, "resetPassword");

// Login Form
$router->map('GET', '/login', function () use ($templates) {
  echo ($templates->render('loginForm'));
}, "loginForm");

// Login - Actually logs the user in
$router->map('POST', '/login', function () use ($templates, $router) {
  $loginResult = Login();
  if ($loginResult === true) {
    // Check if the user is logged in
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
      // The user is logged in
      header('Location: ' . $router->generate('home'));
    } else {
      echo $templates->render("loginForm", ["error" => $loginResult]);
    }
  } else {
    echo $templates->render("loginForm", ["error" => $loginResult]);
  }
}, "login");

// Review Add - Actually adds the review
$router->map('POST', '/review/add/[i:movieId]', function ($movieId) use ($templates, $router) {
  $reviewAddResult = CreateReview(($movieId));
  if ($reviewAddResult === true) {
    header('Location: ' . $router->generate('movieDetail', ['movieId' => $movieId]));
  } else {
    echo ($templates->render('reviewAddError', ["error" => $reviewAddResult]));
  }
}, "reviewAdd");

// Review Update Form
$router->map('GET', '/review/update/[i:reviewId]', function ($reviewId) use ($templates, $adminId) {
  session_start();
  // Retrieve the review details including the userId
  $review = GetReviewDetail($reviewId);
  $reviewUserId = $review['UserId'];
  // Check if the current user's ID matches the userId of the review, or if it is adminId
  // Allow access to only userId of the review or admin
  if ($_SESSION['id'] != $reviewUserId  && $_SESSION['id'] != $adminId) {
    // IDs don't match, not found page
    echo ($templates->render('notfound'));
    exit();
  }
  echo ($templates->render('reviewUpdateForm', ["review" => GetReviewDetail($reviewId)]));
}, "reviewUpdateForm");

// Review Update - Actually updates the review
$router->map('POST', '/review/update/[i:reviewId]', function ($reviewId) use ($templates, $router, $adminId) {
  session_start();
  // Retrieve the review details including the userId
  $review = GetReviewDetail($reviewId);
  $reviewUserId = $review['UserId'];
  // Check if the current user's ID matches the userId of the review
  // Allow access to only userId of the review or admin
  if ($_SESSION['id'] != $reviewUserId && $_SESSION['id'] != $adminId) {
    // IDs don't match, not found page
    echo ($templates->render('notfound'));
    exit();
  }
  $reviewUpdateResult = UpdateReview($reviewId);
  if ($reviewUpdateResult === true) {
    if ($_SESSION['id'] != $adminId || ($_SESSION['id'] == $adminId && $reviewUserId == $adminId)) {
      header('Location: ' . $router->generate('userDetail'));
    } else {
      header('Location: ' . $router->generate('users'));
    }
  } else {
    echo ($templates->render('reviewUpdateError', ["error" => $reviewUpdateResult]));
  }
}, "reviewUpdate");

// Delete Review Confirm
$router->map('GET', '/review/delete/[i:reviewId]', function ($reviewId) use ($templates, $adminId) {
  session_start();
  // Retrieve the review details including the userId
  $review = GetReviewDetail($reviewId);
  $reviewUserId = $review['UserId'];
  // Check if the current user's ID matches the userId of the review
  // Allow access to only userId of the review or admin
  if ($_SESSION['id'] != $reviewUserId && $_SESSION['id'] != $adminId) {
    // IDs don't match, not found page
    echo ($templates->render('notfound'));
    exit();
  }
  // IDs match, render delete confirm page
  echo ($templates->render('reviewDeleteConfirm', ["review" => $review]));
}, "reviewDeleteConfirm");

// Review Delete - Actually deletes the review
$router->map('POST', '/review/delete/[i:reviewId]', function ($reviewId) use ($router, $templates, $adminId) {
  session_start();
  // Retrieve the review details including the userId
  $review = GetReviewDetail($reviewId);
  $reviewUserId = $review['UserId'];
  // Check if the current user's ID matches the userId of the review
  // Allow access to only userId of the review or admin
  if ($_SESSION['id'] != $reviewUserId && $_SESSION['id'] != $adminId) {
    // IDs don't match, not found page
    echo ($templates->render('notfound'));
    exit();
  }
  // IDs match, delete the review
  DeleteReview($reviewId);
  if ($_SESSION['id'] != $adminId || ($_SESSION['id'] == $adminId && $reviewUserId == $adminId)) {
    // Redirect to user details page
    header('Location: ' . $router->generate('userDetail'));
  } else {
    // Redirect to users table if it is adminId
    header('Location: ' . $router->generate('users'));
  }
}, 'reviewDelete');

// User Account Details
$router->map('GET', '/users', function () use ($templates) {
  session_start();
  if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
    $userData = GetUserDetail($userId);
    if ($userData) {
      echo $templates->render("userDetail", ["user" => $userData['user'], "reviews" => $userData['reviews']]);
    } else {
      echo ($templates->render('loginForm'));
    }
  } else {
    // User is not logged in or session ID is not set
    echo ($templates->render('loginForm'));
  }
}, "userDetail");

// Logout
$router->map('GET', '/logout', function () use ($router) {
  Logout();
  header('Location: ' . $router->generate('home'));
}, "logout");

/***    End Routes    ***/

// This has to be at the end of the page
$match = $router->match();
if (is_array($match) && is_callable($match['target'])) {
  // We found a route
  call_user_func_array($match['target'], $match['params']);
} else {
  // No route was matched
  // echo("No Route Found");
  echo ($templates->render("notfound"));
}
