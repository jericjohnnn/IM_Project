<?php

// Start a session
session_start();

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


            // Insert the sid and sbid into the grades table
            $insert_grade = "INSERT INTO grades (sid, sbid) VALUES ('$sid', '$sbid')";
            if (mysqli_query($conn, $insert_grade)) {
                header('Location: student.php');
            } else {
                echo "Error inserting subject code into grades table: " . mysqli_error($conn);
            }
        } else {
            echo "User ID not found in students table.";
        }
    } else {
        echo "Invalid subject code.";
    }
}


?>
