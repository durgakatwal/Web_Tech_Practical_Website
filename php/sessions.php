<?php
// Start the session
session_start();

// Initialize session variables if not set
if (!isset($_SESSION['page_views'])) {
    $_SESSION['page_views'] = 0;
}
$_SESSION['page_views']++;

if (!isset($_SESSION['user_data'])) {
    $_SESSION['user_data'] = array(
        'username' => '',
        'logged_in' => false
    );
}

// Handle login form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Simple authentication (in real app, use database and password hashing)
    if ($username === 'admin' && $password === 'password') {
        $_SESSION['user_data']['username'] = $username;
        $_SESSION['user_data']['logged_in'] = true;
    } else {
        $login_error = "Invalid username or password";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: sessions.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Sessions</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .session-info {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .login-form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <header>
        <h1>PHP Sessions</h1>
        <nav>
            <ul>
                <li><a href="../../index.html">Home</a></li>
                <li><a href="sessions.php">Sessions</a></li>
                <li><a href="crud.php">CRUD</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Session Information</h2>
            
            <div class="session-info">
                <p>Session ID: <?php echo session_id(); ?></p>
                <p>Page Views: <?php echo $_SESSION['page_views']; ?></p>
                
                <?php if ($_SESSION['user_data']['logged_in']): ?>
                    <p>Welcome, <?php echo $_SESSION['user_data']['username']; ?>!</p>
                    <p><a href="sessions.php?logout=1">Logout</a></p>
                <?php else: ?>
                    <p>You are not logged in.</p>
                <?php endif; ?>
            </div>
            
            <?php if (!$_SESSION['user_data']['logged_in']): ?>
                <div class="login-form">
                    <h3>Login Form</h3>
                    
                    <?php if (isset($login_error)): ?>
                        <p class="error"><?php echo $login_error; ?></p>
                    <?php endif; ?>
                    
                    <form method="post" action="sessions.php">
                        <div>
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" required>
                        </div>
                        
                        <div>
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        
                        <button type="submit" name="login">Login</button>
                    </form>
                </div>
            <?php endif; ?>
            
            <h3>Session Data</h3>
            <pre>
<?php print_r($_SESSION); ?>
            </pre>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Web Technology Lab. All rights reserved.</p>
    </footer>
</body>
</html>