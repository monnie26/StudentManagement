<tbody>
        <?php
        require_once('db_connect.php');
        if($_SERVER["REQUEST_METHOD"] == "POST"){

        if ($searchClass !== '') {
            $query = "SELECT f.*, c.class_name FROM student f 
                      LEFT JOIN class c ON f.class_id = c.class_id 
                      WHERE c.class_name LIKE ?";
            $stmt = $conn->prepare($query);
            $searchTerm = "%$searchClass%";
            $stmt->bind_param("s", $searchTerm);
            $stmt->execute();
            $data = $stmt->get_result();
        } else {
            $query = "SELECT f.*, c.class_name FROM student f LEFT JOIN class c ON f.class_id = c.class_id";
            $data = mysqli_query($conn, $query);
        }
        $total = mysqli_num_rows($data);
        if ($total > 0) {
            while ($result = mysqli_fetch_assoc($data)) {
                echo "
                <tr>
                    <td>" . $result['id'] . "</td>
                    <td>" . $result['student_name'] . "</td>
                    <td>" . $result['father_name'] . "</td>
                    <td>" . $result['mother_name'] . "</td>
                    <td>" . $result['email_id'] . "</td>
                    <td>" . $result['dob'] . "</td>
                    <td>" . ($result['class_name'] ?? 'N/A') . "</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan=7 class='no-records'>No records found!</td></tr>";
        }
    }
        $conn->close();
        ?>
      </tbody>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="records-container">
    <h2>Student Records</h2> 
    <form method= "GET" class= "search-bar">
        <input type="text" name="search_class" placeholder="Search by class name" value="<?php echo htmlspecialchars($searchClass);?>">

        <button type="submit" class="btn-primary">Search</button>

        <a href="index.php" class="btn-secondary">Reset</a>
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
        </tr>
      </thead>
      </table>
    </div>
</body>
</html>