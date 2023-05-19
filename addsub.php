
<?php
$user_id = $_SESSION['user_id'];

// Connect to the database
include 'dbconnect.php';

if (isset($_POST['submit'])) {
    // Get the inputted subject code
    $subject_code = mysqli_real_escape_string($conn, $_POST['subject_code']);

    // Check if the inputted subject code exists in the subjects table
    $check_subject = "SELECT sbid FROM subjects WHERE subject_code = '$subject_code'";
    $result = mysqli_query($conn, $check_subject);

    if (mysqli_num_rows($result) == 1) {
        // Subject code exists, fetch the sbid
        $row = mysqli_fetch_assoc($result);
        $sbid = $row['sbid'];

        // Get the user ID from the session or any other way you store it
        $user_id = $_SESSION['user_id'];

        // Check if the user ID exists in the students table
        $check_user = "SELECT sid FROM students WHERE uid = '$user_id'";
        $result = mysqli_query($conn, $check_user);

        if (mysqli_num_rows($result) == 1) {
            // User ID exists, fetch the sid
            $row = mysqli_fetch_assoc($result);
            $sid = $row['sid'];

            // Check if the student is already enrolled in the subject
            $check_enrollment = "SELECT gid FROM grades WHERE sid = '$sid' AND sbid = '$sbid'";
            $result = mysqli_query($conn, $check_enrollment);

            if (mysqli_num_rows($result) == 0) {
                // Student is not enrolled, insert the sid and sbid into the grades table
                $insert_grade = "INSERT INTO grades (sid, sbid) VALUES ('$sid', '$sbid')";
                if (mysqli_query($conn, $insert_grade)) {
                    header('Location: student.php');
                } else {
                    echo "Error inserting subject code into grades table: " . mysqli_error($conn);
                }
            } else {
                // header('Location: student.php');
                echo "You are already enrolled in this subject.";
            }
        } else {
            echo "User ID not found in students table.";
        }
    } else {
        echo "Invalid subject code.";
    }
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Select Subject</title>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
    <h1>add Subject</h1>
    <form method="post" action="">
        <label>Subject Code:</label>
        <input type="text" name="subject_code">
        <br>
        <input type="submit" name="submit" value="Select Subject">
    </form>
</body>
</html>
