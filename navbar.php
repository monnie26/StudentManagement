<div class="navbar">
    <div class="nav-left">
        <strong>Student Management</strong>
    </div>

    <div class="nav-right">
        <?php if(isset($_SESSION['role'])): ?>

            <?php if($_SESSION['role'] == 'student'): ?>
                <a href="dashboard.php">Home</a>
                <a href="student.php">Register</a>
            <?php endif; ?>

            <?php if($_SESSION['role'] == 'faculty'): ?>
                <a href="dashboard.php">Home</a>
                <a href="class.php">Add Class</a>
                <a href="record.php">Student Records</a>
            <?php endif; ?>

            <a href="logout.php" class="logout">Logout</a>
        <?php endif; ?>
    </div>
</div>
