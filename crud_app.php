<?php
// PHP Introduction, Basic Syntax, Session
session_start();
$host = "localhost";
$user = "root";
$pass = "password";
$dbname = "demo_db";
$conn = new mysqli($host, $user, $pass, $dbname);

// Connection check
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function Example
function showMessage($msg) {
    echo "<p style='color:green;'>$msg</p>";
}

// Handle Insert
if (isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    // Form validation
    if ($name == "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['msg'] = "Invalid form data!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $email);
        $stmt->execute();
        $_SESSION['msg'] = "User added successfully!";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
    $_SESSION['msg'] = "User deleted.";
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Handle Edit Fetch
$edit_mode = false;
$edit_data = [];
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = $_GET['edit'];
    $res = $conn->query("SELECT * FROM users WHERE id=$id");
    $edit_data = $res->fetch_assoc();
}

// Handle Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if ($name == "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['msg'] = "Invalid form data!";
    } else {
        $stmt = $conn->prepare("UPDATE users SET name=?, email=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $email, $id);
        $stmt->execute();
        $_SESSION['msg'] = "User updated successfully!";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// Array Example
$user_roles = ['Admin', 'Editor', 'Subscriber'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP CRUD App</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f2f2f2; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; }
        input, button { padding: 8px; margin-top: 10px; width: 100%; }
        form { background: #fff; padding: 20px; border-radius: 6px; }
        .msg { color: red; }
    </style>
</head>
<body>

    <h2>PHP CRUD Application</h2>
    
    <?php
    // Show message
    if (isset($_SESSION['msg'])) {
        echo "<div class='msg'>" . $_SESSION['msg'] . "</div>";
        unset($_SESSION['msg']);
    }
    ?>

    <!-- User Form -->
    <form method="POST">
        <input type="hidden" name="id" value="<?= $edit_mode ? $edit_data['id'] : '' ?>">
        <label>Name:</label>
        <input type="text" name="name" value="<?= $edit_mode ? $edit_data['name'] : '' ?>" required>
        <label>Email:</label>
        <input type="text" name="email" value="<?= $edit_mode ? $edit_data['email'] : '' ?>" required>
        <button type="submit" name="<?= $edit_mode ? 'update' : 'add' ?>">
            <?= $edit_mode ? 'Update' : 'Add' ?> User
        </button>
    </form>

    <!-- User Table -->
    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Created At</th><th>Actions</th>
        </tr>
        <?php
        $res = $conn->query("SELECT * FROM users ORDER BY id DESC");
        while ($row = $res->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <a href="?edit=<?= $row['id'] ?>">Edit</a> | 
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this user?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Array display -->
    <h3>User Roles (Array Example)</h3>
    <ul>
        <?php foreach ($user_roles as $role): ?>
            <li><?= $role ?></li>
        <?php endforeach; ?>
    </ul>

</body>
</html>
