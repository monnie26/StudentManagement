<?php 
require_once('db_connect.php');
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {

  $studentName = $_POST['studentName'] ?? '';
  $fatherName  = $_POST['fatherNameGrid'] ?? '';
  $motherName  = $_POST['motherNameGrid'] ?? '';
  $emailId     = $_POST['emailId'] ?? '';
  $dob         = $_POST['dob'] ?? '';
  $classId     = isset($_POST['class_id']) ? (int) $_POST['class_id'] : 0;

  if ($studentName === '' || $classId <= 0) {
    echo "Error: Student name and class are required.";
  } else {

    $checkClass = $conn->prepare("SELECT class_id FROM class WHERE class_id = ?");
    $checkClass->bind_param("i", $classId);
    $checkClass->execute();
    $checkClass->store_result();

    if ($checkClass->num_rows === 0) {
      echo "Error: Selected class does not exist.";
      $checkClass->close();
    } else {
      $checkClass->close();

      $stmt = $conn->prepare("INSERT INTO student(student_name, father_name, mother_name, email_id, dob, class_id) VALUES(?,?,?,?,?,?)");
      $stmt->bind_param("sssssi", $studentName, $fatherName, $motherName, $emailId, $dob, $classId);

      if ($stmt->execute()) {
        echo "Data sent successfully";
      } else {
        echo "Error: " . $stmt->error;
      }

      $stmt->close();
    }
  }
}
$classSql = "SELECT class_id, class_name FROM class";
$classResult = $conn->query($classSql);
$searchClass = isset($_GET['search_class']) ? trim($_GET['search_class']) : '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registeration Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container"> 
    <h2>Student Registeration</h2>
        
    <form method="POST">
        <div class="form-group">
            <label for="studentName">Student Name:</label>
            <input type="text" id="studentName" name="studentName" required>
        </div>

        <div class="form-group">
            <label for="fatherNameGrid">Father's Name:</label>
            <input type="text" id="fatherNameGrid" name="fatherNameGrid" required>
        </div>

        <div class="form-group">
            <label for="motherNameGrid">Mother's Name:</label>
            <input type="text" id="motherNameGrid" name="motherNameGrid" required>
        </div>

        <div class="form-group">
            <label for="emailId">Email id:</label>
            <input type="text" id="emailId" name="emailId" required>
        </div>

        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="text" id="dob" name="dob" required>
        </div>

        <div class="form-group">
            <label for="class_id">Class:</label>
            <select id="class_id" name="class_id" required>
            <option value="">Select class</option>
            <?php
            if ($classResult && $classResult->num_rows > 0) {
              while ($row = $classResult->fetch_assoc()) {
                echo '<option value="' . $row['class_id'] . '">' . $row['class_name'] . '</option>';
              }
            }
            ?>
          </select>
        </div>
        <button type="submit" name="submit">Submit</button>
    </form>
    </div>

      <div class="actions">
        <a href="class.php" class="class-btn">Manage Classes</a>
      </div>

      <div class="actions">
        <a href="student_record.php" class="class-btn">Student Records </a>
      </div>
</body>
</html>
