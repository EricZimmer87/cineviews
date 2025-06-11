<?php

function GetReadConnection()
{

    $info = parse_ini_file("user_read.ini");
    $servername = $info["servername"];
    $username = $info["username"];
    $password = $info["password"];
    $dbname = $info["dbname"];

    try {
        // Connects to database
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        //set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        // Displays default error message if connection fails
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}

function GetEditConnection()
{

    // Get information from the ini file and assign to variables
    $info = parse_ini_file("user_edit.ini");
    $servername = $info["servername"];
    $username = $info["username"];
    $password = $info["password"];
    $dbname = $info["dbname"];

    try {
        // Connects to database
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        //set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        // Displays default error message if connection fails
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}

function GetMovies()
{
    $conn = GetReadConnection();
    $sql = "SELECT Movies.*, GROUP_CONCAT(Genres.GenreName SEPARATOR ', ') AS Genre
        FROM Movies
        JOIN MovieGenre ON Movies.MovieId = MovieGenre.MovieId
        JOIN Genres ON MovieGenre.GenreId = Genres.GenreId
        GROUP BY Movies.MovieId;"; // SQL statement
    $prepare = $conn->prepare($sql); // Prepares sql statement
    $prepare->execute(); // Executes sql statement
    $movies = $prepare->fetchAll(PDO::FETCH_ASSOC); // Fetches data as associative array

    // Convert array to JSON for client-side sorting/filtering
    $json_data = json_encode($movies);

    return $json_data;
}

function SearchMovies($searchQuery)
{
    $conn = GetReadConnection();
    $sql = "SELECT * FROM Movies WHERE MovieTitle LIKE :search";
    $prepare = $conn->prepare($sql);
    $prepare->execute([':search' => "%$searchQuery%"]);
    $movies = $prepare->fetchAll(PDO::FETCH_ASSOC);
    // Convert array to JSON for client-side sorting/filtering
    $json_data = json_encode($movies);

    return $json_data;
}

function GetGenres()
{
    $conn = GetReadConnection();
    $sql = "SELECT * FROM Genres ORDER BY GenreName ASC";
    $prepare = $conn->query($sql);
    $genres = $prepare->fetchAll(PDO::FETCH_ASSOC);
    return $genres;
}

function GetGenreDetail($genreId)
{
    $conn = GetReadConnection();
    $sql = "SELECT * FROM Genres WHERE GenreId = ?";
    $prepare = $conn->prepare($sql);
    $prepare->execute(array($genreId));
    $genre = $prepare->fetch();
    return $genre;
}

function AddGenre()
{
    $conn = GetEditConnection();
    $genreName = $_POST['genre-name'];
    if (empty($genreName)) {
        return "You must enter a genre name.";
    }
    $sql = "INSERT INTO Genres (GenreName) VALUES (?)";
    $prepare = $conn->prepare($sql);
    $prepare->execute(array($genreName));
    return true;
}

function UpdateGenre($genreId)
{
    $conn = GetEditConnection();
    $genreName = $_POST['genre-name'];

    if (empty($genreName)) {
        return "Please fill out the genre name.";
    } else {
        $sql = "UPDATE Genres SET `GenreName` = ? WHERE `GenreId` = ?";
        $prepare = $conn->prepare($sql);
        $prepare->execute([$genreName, $genreId]);
        return true;
    }
}

function DeleteGenre($genreId)
{
    $conn = GetEditConnection();
    $sql = "DELETE FROM Genres WHERE GenreId = ?";
    $prepare = $conn->prepare($sql);
    $prepare->execute(array($genreId));
}

function GetUsers()
{
    $conn = GetReadConnection();
    $sql = "SELECT * FROM Users";
    $prepare = $conn->query($sql);
    $users = $prepare->fetchAll(PDO::FETCH_ASSOC);
    return $users;
}

function DeleteUser($userId)
{
    $conn = GetEditConnection();
    $sql = "DELETE FROM Users WHERE UserId = ?";
    $prepare = $conn->prepare($sql);
    $prepare->execute(array($userId));
}

function GetMovieDetail($movieId)
{
    $conn = GetReadConnection();

    // Retrieves movie details, including Genres, AverageScore
    $sql = "SELECT Movies.*,
        GROUP_CONCAT(DISTINCT Genres.GenreName SEPARATOR ', ') AS Genres,
        ROUND(AVG(Reviews.Score), 1) AS AverageScore
        FROM 
        Movies
        LEFT JOIN 
        MovieGenre ON Movies.MovieId = MovieGenre.MovieId
        LEFT JOIN 
        Genres ON MovieGenre.GenreId = Genres.GenreId
        LEFT JOIN 
        Reviews ON Movies.MovieId = Reviews.MovieId
        WHERE 
        Movies.MovieId = ?
        GROUP BY 
        Movies.MovieId,
        Movies.MovieTitle,
        Movies.ReleaseDate,
        Movies.Art,
        Movies.IsSeries;";
    $prepare = $conn->prepare($sql);
    $prepare->execute(array($movieId));
    $movie = $prepare->fetch();

    // Retrieves all reviews for the movie to put into separate array
    $reviewsSql = "SELECT Reviews.*, Users.UserName AS ReviewerName 
        FROM Reviews 
        LEFT JOIN Users ON Reviews.UserId = Users.UserId 
        WHERE Reviews.MovieId = ?";
    $reviewsPrepare = $conn->prepare($reviewsSql);
    $reviewsPrepare->execute(array($movieId));
    $reviews = $reviewsPrepare->fetchAll();

    // Add reviews to the movie array
    $movie['Reviews'] = $reviews;

    return $movie;
}

function CreateMovie()
{
    $conn = GetEditConnection();
    // Retrieve form data
    $title = $_POST['movie-title'];
    // Convert release date to YYYY-MM-DD format
    $releaseDateFormatted = date('Y-m-d', strtotime($_POST['release-date']));
    $art = $_POST['art'];
    $isSeries = $_POST['is-series'];
    $genres = $_POST['genres'];

    // Check if required fields are not empty
    if (empty($title) || empty($releaseDateFormatted) || empty($art) || empty($isSeries) || empty($genres)) {
        return "Please fill out all fields.";
    }

    // Check if the movie title already exists
    $sqlCheck = "SELECT COUNT(*) AS count FROM Movies WHERE MovieTitle = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->execute([$title]);
    $resultCheck = $stmtCheck->fetch(PDO::FETCH_ASSOC);
    if ($resultCheck['count'] > 0) {
        return "Movie already exists.";
    }

    // Convert $isSeries to 1 if yes or 0 if no
    $isSeriesValue = ($isSeries == "Yes") ? 1 : 0;

    try {
        // SQL statement to insert movie details
        $sql = "INSERT INTO Movies (`MovieTitle`, `ReleaseDate`, `Art`, `IsSeries`) VALUES (?, ?, ?, ?)";
        $prepare = $conn->prepare($sql);
        $prepare->execute([$title, $releaseDateFormatted, $art, $isSeriesValue]);

        // Retrieve the ID of the newly inserted movie
        $movieId = $conn->lastInsertId();

        // SQL statement to insert movie-genre
        $sql = "INSERT INTO MovieGenre (`MovieId`, `GenreId`) VALUES (?, ?)";
        $prepare = $conn->prepare($sql);

        // Add each genre for the movie
        foreach ($genres as $genreId) {
            $prepare->execute([$movieId, $genreId]);
        }

        return true;
    } catch (Exception $e) {
        return "An error occurred while adding the movie.";
    }
}

function UpdateMovie($movieId)
{
    $conn = GetEditConnection();
    $title = $_POST['movie-title'];
    // Convert release date to YYYY-MM-DD format
    $releaseDateFormatted = date('Y-m-d', strtotime($_POST['release-date']));
    $art = $_POST['art'];
    $isSeries = $_POST['is-series'];

    // Check if required fields are not empty
    if (empty($title) || empty($releaseDateFormatted) || empty($art) || empty($isSeries)) {
        return "Please fill out all fields.";
    }
    // Convert $isSeries to 1 if yes or 0 if no
    $isSeriesValue = ($isSeries == "Yes") ? 1 : 0;

    // SQL to update MovieTitle, ReleaseDate, Art, and IsSeries
    $sql = "UPDATE Movies SET `MovieTitle` = ?, `ReleaseDate` = ?, `Art` = ?, `IsSeries` = ? WHERE `MovieId` = ?";
    $prepare = $conn->prepare($sql);
    $prepare->execute([$title, $releaseDateFormatted, $art, $isSeriesValue, $movieId]);
    return true;
}

function UpdateMovieGenre($movieId)
{
    $selectedGenres = $_POST['genres'];
    if (empty($selectedGenres)) {
        return "At least one genre must be selected.";
    }

    try {
        $conn = GetEditConnection();

        // Make transaction in case of interruption or error
        $conn->beginTransaction();

        // Delete existing genres for the movie
        $deleteSql = "DELETE FROM MovieGenre WHERE MovieId = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->execute([$movieId]);

        // Insert new genres for the movie
        foreach ($selectedGenres as $genreId) {
            $insertSql = "INSERT INTO MovieGenre (MovieId, GenreId) VALUES (?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->execute([$movieId, $genreId]);
        }

        // Commit the transaction
        $conn->commit();

        // Success!
        return true;
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        return $e;
    }
}

function DeleteMovie($movieId)
{
    $conn = GetEditConnection();
    $sql = "DELETE FROM Movies WHERE MovieId = ?";
    $prepare = $conn->prepare($sql);
    $prepare->execute(array($movieId));
}

function CreateAccount()
{
    $conn = GetEditConnection();
    $userName = $_POST['username'];
    $userEmail = $_POST['email'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password-confirm'];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    // Check if username length is greater than 30 characters
    if (strlen($userName) > 30) {
        return "Username must be 30 characters or less.";
    }
    // Check if password and password confirm match
    if ($password !== $passwordConfirm) {
        return "Passwords do not match.";
    }
    // Validate email format
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }
    // Check if required fields are empty
    if (empty($userName) || empty($userEmail)  ||  empty($passwordHash) || empty($passwordConfirm)) {
        return "Please fill out all fields.";
    }
    // Validate password strength
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        return "Password must be at least 8 characters long, contain at least one special character (@$!%*?&), one number, one capital letter, and one lowercase letter.";
    }
    // Check if the username already exists
    $prepare = $conn->prepare("SELECT UserId FROM Users WHERE UserName = ?");
    $prepare->execute([$userName]);
    $existingUser = $prepare->fetch();
    if ($existingUser) {
        return "Username already exists. Please choose a different username.";
    } else {
        // Input validation passed, create account
        $sql = "INSERT INTO Users (`UserName`, `UserEmail`, `PasswordHash`) VALUES (?, ?, ?)";
        $prepare = $conn->prepare($sql);
        $prepare->execute([$userName, $userEmail, $passwordHash]);
        // Log the user in after creating the account
        $lastInsertedId = $conn->lastInsertId();
        session_start();
        session_set_cookie_params(3600); // One hour cookie expiry
        $_SESSION['loggedin'] = true;
        $_SESSION['id'] = $lastInsertedId;
        $_SESSION['username'] = $userName;
        return true;
    }
}

function ChangePassword($userId)
{
    $conn = GetEditConnection();
    $currentPassword = $_POST['current-password'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password-confirm'];

    $prepare = $conn->prepare("SELECT UserId, PasswordHash FROM Users WHERE UserId = :userId");
    $prepare->execute(['userId' => $userId]);
    $user = $prepare->fetch();
    $hashed_password = $user['PasswordHash'];
    // Verify the current password
    if (!password_verify($currentPassword, $hashed_password)) {
        return "Incorrect password.";
    }
    // Check if new password and password confirm match
    if ($password !== $passwordConfirm) {
        return "Passwords do not match.";
    }
    // Validate password strength
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        return "Password must be at least 8 characters long, contain at least one special character (@$!%*?&), one number, one capital letter, and one lowercase letter.";
    }
    // Update password
    $newHashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE Users SET PasswordHash = ? WHERE UserId = ?";
    $prepare = $conn->prepare($sql);
    $prepare->execute([$newHashedPassword, $userId]);

    return true;
}

function ValidateResetPasswordForm($userId, $token)
{
    $conn = GetReadConnection();

    // Check if the user exists and the token is valid
    $sql = $conn->prepare("SELECT UserId, ResetTokenExpiry FROM Users WHERE UserId = ? AND ResetToken = ?");
    $sql->execute([$userId, $token]);
    $user = $sql->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // User doesn't exist
        return false;
    }

    // Check if the token is expired
    $expiryTime = strtotime($user['ResetTokenExpiry']);
    if ($expiryTime < time()) {
        // Token expired
        return false;
    }

    return true;
}

