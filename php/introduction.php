<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Introduction</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header>
        <h1>PHP Introduction</h1>
        <nav>
            <ul>
                <li><a href="../../index.html">Home</a></li>
                <li><a href="introduction.php">PHP Intro</a></li>
                <li><a href="functions-arrays.php">Functions & Arrays</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Basic PHP Syntax</h2>
            
            <?php
            // Basic output
            echo "<p>This is PHP output using echo.</p>";
            
            // Variables
            $name = "John Doe";
            $age = 30;
            echo "<p>Name: $name, Age: $age</p>";
            
            // Constants
            define("SITE_NAME", "Web Tech Lab");
            echo "<p>Welcome to " . SITE_NAME . "</p>";
            ?>
            
            <h3>PHP in HTML</h3>
            <p>Current server time is: <?php echo date('Y-m-d H:i:s'); ?></p>
            
            <h3>Conditional Statements</h3>
            <?php
            $hour = date('H');
            if ($hour < 12) {
                echo "<p>Good morning!</p>";
            } elseif ($hour < 18) {
                echo "<p>Good afternoon!</p>";
            } else {
                echo "<p>Good evening!</p>";
            }
            ?>
            
            <h3>Loops</h3>
            <ul>
            <?php
            for ($i = 1; $i <= 5; $i++) {
                echo "<li>Item $i</li>";
            }
            ?>
            </ul>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Web Technology Lab. All rights reserved.</p>
    </footer>
</body>
</html>