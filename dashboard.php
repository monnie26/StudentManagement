<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}
?>
<?php include "navbar.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">

<?php if ($_SESSION['role'] == 'student'): ?>
    <!-- STUDENT DASHBOARD -->
    <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
    <p>You can register new student details below:</p>
    <a href="student.php" class="btn-primary">Go to Registration Form</a>
<?php endif; ?>

<?php if ($_SESSION['role'] == 'faculty'): ?>
    <!-- FACULTY DASHBOARD -->
    <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
    <p>Manage student and class information:</p>
    <a href="class.php" class="btn-primary">Add Class</a>
    <a href="record.php" class="btn-primary">View Student Records</a><br><br>
    <a href="student.php" class="btn-primary">Go to Registration Form</a>
<?php endif; ?>

</div>
</body>
</html>