function ResetPassword($userId)
{
    $conn = GetEditConnection();
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password-confirm'];

    // Check if new password and password confirm match
    if ($password !== $passwordConfirm) {
        return "Passwords do not match.";
    }
    // Validate password strength
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        return "Password must be at least 8 characters long, contain at least one special character (@$!%*?&), one number, one capital letter, and one lowercase letter.";
    }
    // Update password & delete token
    $newHashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash new password
    $sql = "UPDATE Users SET PasswordHash = ?, ResetToken = NULL, ResetTokenExpiry = NULL WHERE UserId = ?";
    $prepare = $conn->prepare($sql);
    $prepare->execute([$newHashedPassword, $userId]);

    return true;
}

function generateResetToken($conn, $email)
{
    try {
        // Get the user ID based on the email
        $stmt = $conn->prepare("SELECT UserId FROM Users WHERE UserEmail = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $userId = $row['UserId'];

        // Generate a unique token
        $token = bin2hex(random_bytes(16));

        // Store the token and expiry time in the database
        $expiryTime = date('Y-m-d H:i:s', strtotime('+10 minutes')); // Token expires after 10 minutes
        $stmt = $conn->prepare("UPDATE Users SET ResetToken = ?, ResetTokenExpiry = ? WHERE UserId = ?");
        $stmt->execute([$token, $expiryTime, $userId]);

        return ['userId' => $userId, 'token' => $token];
    } catch (Exception $e) {
        // Log or handle the exception
        error_log("Failed to generate reset token: " . $e->getMessage());
        return false;
    }
}

function sendPasswordResetEmail($to, $userId, $token)
{
    // Compose the email body with the reset link
    $resetLink = "http://localhost/CineViews/reset-password/$userId/$token";
    $subject = "Password Reset";
    $message = "Click the following link to reset your password: $resetLink";
    $headers = "From: eric85276@cccneb.edu";

    // Send the email
    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        // Log or handle email sending failure
        error_log("Failed to send password reset email to $to");
        return false;
    }
}

