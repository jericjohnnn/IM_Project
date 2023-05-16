<?php
session_start();

// Check if user is logged in and has teacher role
if (!isset($_SESSION['user_id']) || $_SESSION['acct_type'] != 'teacher') {
    header('Location: login.php');
    exit;
}

// Check if student ID is provided
if (!isset($_GET['student_id'])) {
    header('Location: teacher.php');
    exit;
}

// Get the student ID from the query string
$student_id = $_GET['student_id'];

// Connect to the database
include 'dbconnect.php';

// Get the teacher's user ID
$user_id = $_SESSION['user_id'];

// Get the teacher's subject ID
$get_teacher = "SELECT sbid FROM teachers WHERE uid = $user_id";
$result = mysqli_query($conn, $get_teacher);
$teacher = mysqli_fetch_assoc($result);
$subject_id = $teacher['sbid'];

// Remove the student from the teacher's subject in the grades table
$remove_student = "DELETE FROM grades WHERE sid = $student_id AND sbid = $subject_id";
if (mysqli_query($conn, $remove_student)) {
    // Redirect to the teacher's page
    header('Location: teacher.php');
    exit;
} else {
    echo "Error removing student: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
