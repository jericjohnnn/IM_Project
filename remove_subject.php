<?php
session_start();

// Check if user is logged in and has student role
if (!isset($_SESSION['user_id']) || $_SESSION['acct_type'] != 'student') {
    header('Location: index.php');
    exit;
}

// Check if grade ID is provided
if (!isset($_GET['grade_id'])) {
    header('Location: student.php');
    exit;
}

// Get the grade ID from the query string
$grade_id = $_GET['grade_id'];

// Connect to the database
include 'dbconnect.php';

// Remove the subject from the student's grades
$remove_grade = "DELETE FROM grades WHERE gid = $grade_id";
if (mysqli_query($conn, $remove_grade)) {
    // Redirect to the student's page
    header('Location: student.php');
    exit;
} else {
    echo "Error removing subject: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
