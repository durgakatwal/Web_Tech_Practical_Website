<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set content type to HTML with UTF-8
header('Content-Type: text/html; charset=utf-8');

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Initialize variables
$errors = [];
$formData = [
    'name' => '',
    'email' => '',
    'password' => '', // Note: In real applications, never store raw passwords
    'phone' => '',
    'dob' => '',
    'gender' => '',
    'interests' => [],
    'subscription' => 'monthly',
    'bio' => '',
    'file' => ''
];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize each field
    
    // Name
    if (empty($_POST['name'])) {
        $errors['name'] = 'Name is required';
    } else {
        $formData['name'] = sanitizeInput($_POST['name']);
        if (!preg_match("/^[a-zA-Z ]*$/", $formData['name'])) {
            $errors['name'] = 'Only letters and white space allowed';
        }
    }
    
    // Email
    if (empty($_POST['email'])) {
        $errors['email'] = 'Email is required';
    } else {
        $formData['email'] = sanitizeInput($_POST['email']);
        if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }
    }
    
    // Password
    if (empty($_POST['password'])) {
        $errors['password'] = 'Password is required';
    } else {
        $formData['password'] = $_POST['password']; // In real app, you would hash this
        if (strlen($formData['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        }
    }
    
    // Phone
    if (!empty($_POST['phone'])) {
        $formData['phone'] = sanitizeInput($_POST['phone']);
        if (!preg_match("/^[0-9]{10}$/", $formData['phone'])) {
            $errors['phone'] = 'Phone number must be 10 digits';
        }
    }
    
    // Date of Birth
    if (!empty($_POST['dob'])) {
        $formData['dob'] = sanitizeInput($_POST['dob']);
        // You could add additional date validation here
    }
    
    // Gender
    if (!empty($_POST['gender'])) {
        $formData['gender'] = sanitizeInput($_POST['gender']);
        if (!in_array($formData['gender'], ['male', 'female', 'other'])) {
            $errors['gender'] = 'Invalid gender selection';
        }
    }
    
    // Interests (checkboxes)
    if (!empty($_POST['interests'])) {
        $allowedInterests = ['web', 'data', 'mobile'];
        foreach ($_POST['interests'] as $interest) {
            $cleanInterest = sanitizeInput($interest);
            if (in_array($cleanInterest, $allowedInterests)) {
                $formData['interests'][] = $cleanInterest;
            }
        }
    }
    
    // Subscription (radio buttons)
    if (!empty($_POST['subscription'])) {
        $formData['subscription'] = sanitizeInput($_POST['subscription']);
        if (!in_array($formData['subscription'], ['monthly', 'yearly'])) {
            $errors['subscription'] = 'Invalid subscription selection';
        }
    }
    
    // Bio
    if (!empty($_POST['bio'])) {
        $formData['bio'] = sanitizeInput($_POST['bio']);
    }
    
    // File upload handling
    if (!empty($_FILES['file']['name'])) {
        $file = $_FILES['file'];
        
        // File upload configuration
        $uploadDir = 'uploads/';
        $maxFileSize = 2 * 1024 * 1024; // 2MB
        $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        
        // Create uploads directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = basename($file['name']);
        $filePath = $uploadDir . uniqid() . '_' . $fileName;
        $fileType = $file['type'];
        $fileSize = $file['size'];
        
        // Validate file
        if ($fileSize > $maxFileSize) {
            $errors['file'] = 'File size exceeds 2MB limit';
        } elseif (!in_array($fileType, $allowedTypes)) {
            $errors['file'] = 'Only PDF and Word documents are allowed';
        } elseif (move_uploaded_file($file['tmp_name'], $filePath)) {
            $formData['file'] = $filePath;
        } else {
            $errors['file'] = 'Error uploading file';
        }
    }
    
    // If no errors, process the form
    if (empty($errors)) {
        // In a real application, you would typically:
        // 1. Hash the password
        // 2. Store data in a database
        // 3. Send email notifications
        // 4. Redirect to a success page
        
        // For this example, we'll just display the submitted data
        $submissionSuccess = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submission Result</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .form-result {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .error {
            color: #e74c3c;
            font-weight: bold;
        }
        .success {
            color: #2ecc71;
            font-weight: bold;
        }
        .form-data {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-data dt {
            font-weight: bold;
            margin-top: 10px;
        }
        .form-data dd {
            margin-left: 0;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Form Submission Result</h1>
        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/tables-forms.html">Back to Form</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="form-result">
            <?php if (!empty($errors)): ?>
                <h2 class="error">Form Submission Failed</h2>
                <p>Please correct the following errors:</p>
                <ul>
                    <?php foreach ($errors as $field => $error): ?>
                        <li><strong><?php echo ucfirst($field); ?>:</strong> <?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
                <p><a href="/tables-forms.html">Go back to the form</a></p>
            <?php elseif (isset($submissionSuccess)): ?>
                <h2 class="success">Form Submitted Successfully!</h2>
                <p>Thank you for your submission. Here's what we received:</p>
                
                <div class="form-data">
                    <dl>
                        <dt>Full Name:</dt>
                        <dd><?php echo $formData['name']; ?></dd>
                        
                        <dt>Email:</dt>
                        <dd><?php echo $formData['email']; ?></dd>
                        
                        <dt>Password:</dt>
                        <dd>******** (hidden for security)</dd>
                        
                        <?php if (!empty($formData['phone'])): ?>
                            <dt>Phone Number:</dt>
                            <dd><?php echo $formData['phone']; ?></dd>
                        <?php endif; ?>
                        
                        <?php if (!empty($formData['dob'])): ?>
                            <dt>Date of Birth:</dt>
                            <dd><?php echo $formData['dob']; ?></dd>
                        <?php endif; ?>
                        
                        <?php if (!empty($formData['gender'])): ?>
                            <dt>Gender:</dt>
                            <dd><?php echo ucfirst($formData['gender']); ?></dd>
                        <?php endif; ?>
                        
                        <?php if (!empty($formData['interests'])): ?>
                            <dt>Interests:</dt>
                            <dd>
                                <ul>
                                    <?php foreach ($formData['interests'] as $interest): ?>
                                        <li><?php echo ucfirst($interest); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </dd>
                        <?php endif; ?>
                        
                        <dt>Subscription:</dt>
                        <dd><?php echo ucfirst($formData['subscription']); ?></dd>
                        
                        <?php if (!empty($formData['bio'])): ?>
                            <dt>Bio:</dt>
                            <dd><?php echo nl2br($formData['bio']); ?></dd>
                        <?php endif; ?>
                        
                        <?php if (!empty($formData['file'])): ?>
                            <dt>Resume:</dt>
                            <dd>
                                <a href="<?php echo $formData['file']; ?>" target="_blank">Download uploaded file</a>
                            </dd>
                        <?php endif; ?>
                    </dl>
                </div>
                
                <p><a href="/tables-forms.html">Submit another form</a></p>
            <?php else: ?>
                <h2 class="error">Invalid Form Submission</h2>
                <p>This page should only be accessed after submitting the form.</p>
                <p><a href="/tables-forms.html">Go to the form</a></p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Web Technology Lab. All rights reserved.</p>
    </footer>
</body>
</html>