<?php 
require_once('db_connect.php');

session_start();
if ($_SESSION['role'] != 'faculty') {
    header("Location: login.php");
    exit;
}
include "navbar.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['className'])) {
    $classname = trim($_POST['className']);
    if ($classname === '') {
        echo "<script>alert('Please enter a class name');</script>";
    } else {
        $check = $conn->prepare("SELECT class_id FROM class WHERE class_name = ?");
        $check->bind_param("s", $classname);
        $check->execute();
        $result = $check->get_result();

        if ($result && $result->num_rows > 0) {
            echo "<script>alert('Class already exists!');</script>";
        } else {
            $stmt = $conn->prepare("INSERT INTO class(class_name) VALUES(?)");
            $stmt->bind_param("s", $classname);
            if ($stmt->execute()) {
                echo "<script>alert('Class added successfully!'); window.location.href='class.php';</script>";
            } else {
                echo "<script>alert('Failed to add class.');</script>";
            }
            $stmt->close();
        }
        $check->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_class'])) {
    $id = $_POST['delete_class'];
    $stmt = $conn->prepare("DELETE FROM class WHERE class_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Class deleted'); window.location.href='class.php';</script>";
    } else {
        echo "<script>alert('Cannot delete! Class is assigned to students.');</script>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_class'])) {

    $id = $_POST['update_id'];
    $name = trim($_POST['update_name']);
    $stmt = $conn->prepare("UPDATE class SET class_name=? WHERE class_id=?");
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();
    echo "<script>alert('Class updated!'); window.location.href='class.php';</script>";
}

$classSql = "SELECT * FROM class ORDER BY class_id ASC";
$classResult = $conn->query($classSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <h2>Add Class</h2>
    <form method="POST">
        <div class="form-group">
            <label for="className">Class Name:</label>
            <input type="text" id="className" name="className" placeholder="Add class name" required>
        </div>
        <button type="submit" class="button">Submit</button>
    </form>
    <h2>Existing Classes</h2>
    <table class="student-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Class Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        <?php while ($row = $classResult->fetch_assoc()): ?>
            <tr>
                <td><?= $row['class_id'] ?></td>
                <td><?= $row['class_name'] ?></td>
                <td>
                    <form method="post"  
                          onsubmit="return confirm('Delete this class?');">
                        <input type="hidden" name="delete_class" value="<?= $row['class_id'] ?>">
                        <button type="submit" class="btn-primary">Delete</button>
                    </form>
                    <form method="post">
                        <input type="hidden" name="edit_mode" value="<?= $row['class_id'] ?>">
                        <button type="submit" class="btn-secondary">Edit</button>
                    </form>
                </td>
            </tr>

            <?php if (isset($_POST['edit_mode']) && $_POST['edit_mode'] == $row['class_id']): ?>
            <tr>
                <form method="POST">
                    <td><?= $row['class_id'] ?></td>
                    <td>
                        <input type="text" name="update_name" value="<?= $row['class_name'] ?>" required>
                    </td>

                    <td>
                        <input type="hidden" name="update_id" value="<?= $row['class_id'] ?>">
                        <button type="submit" name="update_class" class="btn-primary">Save</button>
                    </td>
                </form>
            </tr>
            <?php endif; ?>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
