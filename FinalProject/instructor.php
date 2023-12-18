<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="index.css">
    <title>InstructorRecord</title>
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
        <h1>Add Instructor Record</h1>
        <table style="width:40%">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <tr><td><label for="fname">First name:</label></td>
                <td><input type="text" name="insfname" id="insfname" value=""></td></tr>
                <tr><td><label for="fname">Last name:</label></td>
                <td><input type="text" name="inslname" id="inslname" value=""></td></tr>
                <tr><td><label for="fname">Email:</label></td>
                <td><input type="text" name="insemail" id="insemail" value=""></td></tr>
                <tr><td><label for="fname">Phone:</label></td>
                <td><input type="text" name="insphone" id="insphone" value=""></td></tr> 
                <tr><td></td><td><input type="submit" value="submit" name="addinstructor"></td></tr>
            </form>
        </table>
    </div>
    <?php 

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addinstructor']))
    {
        try{
            $studenfname = $_POST['insfname'];
            $studentlname = $_POST['inslname'];
            $studentemail = $_POST['insemail'];
            $studentphone = (int)$_POST['insphone'];
            $instrucsql = "INSERT INTO instructor (FirstName,LastName,Email,Phone) 
                                    VALUES(
                                    '$studenfname',
                                    '$studentlname',
                                    '$studentemail',
                                    $studentphone)";
            //$studentrecord = $conn->exec($studentsql);
            //echo  gettype($studenfname);	

            if (mysqli_query($conn, $instrucsql)) {
                echo "New record created successfully";
            } else {
                echo "<br>Error: " . $instrucsql . "<br>" . mysqli_error($conn);
            }
        }catch(PDOException $e) {
            echo $studentrecord . "<br>" . $e->getMessage();
        }
        
        
    }?>

    <div class="card-style">
        <h1>Instructor Records</h1>
        <table style="width:100%">
        <tr>
            <th>Instructor ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Options</th>
        </tr>
        <?php
        $sql = "SELECT * FROM instructor";
        $result = $conn->query($sql);

        // Check if the query was successful
        if ($result) {
            // Process the results
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["InstructorID"] . "</td>"
                . "<td>" . $row["FirstName"]. "</td>"
                . "<td>" . $row["LastName"]. "</td>"
                . "<td>" . $row["Email"]. "</td>"
                . "<td>" . $row["Phone"]. "</td>"
                . "<td><form method=".'POST'.">" 
                . "<input type=".'hidden'." value=". '_method' ." name= " . "DELETE"  ."/>" 
                . "<button type=".'submit'." value=". $row["InstructorID"] ." name= " . 'deleteButton' .">Delete</button>" 
                . "</form></td></tr>";
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }?>
        </table>
    </div>
    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteButton'])) {
                $idToDelete = $_POST['deleteButton'];;

                $delinssql = "DELETE FROM instructor WHERE InstructorID=$idToDelete";

                if ($conn->query($delinssql) === TRUE) {
                    echo "Record deleted successfully";
                } else {
                    echo "Error deleting record: " . $conn->error;
                }
        }
    ?>
    <div class="card-style">
        <?php 
            $selecteditsql = "SELECT InstructorID, FirstName, LastName, Email  FROM instructor";
            $result = $conn->query($selecteditsql);
            ?>
            <h1>Edit Records</h1>
            <form method="POST">
                <label for="instr_id">Select Instructor</label>
                <select name="instr_id" id="instr_id">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['InstructorID'] . '">' . $row['FirstName'] ." ". $row['LastName']."  (". $row['Email'].") " . '</option>';
                        }
                    }
                    ?>
                </select>
                <input type="submit" value="Show Instructor Info">
            </form>
            <?php
        // Check if the form has been submitted and a student ID is selected
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['instr_id'])) {
            $selected_id = $_POST['instr_id'];

            // Fetch student details based on the selected ID
            $editsql = "SELECT * FROM instructor WHERE InstructorID = $selected_id";
            $result = $conn->query($editsql);

            if ($result->num_rows > 0) {
                $editstudent = $result->fetch_assoc();
                // Display input fields with fetched student information
                ?>
                <table style="width:40%">
                <form method="POST" action="Instructor_Record.php">
                <input type="hidden" name="instrs_id" value="<?php echo $editstudent['InstructorID']; ?>">
                <tr><td>First Name:</td>
                <td><input type="text" name="ifirstname" value="<?php echo $editstudent['FirstName']; ?>"></td></tr>
                <tr><td>Last Name:</td>
                <td><input type="text" name="ilastname" value="<?php echo $editstudent['LastName']; ?>"></td></tr>
                <tr><td>Email:</td>
                <td><input type="text" name="iemail" value="<?php echo $editstudent['Email']; ?>"></td></tr>
                <tr><td>Phone: </td>
                <td><input type="text" name="iphone" value="<?php echo $editstudent['Phone']; ?>"></td></tr>
                <tr><td></td><td><input type="submit" value="Update"></td></tr>
                </form>
                </table>
                <?php
            } else {
                echo "No student found with this ID.";
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['instrs_id'])) {
            $instr_ids = $_POST['instrs_id'];
            $ifirstnames = $_POST['ifirstname'];
            $ilastnames = $_POST['ilastname'];
            $iemails = $_POST['iemail'];
            $iphones = $_POST['iphone'];
        
            $eupdatesql = "UPDATE instructor SET FirstName='$ifirstnames', LastName='$ilastnames', Email='$iemails', Phone='$iphones' WHERE InstructorID='$instr_ids'";
        
            if ($conn->query($eupdatesql) === TRUE) {
                echo "Instructor information updated successfully";
            } else {
                echo "Error updating Instructor information: " . $conn->error;
            }
        }

        ?>
    </div>
</body>
</html>