function SendEmailTest()
{
    // Connect to database
    $conn = GetEditConnection();
    if (!$conn) {
        return "Failed to connect to database.";
    }

    // Get email from form
    $to = $_POST['email'];

    // Validate email format
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // Check if the user email exists
    $prepare = $conn->prepare("SELECT UserId FROM Users WHERE UserEmail = ?");
    $prepare->execute([$to]);
    $existingUser = $prepare->fetch();
    if (!$existingUser) {
        return "Invalid email address.";
    }

    // Generate reset token and get userId
    $result = generateResetToken($conn, $to);
    if (!$result) {
        return "Failed to generate reset token.";
    }
    $userId = $result['userId'];
    $token = $result['token'];

    // Send password reset email
    if (sendPasswordResetEmail($to, $userId, $token)) {
        return true;
    } else {
        return "Failed to send email.";
    }
}

function UsernameEmailUpdate($userId)
{
    $conn = GetEditConnection();
    $userName = $_POST['username'];
    $userEmail = $_POST['email'];

    // Check if username and email are not empty
    if (empty($_POST['username']) || empty($_POST['email'])) {
        $error = "Username and email fields must be filled out.";
        return $error;
    }

    // Validate email format
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
        return $error;
    }

    // Check if the username already exists
    $prepare = $conn->prepare("SELECT UserId FROM Users WHERE UserName = ? AND UserId != ?");
    $prepare->execute([$userName, $userId]);
    $existingUser = $prepare->fetch();
    if ($existingUser) {
        return "Username already exists. Please choose a different username.";
    }

    // Input validation passed, create account
    $sql = "UPDATE Users SET UserName = ?, UserEmail = ? WHERE UserId = ?";
    $prepare = $conn->prepare($sql);
    $prepare->execute([$userName, $userEmail, $userId]);

    // Update the session data so the name changes in the header on the master template
    session_start();
    // In the event of admin changing username/email, do not update session name
    if ($_SESSION['id'] != 26) {
        $_SESSION['username'] = $userName;
    }

    return true;
}

