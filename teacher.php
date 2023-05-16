<?php
session_start();

// Check if user is logged in and has teacher role
if (!isset($_SESSION['user_id']) || $_SESSION['acct_type'] != 'teacher') {
    header('Location: login.php');
    exit;
}

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

// Get student and grade data for the teacher's subject
$get_students = "SELECT students.sid, students.firstname, students.lastname, students.course, grades.grade
                FROM students
                INNER JOIN grades ON students.sid = grades.sid
                WHERE grades.sbid = " . $teacher['sbid'];


$result = mysqli_query($conn, $get_students);

// Display student and grade data in a table
echo '<h2>Welcome, ' . $teacher['firstname'] . ' ' . $teacher['lastname'] . '!</h2>';
echo '<p>Subject: ' . $subject['subject'] . '</p>';
echo '<p>Subject code: '.$subject['subject_code'].'</p>';
echo '<table>';
echo '<tr><th>No.</th><th>Firstname</th><th>Lastname</th><th>Course</th><th>Grade</th></tr>';

$counter = 1;
while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td><a href="remove_student.php?student_id=' . $row['sid'] . '">DEL</a></td>';
    echo '<td>' . $counter . '</td>';
    echo '<td>' . $row['firstname'] . '</td>';
    echo '<td>' . $row['lastname'] . '</td>';
    echo '<td>' . $row['course'] . '</td>';
    echo '<td><a href="edit_grade.php?student_id=' . $row['sid'] . '">Edit</a></td>';
    echo '<td><a href="add_grade.php?student_id=' . $row['sid'] . '">Give Grade</a></td>';
    echo '<td>' . $row['grade'] . '</td>';
    echo '</tr>';
    $counter++;
}

echo '</table>';

// Close the database connection
mysqli_close($conn);
?>

<a href="logout.php">Logout</a>
