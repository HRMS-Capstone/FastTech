<?php
// Database connection details
$host = 'localhost';
$db = 'hrm';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted via AJAX (POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize inputs
    $first_name = isset($_POST['first_name']) ? $conn->real_escape_string($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? $conn->real_escape_string($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
    $job_title = isset($_POST['job_title']) ? $conn->real_escape_string($_POST['job_title']) : '';

    // Prepare the SQL query to insert the new employee
    $sql = "INSERT INTO employees (first_name, last_name, email, job_title) VALUES (?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $job_title);
        
        // Execute the query
        if ($stmt->execute()) {
            // Return success response
            echo json_encode(['success' => true, 'message' => 'Employee added successfully!']);
        } else {
            // Return error message
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }

        $stmt->close();
    } else {
        // Return error message
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    exit(); // Ensure no further code is processed after AJAX request
}

$conn->close();
?>
