<!-- registration form -->
<!DOCTYPE html>
<html>
<head>
	<title>Registration Form</title>
	<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
	<h1>Registration Form</h1>
    <!-- basic registration inputs -->
	<form action="register.php" method="post">
		<label for="firstname">First Name:</label>
		<input type="text" name="firstname" required><br><br>

		<label for="lastname">Last Name:</label>
		<input type="text" name="lastname" required><br><br>

		<label for="username">Username:</label>
		<input type="text" name="username" required><br><br>

		<label for="password">Password:</label>
		<input type="password" name="password" required><br><br>

        <!-- select account type -->
		<label for="acct_type">Account Type:</label>
		<select name="acct_type" id="acct_type" required>
			<option value="">-- Select Account Type --</option>
			<option value="student">Student</option>
			<option value="teacher">Teacher</option>
		</select><br><br>

        <!-- shown when student is clicked -->
		<div id="course_field" style="display: none;">
			<label for="course">Course:</label>
			<input type="text" name="course"><br><br>
		</div>

        <!-- shown when teacher is clicked -->
		<div id="subject_field" style="display: none;">
			<label for="subject">Subject:</label>
			<select name="subject" id="subject">
				<option value="">-- Select Subject --</option>
				<!-- PHP code to retrieve subjects from database and populate the dropdown menu -->
				<?php
					// connect to database im_project
					include 'dbconnect.php';
				

					$sql = "SELECT sbid, subject FROM subjects";
					$result = mysqli_query($conn, $sql);
					if (mysqli_num_rows($result) > 0) {
					    while($row = mysqli_fetch_assoc($result)) {
					        echo '<option value="' . $row['sbid'] . '">' . $row['subject'] . '</option>';
					    }
					}
					mysqli_close($conn);
				?>
			</select><br><br>
		</div>
            <!-- submit registration form -->
		<input type="submit" name="submit" value="Register">
	</form>

    <a href="index.php">login</a>

    <!-- script for the student/teacher selection -->
	<script>
		document.getElementById("acct_type").addEventListener("change", function() {
			if (this.value == "student") {
				document.getElementById("course_field").style.display = "block";
				document.getElementById("subject_field").style.display = "none";
			} else if (this.value == "teacher") {
				document.getElementById("course_field").style.display = "none";
				document.getElementById("subject_field").style.display = "block";
			} else {
				document.getElementById("course_field").style.display = "none";
				document.getElementById("subject_field").style.display = "none";
			}
		});
	</script>
</body>
</html>


<!-- code for: if already logged in, will be redirected to respective page -->
<?php
    session_start();

    // check if a session is active
    if (isset($_SESSION['acct_type'])) {
        // redirect the user to the appropriate page based on their role
        if ($_SESSION['acct_type'] == 'student') {
            header('Location: student.php');
            exit();
        } else if ($_SESSION['acct_type'] == 'teacher') {
            header('Location: teacher.php');
            exit();
        }
    }

    // if no session is active, continue with the current page
?>

<!-- registration php code -->
<?php
	// connect to database im_project
	include 'dbconnect.php';

	// check if the form is submitted
	if(isset($_POST['submit'])) {
		// get the form inputs
		$firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
		$lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
		$username = mysqli_real_escape_string($conn, $_POST['username']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);
		$acct_type = mysqli_real_escape_string($conn, $_POST['acct_type']);

		// check if the username already exists in the users table
		$check_user = "SELECT * FROM users WHERE username='$username'";
		$result = mysqli_query($conn, $check_user);

		if(mysqli_num_rows($result) == true) {
			// username already exists, redirect back to registration page
			echo 'Username already exists. Please choose a different username.';
			exit;
		} else {
			// insert the user information into the users table
			$insert_user = "INSERT INTO users (username, password, acct_type) VALUES ('$username', '$password', '$acct_type')";
			mysqli_query($conn, $insert_user);
			$user_id = mysqli_insert_id($conn);

			// check if the account type is student or teacher
			if($acct_type == 'student') {
				// get the course from the form input
				$course = mysqli_real_escape_string($conn, $_POST['course']);

				// insert the student information into the students table
				$insert_student = "INSERT INTO students (uid, firstname, lastname, course) VALUES ('$user_id', '$firstname', '$lastname', '$course')";
				mysqli_query($conn, $insert_student);
			} elseif($acct_type == 'teacher') {
				// get the subject ID from the form input
				$subject_id = mysqli_real_escape_string($conn, $_POST['subject']);

				// insert the teacher information into the teachers table
				$insert_teacher = "INSERT INTO teachers (uid, firstname, lastname, sbid) VALUES ('$user_id', '$firstname', '$lastname', '$subject_id')";
				mysqli_query($conn, $insert_teacher);
			}

			// redirect to the home page after registration
			echo 'register success';
			exit;
		}
	}

	// close the database connection
	mysqli_close($conn);
?>

