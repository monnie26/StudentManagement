<?php 
session_start();
require "db_connect.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == "student") {
            header("Location: dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        $message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="form-container">
    <h2>Login</h2>

    <?php if ($message) echo "<p style='color:red;'>$message</p>"; ?>

    <form method="POST">
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>

        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
