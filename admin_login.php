<!-- admin_login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Add the Google Fonts link here -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin_login.css">
</head>
<body>
    <div class="login-container">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message" style="color: red; font-weight: bold;">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// admin_login.php: Admin login page
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Authenticate the user
    if ($username === 'admin' && $password === 'techblitz') {
        $_SESSION['loggedin'] = true;
        header("Location: admin.html");
        exit;
    } else {
        $error = "Incorrect username or password. Please try again.";
        // Redirect back to the login form with an error message
        header("Location: admin_login.php?error=" . urlencode($error));
        exit;
    }
}
?>