<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Form Validation</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .error {
            color: red;
            font-size: 0.9em;
        }
        form {
            max-width: 500px;
            margin: 20px auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <h1>PHP Form Validation</h1>
        <nav>
            <ul>
                <li><a href="../../index.html">Home</a></li>
                <li><a href="form-validation.php">Form Validation</a></li>
                <li><a href="sessions.php">Sessions</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Form Validation Example</h2>
            
            <?php
            // Define variables and initialize with empty values
            $name = $email = $password = $confirm = $gender = $bio = "";
            $nameErr = $emailErr = $passwordErr = $confirmErr = $genderErr = "";
            
            // Form submission handling
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Validate name
                if (empty($_POST["name"])) {
                    $nameErr = "Name is required";
                } else {
                    $name = test_input($_POST["name"]);
                    // Check if name only contains letters and whitespace
                    if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
                        $nameErr = "Only letters and white space allowed";
                    }
                }
                
                // Validate email
                if (empty($_POST["email"])) {
                    $emailErr = "Email is required";
                } else {
                    $email = test_input($_POST["email"]);
                    // Check if email is valid
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $emailErr = "Invalid email format";
                    }
                }
                
                // Validate password
                if (empty($_POST["password"])) {
                    $passwordErr = "Password is required";
                } else {
                    $password = test_input($_POST["password"]);
                    if (strlen($password) < 6) {
                        $passwordErr = "Password must be at least 6 characters";
                    }
                }
                
                // Validate confirm password
                if (empty($_POST["confirm"])) {
                    $confirmErr = "Please confirm password";
                } else {
                    $confirm = test_input($_POST["confirm"]);
                    if ($password !== $confirm) {
                        $confirmErr = "Passwords do not match";
                    }
                }
                
                // Validate gender
                if (empty($_POST["gender"])) {
                    $genderErr = "Gender is required";
                } else {
                    $gender = test_input($_POST["gender"]);
                }
                
                // Validate bio
                $bio = test_input($_POST["bio"]);
                
                // If no errors, process the form
                if (empty($nameErr) && empty($emailErr) && empty($passwordErr) && empty($confirmErr) && empty($genderErr)) {
                    echo "<div style='background-color: #dff0d8; padding: 15px; margin: 20px 0; border: 1px solid #d6e9c6; border-radius: 4px;'>";
                    echo "<h3>Form Submitted Successfully!</h3>";
                    echo "<p>Name: $name</p>";
                    echo "<p>Email: $email</p>";
                    echo "<p>Gender: $gender</p>";
                    echo "<p>Bio: $bio</p>";
                    echo "</div>";
                    
                    // Reset form values
                    $name = $email = $password = $confirm = $gender = $bio = "";
                }
            }
            
            // Helper function to sanitize input
            function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            ?>
            
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo $name; ?>">
                    <span class="error"><?php echo $nameErr; ?></span>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>">
                    <span class="error"><?php echo $emailErr; ?></span>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password">
                    <span class="error"><?php echo $passwordErr; ?></span>
                </div>
                
                <div class="form-group">
                    <label for="confirm">Confirm Password:</label>
                    <input type="password" id="confirm" name="confirm">
                    <span class="error"><?php echo $confirmErr; ?></span>
                </div>
                
                <div class="form-group">
                    <label>Gender:</label>
                    <input type="radio" id="male" name="gender" value="male" <?php if ($gender == "male") echo "checked"; ?>>
                    <label for="male">Male</label>
                    
                    <input type="radio" id="female" name="gender" value="female" <?php if ($gender == "female") echo "checked"; ?>>
                    <label for="female">Female</label>
                    
                    <input type="radio" id="other" name="gender" value="other" <?php if ($gender == "other") echo "checked"; ?>>
                    <label for="other">Other</label>
                    <span class="error"><?php echo $genderErr; ?></span>
                </div>
                
                <div class="form-group">
                    <label for="bio">Bio:</label>
                    <textarea id="bio" name="bio" rows="4"><?php echo $bio; ?></textarea>
                </div>
                
                <button type="submit">Submit</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Web Technology Lab. All rights reserved.</p>
    </footer>
</body>
</html>