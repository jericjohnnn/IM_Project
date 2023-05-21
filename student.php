<link rel="stylesheet" type="text/css" href="style.css">

<?php
session_start();

// Check if user is logged in and has student role
if (!isset($_SESSION['user_id']) || $_SESSION['acct_type'] != 'student') {
    header('Location: index.php');
    exit;
}

// Connect to the database
include 'dbconnect.php';

// Get student data
$user_id = $_SESSION['user_id'];
$get_student = "SELECT * FROM students WHERE uid=$user_id";
$result = mysqli_query($conn, $get_student);
$student = mysqli_fetch_assoc($result);

// Display student data
echo '<h2>Welcome, ' . $student['firstname'] . ' ' . $student['lastname'] . '!</h2>';
echo '<p>Course: ' . $student['course'] . '</p>';

// Construct the SQL query to fetch the grades and subjects for the student
$query = "SELECT grades.gid, grades.grade, subjects.subject, subjects.subject_name
          FROM grades
          INNER JOIN subjects ON grades.sbid = subjects.sbid
          WHERE grades.sid = " . $student['sid'];

// Execute the query
$result = mysqli_query($conn, $query);

    // Start the HTML table
    echo "<table><tr><th>Grade</th><th>Subject</th><th>Subject Description</th><th>Action</th></tr>";

    // Output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row["grade"] . "</td><td>" . $row["subject"] . "</td> <td>" . $row["subject_name"] . "</td>";
        echo '<td><a href="remove_subject.php?grade_id=' . $row['gid'] . '">Remove</a></td></tr>';
    }

    // End the HTML table
    echo "</table>";


// Close the database connection
mysqli_close($conn);
?>

<a href="logout.php">Logout</a>


<?php
    include 'addsub.php';

?>


