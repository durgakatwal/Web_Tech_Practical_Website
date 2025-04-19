<?php
// Database configuration
$host = 'localhost';
$dbname = 'webtechlab';
$username = 'root';
$password = '';

// Create connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Create table if not exists
$createTable = "
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    course VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$pdo->exec($createTable);

// CRUD operations
$message = '';

// Create (Add new student)
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = $_POST['course'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO students (name, email, course) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $course]);
        $message = "Student added successfully!";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// Read (Fetch all students)
$students = $pdo->query("SELECT * FROM students ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// Update (Edit student)
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = $_POST['course'];
    
    try {
        $stmt = $pdo->prepare("UPDATE students SET name = ?, email = ?, course = ? WHERE id = ?");
        $stmt->execute([$name, $email, $course, $id]);
        $message = "Student updated successfully!";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// Delete (Remove student)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Student deleted successfully!";
        header("Location: crud.php");
        exit;
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// Get student for editing
$edit_student = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$id]);
    $edit_student = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD Operations</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .crud-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
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
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .action-links a {
            margin-right: 10px;
            text-decoration: none;
        }
        
        .edit-link {
            color: #3498db;
        }
        
        .delete-link {
            color: #e74c3c;
        }
        
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        
        .success {
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
            color: #3c763d;
        }
        
        .error {
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            color: #a94442;
        }
    </style>
</head>
<body>
    <header>
        <h1>PHP CRUD Operations</h1>
        <nav>
            <ul>
                <li><a href="../../index.html">Home</a></li>
                <li><a href="sessions.php">Sessions</a></li>
                <li><a href="crud.php">CRUD</a></li>
            </ul>
        </nav>
    </header>

    <main class="crud-container">
        <section>
            <h2><?php echo isset($edit_student) ? 'Edit Student' : 'Add New Student'; ?></h2>
            
            <?php if ($message): ?>
                <div class="message <?php echo strpos($message, 'Error') === false ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="crud.php">
                <?php if (isset($edit_student)): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_student['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" 
                           value="<?php echo isset($edit_student) ? $edit_student['name'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo isset($edit_student) ? $edit_student['email'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="course">Course:</label>
                    <select id="course" name="course" required>
                        <option value="">Select a course</option>
                        <option value="Computer Science" <?php echo (isset($edit_student) && $edit_student['course'] == 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                        <option value="Information Technology" <?php echo (isset($edit_student) && $edit_student['course'] == 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                        <option value="Data Science" <?php echo (isset($edit_student) && $edit_student['course'] == 'Data Science') ? 'selected' : ''; ?>>Data Science</option>
                        <option value="Cyber Security" <?php echo (isset($edit_student) && $edit_student['course'] == 'Cyber Security') ? 'selected' : ''; ?>>Cyber Security</option>
                    </select>
                </div>
                
                <button type="submit" name="<?php echo isset($edit_student) ? 'update' : 'add'; ?>">
                    <?php echo isset($edit_student) ? 'Update Student' : 'Add Student'; ?>
                </button>
                
                <?php if (isset($edit_student)): ?>
                    <a href="crud.php" style="margin-left: 10px;">Cancel</a>
                <?php endif; ?>
            </form>
        </section>
        
        <section>
            <h2>Student Records</h2>
            
            <?php if (count($students) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Course</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo $student['id']; ?></td>
                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                <td><?php echo htmlspecialchars($student['course']); ?></td>
                                <td><?php echo $student['created_at']; ?></td>
                                <td class="action-links">
                                    <a href="crud.php?edit=<?php echo $student['id']; ?>" class="edit-link">Edit</a>
                                    <a href="crud.php?delete=<?php echo $student['id']; ?>" class="delete-link" 
                                       onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No student records found.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Durga Katwal. All rights reserved.</p>
    </footer>
</body>
</html>