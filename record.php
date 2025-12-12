<?php
require_once('db_connect.php');
session_start();
if ($_SESSION['role'] != 'faculty') {
    header("Location: login.php");
    exit;
}
include "navbar.php";
$searchClass = "";  
if (isset($_GET['search_class'])) {
    $searchClass = $_GET['search_class'];
}

// Handle update request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id = (int) $_POST['update_id'];
    $student = $_POST['student_name'] ?? '';
    $father = $_POST['father_name'] ?? '';
    $mother = $_POST['mother_name'] ?? '';
    $email = $_POST['email_id'] ?? '';
    $class_id = isset($_POST['class_id']) ? (int) $_POST['class_id'] : 0;
    $editDob = isset($_POST['edit_dob']) && $_POST['edit_dob'] == '1';
    $dob = $_POST['dob'] ?? null;

    if ($student === '' || $class_id <= 0) {
        // validation fail - redirect back with error (simple)
        header('Location: record.php?msg=invalid');
        exit;
    }

    if ($editDob) {
        $query = "UPDATE student SET student_name=?, father_name=?, mother_name=?, email_id=?, dob=?, class_id=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $student, $father, $mother, $email, $dob, $class_id, $id);
    } else {
        $query = "UPDATE student SET student_name=?, father_name=?, mother_name=?, email_id=?, class_id=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssii", $student, $father, $mother, $email, $class_id, $id);
    }
    $stmt->execute();
    header("Location: record.php?msg=updated");
    exit;
}

// Handle delete request (faculty only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!isset($_SESSION)) { session_start(); }
    // allow only faculty
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'faculty') {
        $deleteId = (int) $_POST['delete_id'];
        if ($deleteId > 0) {
            $delStmt = $conn->prepare("DELETE FROM student WHERE id = ?");
            $delStmt->bind_param("i", $deleteId);
            if ($delStmt->execute()) {
                header('Location: record.php?msg=deleted');
                exit;
            } else {
                $deleteError = $delStmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
                    <a href='edit.php?id=" . htmlspecialchars($result['id']) . "' class='btn-edit' style='margin-right:6px;'>Edit</a>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="records-container">
    <h2>Student Records</h2> 
    <form method="GET" class="search-bar">
        <input type="text" name="search_class" placeholder="Search by class name" 
        value="<?php echo htmlspecialchars($searchClass); ?>">
        <button type="submit" class="btn-primary">Search</button>
        <a href="record.php" class="btn-secondary">Reset</a>
        <a href="student.php" class="btn-primary">Add Student</a>
    </form>
    <table class="student-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Student Name</th>
                <th>Father Name</th>
                <th>Mother Name</th>
                <th>Email</th>
                <th>DOB</th>
                <th>Class</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

<?php
if (!empty($_GET['search_class'])) {
    $query = "SELECT f.*, c.class_name 
              FROM student f 
              LEFT JOIN class c ON f.class_id = c.class_id
              WHERE c.class_name LIKE ?";
    $stmt = $conn->prepare($query);
    $searchTerm = "%" . $searchClass . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $data = $stmt->get_result();
} 
else {
    $query = "SELECT f.*, c.class_name 
              FROM student f 
              LEFT JOIN class c ON f.class_id = c.class_id";
    $data = mysqli_query($conn, $query);
}
$total = mysqli_num_rows($data);
$edit_id = $_POST['edit_id'] ?? null;

if ($total > 0) {
    while ($result = mysqli_fetch_assoc($data)) {
        if ($edit_id == $result['id']) {
            echo "
            <tr>
                <form method='post'>
                    <td>{$result['id']}</td>
                    <td><input type='text' name='student_name' value='" . htmlspecialchars($result['student_name']) . "'></td>
                    <td><input type='text' name='father_name' value='" . htmlspecialchars($result['father_name']) . "'></td>
                    <td><input type='text' name='mother_name' value='" . htmlspecialchars($result['mother_name']) . "'></td>
                    <td><input type='text' name='email_id' value='" . htmlspecialchars($result['email_id']) . "'></td>
                    <td><input type='date' name='dob' value='" . htmlspecialchars($result['dob']) . "'></td>
                    <td>
                        <select name='class_id' required>
                            <option value=''disabled>Select class</option>";
                            $classResult = mysqli_query($conn, "SELECT * FROM class");
                            while ($c = mysqli_fetch_assoc($classResult)) {
                                $selected = ($c['class_id'] == $result['class_id']) ? 'selected' : '';
                                echo "<option value='{$c['class_id']}' $selected>{$c['class_name']}</option>";
                            }
            echo "
                        </select>
                    </td>
                    <td>
                        <input type='hidden' name='update_id' value='{$result['id']}'>
                        <button type='submit' class='btn-primary'>Save</button>
                    </td>
                </form>
            </tr>";
        }
        else {
            echo "
            <tr>
                <td>{$result['id']}</td>
                <td>{$result['student_name']}</td>
                <td>{$result['father_name']}</td>
                <td>{$result['mother_name']}</td>
                <td>{$result['email_id']}</td>
                <td>{$result['dob']}</td>
                <td>" . ($result['class_name'] ?? 'N/A') . "</td>
                <td>
                    <form method='post' style='display:inline' 
                        onsubmit=\"return confirm('Delete this record?');\">
                        <input type='hidden' name='delete_id' value='{$result['id']}'>
                        <button type='submit' class='btn-primary'>Delete</button>
                    </form>

                    <form method='post' style='display:inline;'>
                        <input type='hidden' name='edit_id' value='{$result['id']}'>
                        <button type='submit' class='btn-secondary'>Edit</button>
                    </form>
                </td>
            </tr>";
        }
    }
}
else {
    echo "<tr><td colspan='8' class='no-records'>No records found!</td></tr>";
}
?>
</tbody>
</table>
</div>
</body>
</html>
