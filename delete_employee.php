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

// Check if 'id' is provided through POST
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $employeeId = $_POST['id'];

    // Prepare the DELETE query
    $sql = "DELETE FROM employees WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $employeeId);

        // Execute the query
        if ($stmt->execute()) {
            echo 'success'; // Return success message
        } else {
            echo 'Error deleting employee: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        echo 'Error preparing query: ' . $conn->error;
    }
} else {
    echo 'Invalid ID.';
}

$conn->close();
?>
