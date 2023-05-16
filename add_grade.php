<?php
session_start();

// Check if user is logged in and has teacher role
if (!isset($_SESSION['user_id']) || $_SESSION['acct_type'] != 'teacher') {
    header('Location: login.php');
    exit;
}

// Check if the student ID is provided
if (!isset($_GET['student_id'])) {
    header('Location: teacher.php');
    exit;
}

// Get the student ID from the query string
$student_id = $_GET['student_id'];

// Connect to the database
include 'dbconnect.php';

// Get teacher data
$user_id = $_SESSION['user_id'];
$get_teacher = "SELECT * FROM teachers WHERE uid=$user_id";
$result = mysqli_query($conn, $get_teacher);
$teacher = mysqli_fetch_assoc($result);

// Get subject data
$get_subject = "SELECT * FROM subjects WHERE sbid=" . $teacher['sbid'];
$result = mysqli_query($conn, $get_subject);
$subject = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the grade is provided
    if (!isset($_POST['grade']) || empty($_POST['grade'])) {
        $error = "Please enter a grade.";
    } else {
        // Get the grade from the form
        $grade = $_POST['grade'];

        // Validate the grade (add your own validation logic if needed)

        // Update the grade in the grades table for the specific student and subject
        $update_grade = "UPDATE grades SET grade=$grade WHERE sid=$student_id AND sbid=" . $teacher['sbid'];
        if (mysqli_query($conn, $update_grade)) {
            // Redirect back to the teacher's page
            header('Location: teacher.php');
            exit;
        } else {
            $error = "Error updating grade: " . mysqli_error($conn);
        }
    }
}

// Get the student's current grade
$get_student_grade = "SELECT grade FROM grades WHERE sid=$student_id AND sbid=" . $teacher['sbid'];
$result = mysqli_query($conn, $get_student_grade);
$student_grade = mysqli_fetch_assoc($result);

// Display the grade form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Grade</title>
</head>
<body>
    <h1>Add Grade</h1>
    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
    <form method="post" action="">
        <label>Current Grade:</label>
        <?php echo $student_grade['grade']; ?>
        <br>
        <label>New Grade:</label>
        <input type="text" name="grade">
        <br>
        <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>
