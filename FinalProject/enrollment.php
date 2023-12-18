<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="index.css">
    <title>EnrollmentRecord</title>
</head>
<body>
    <ul class="navigation-bar">
        <li><a href="Student_record.php">StudentRecord</a></li>
        <li><a href="Course_Record.php">Course</a></li>
        <li><a href="Instructor_Record.php">Instructor</a></li>
        <li><a href="Enrollment_Record.php">Enrollment</a></li>
    </ul>
    <div class="status">
        <?php // Check if the query was successful
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "studentrecord";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Status:  Connection failed: " . $conn->connect_error);
        }
        echo "Server Status: Connected successfully";
        ?>
    </div>

    <div class="card-style">
        <h1>Enroll</h1>
        <table style="width:40%">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <tr><td><label for="fname">Select Student:</label></td>
                <td>
                <select id="student" name="student">
                <?php
                    echo "<br><hr>";
                    // Example query
                    $sql = "SELECT * FROM student";
                    $result = $conn->query($sql);

                    // Check if the query was successful
                    if ($result) {
                        // Process the results
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value=" . $row["StudentID"] . ">".$row["FirstName"]."</option>";
                        }
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }?>
                </select>
                </td></tr>
                <tr><td><label for="fname">Select Course:</label></td>
                <td>
                <select id="course" name="course">
                <?php
                    echo "<br><hr>";
                    // Example query
                    $sql = "SELECT * FROM course";
                    $result = $conn->query($sql);

                    // Check if the query was successful
                    if ($result) {
                        // Process the results
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value=" . $row["CourseID"] . ">".$row["CourseName"]."</option>";
                        }
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }?>
                </select>
                </td></tr>
                <tr><td><label for="fname">Enrollment Date:</label></td>
                <td><input type="date" id="datepicker" name="selectedDate"></td></tr>
                <tr><td><label for="fname">Grade:</label></td>
                <td><input type="number" id="integerInput" name="grade" required></td></tr>      
                <tr><td></td><td><input type="submit" value="submit" name="addenroll"></td></tr>
            </form>
        </table>
    
    
    <?php 

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addenroll']))
    {
        try{
            $student = $_POST['student'];
            $course = $_POST['course'];
            $enrolldate = $_POST['selectedDate'];
            $grade = (int)$_POST['grade'];
            $studentsql = "INSERT INTO enrollment (StudentID,CourseID,EnrollmentDate,Grade) 
                                    VALUES(
                                    '$student',
                                    '$course',
                                    '$enrolldate',
                                    '$grade')";
            //$studentrecord = $conn->exec($studentsql);
            //echo  gettype($studenfname);	

            if (mysqli_query($conn, $studentsql)) {
                echo "New record created successfully";
            } else {
                echo "<br>Error: " . $studentsql . "<br>" . mysqli_error($conn);
            }
        }catch(PDOException $e) {
            echo $studentrecord . "<br>" . $e->getMessage();
        }
        
        
    }?>
    </div>

    <div class="card-style">
        <h1>Enrollment Records</h1>
        <table style="width:100%">
        <tr>
            <th>Enrollment ID</th>
            <th>Student ID</th>
            <th>Course ID</th>
            <th>Enrollment Date</th>
            <th>Grade</th>
            <th>Options</th>
        </tr>
        <?php
        $sql = "SELECT * FROM enrollment";
        $result = $conn->query($sql);

        // Check if the query was successful
        if ($result) {
            // Process the results
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["EnrollmentID"] . "</td>"
                . "<td>" . $row["StudentID"]. "</td>"
                . "<td>" . $row["CourseID"]. "</td>"
                . "<td>" . $row["EnrollmentDate"]. "</td>"
                . "<td>" . $row["Grade"]. "</td>"
                . "<td><form method=".'POST'.">" 
                . "<input type=".'hidden'." value=". '_method' ." name= " . "DELETE"  ."/>" 
                . "<button type=".'submit'." value=". $row["EnrollmentID"] ." name= " . 'deleteButton' .">Delete</button>" 
                . "</form></td></tr>";
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }?>
        </table>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteButton'])) {
                $idToDelete = $_POST['deleteButton'];;

                $eenrollsql = "DELETE FROM enrollment WHERE EnrollmentID=$idToDelete";

                if ($conn->query($eenrollsql) === TRUE) {
                    echo "Record deleted successfully";
                } else {
                    echo "Error deleting record: " . $conn->error;
                }
        }
    ?>
    </div>
    <div class="card-style">
        <?php 
        $selecteditsql = "SELECT EnrollmentID, StudentID, EnrollmentDate, Grade FROM enrollment";
        $result = $conn->query($selecteditsql);
        ?>
        <h1>Edit Records</h1>
        <form method="POST">
            <label for="enrolls_id">Select Student</label>
            <select name="enrolls_id" id="enrolls_id">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['EnrollmentID'] . '">' ."Student:". $row['StudentID'] ." Date:". $row['EnrollmentDate']."  (Grade:". $row['Grade'].") " . '</option>';
                    }
                }
                ?>
            </select>
            <input type="submit" value="Show Student Info">
        </form>
        <?php
    // Check if the form has been submitted and a student ID is selected
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enrolls_id'])) {
        $selected_id = $_POST['enrolls_id'];

        // Fetch student details based on the selected ID
        $enrolleditsql = "SELECT * FROM enrollment WHERE EnrollmentID = $selected_id";
        $result = $conn->query($enrolleditsql);

        if ($result->num_rows > 0) {
            $editstudent = $result->fetch_assoc();
            // Display input fields with fetched student information
            ?>
            <table style="width:40%">
            <form method="POST" action="Enrollment_Record.php">
            <input type="hidden" name="eenrollment_id" value="<?php echo $editstudent['EnrollmentID']; ?>">
            <tr><td>Enrollment Date:</td>
             <td><input type="date" name="enrolldates" value="<?php echo $editstudent['EnrollmentDate']; ?>"></td></tr>
             <tr><td>Grade:</td>
             <td><input type="text" name="enrollgrade" value="<?php echo $editstudent['Grade']; ?>"></td></tr>
             <tr><td></td><td><input type="submit" value="Update"></td></tr>
            </form>
            </table>
            <?php
        } else {
            echo "No student found with this ID.";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eenrollment_id'])) {
        $enrollmentids = $_POST['eenrollment_id'];
        $enrollsdates = $_POST['enrolldates'];
        $grades = $_POST['enrollgrade'];
    
        $eupdatesql = "UPDATE enrollment SET EnrollmentDate='$enrollsdates', Grade='$grades' WHERE EnrollmentID='$enrollmentids'";
    
        if ($conn->query($eupdatesql) === TRUE) {
            echo "Enrollment information updated successfully";
        } else {
            echo "Error updating Enrollment information: " . $conn->error;
        }
    }

    ?>
    </div>
</body>
</html>