function Login()
{
    $conn = GetReadConnection();
    // Get the form data
    $username = trim($_POST['username']); // Remove leading & trailing spaces
    $password = $_POST['password'];
    $prepare = $conn->prepare("SELECT UserId, PasswordHash FROM Users WHERE UserName = :username");
    $prepare->execute(['username' => $username]);
    $user = $prepare->fetch();
    // Check if the user exists
    if ($user) {
        $id = $user['UserId'];
        $hashed_password = $user['PasswordHash'];

        // Verify the password
        if (password_verify($password, $hashed_password)) {

            session_start();
            // Set session cookie lifetime to 1 hour
            session_set_cookie_params(3600);
            // Set the session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $id;
            $_SESSION['username'] = $username;
            return true;
        } else {
            // Same error for either username or password for enhanced security.
            return "The username or password you entered was incorrect.";
        }
    } else {
        // Same error for either username or password for enhanced security.
        return "The username or password you entered was incorrect.";
    }
}

function Logout()
{
    session_start();

    // Unset all session variables
    $_SESSION = array();

    // Destroy the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000, '/');
    }

    // Destroy the session
    session_destroy();
}

function CreateReview($movieId)
{
    $conn = GetEditConnection();

    // Sanitize and validate input
    $score = (float) $_POST['score'];
    $reviewText = trim($_POST['review-text']);
    session_start();
    // Make sure there is a user logged in before creating a review
    if (!isset($_SESSION['id'])) {
        return "You must be logged in to submit a review.";
    }
    $userId = $_SESSION['id'];

    // Check if score is within valid range
    if (!is_numeric($score) || $score < 0 || $score > 5) {
        return "Score must be a number between 0.0 and 5.0.";
    }

    // Check if required fields are not empty
    if (!isset($score) || empty($reviewText)) {
        return "Score and review must be filled out.";
    }

    // Check if the user has already submitted a review for the same movie
    $checkSql = "SELECT COUNT(*) FROM Reviews WHERE MovieId = ? AND UserId = ?";
    $checkPrepare = $conn->prepare($checkSql);
    $checkPrepare->execute([$movieId, $userId]);
    $existingReviewCount = $checkPrepare->fetchColumn();

    if ($existingReviewCount > 0) {
        return "You have already submitted a review for this movie.";
    }

    // Add the review
    $sql = "INSERT INTO Reviews (`MovieId`, `UserId`, `Score`, `ReviewText`) VALUES (?, ?, ?, ?)";
    $prepare = $conn->prepare($sql);
    $prepare->execute([$movieId, $userId, $score, $reviewText]);

    return true;
}

