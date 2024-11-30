<?php
// Database connection
$host = 'localhost';
$db = 'hrm';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the submitted form data
$job_title = $_POST['job_title'];
$department = $_POST['department'];
$salary = $_POST['salary'];
$status = $_POST['status'];

// Insert the new job opening into the job_openings table
$sql = "INSERT INTO job_openings (job_title, department, salary, status) 
        VALUES ('$job_title', '$department', '$salary', '$status')";

if ($conn->query($sql) === TRUE) {
    echo "New job opening created successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
