<?php 
require_once('db_connect.php');

session_start();
if ($_SESSION['role'] != 'faculty') {
    header("Location: login.php");
    exit;
}
include "navbar.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
$classname = $_POST['className'];
$classSql = "SELECT class_Id, class_name FROM class";
$classResult = $conn->query($classSql);

if($classResult->num_rows > 0){
    while($row = $classResult->fetch_assoc()) {
        if($row['class_name'] == $classname){
            $msg = $classname . " - Already exists!";
            echo "<script>alert(" . json_encode($msg) . "); window.location.href = window.location.pathname;</script>";
            exit();
        }
    }
}

$stmt = $conn->prepare("INSERT INTO class(class_name) VALUES(?)");
$stmt->bind_param("s",$classname);

if($stmt->execute()){
    $msg = $classname . " - Added successfully!";
    echo "<script>alert(" . json_encode($msg) . "); window.location.href = window.location.pathname;</script>";
} else{
    echo "Error: ". $stmt->error;
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Form</title>
     <link rel="stylesheet" href="class.css">
     <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
    <h2>Class Form</h2>

    <form  method ="POST"> 
        <div class="form-group">
            <label for="className">Class Name:</label>
            <input type="text" id="className" name="className" required>
        </div>

        <button type="submit">Submit</button>
</body>
</html>