function GetReviewDetail($reviewId)
{
    $conn = GetReadConnection();
    $sql = "SELECT Reviews.*, Movies.MovieTitle 
            FROM Reviews 
            LEFT JOIN Movies ON Reviews.MovieId = Movies.MovieId 
            WHERE ReviewId = ?";
    $prepare = $conn->prepare($sql);
    $prepare->execute(array($reviewId));
    $review = $prepare->fetch();
    return $review;
}

function UpdateReview($reviewId)
{
    $conn = GetEditConnection();
    $score = $_POST['score'];
    $reviewText = $_POST['review-text'];

    // Check if score is within valid range
    if (!is_numeric($score) || $score < 0 || $score > 5) {
        return "Score must be a number between 0.0 and 5.0.";
    }

    // Check if required fields are not empty
    if (!isset($score) || empty($reviewText)) {
        $error = "Score and review fields must be filled out.";
        return $error;
    } else {
        $sql = "UPDATE Reviews SET `Score` = ?, `ReviewText` = ? WHERE `ReviewId` = ?";
        $prepare = $conn->prepare($sql);
        $prepare->execute([$score, $reviewText, $reviewId]);
        return true;
    }
}

function DeleteReview($reviewId)
{
    $conn = GetEditConnection();
    $sql = "DELETE FROM Reviews WHERE ReviewId = ?";
    $prepare = $conn->prepare($sql);
    $prepare->execute(array($reviewId));
}

function GetUserDetail($userId)
{
    $conn = GetReadConnection();

    // Retrieve user information
    $userSql = "SELECT * FROM Users WHERE UserId = ?";
    $userPrepare = $conn->prepare($userSql);
    $userPrepare->execute(array($userId));
    $user = $userPrepare->fetch();

    // Retrieve reviews for the user
    $reviewsSql = "SELECT Reviews.*, Movies.MovieTitle
                   FROM Reviews
                   LEFT JOIN Movies ON Reviews.MovieId = Movies.MovieId
                   WHERE Reviews.UserId = ?";
    $reviewsPrepare = $conn->prepare($reviewsSql);
    $reviewsPrepare->execute(array($userId));
    $reviews = $reviewsPrepare->fetchAll();

    // Return an array for user data and review data
    return array('user' => $user, 'reviews' => $reviews);
}
