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

// Get employee ID from URL parameter
if (isset($_GET['id'])) {
    $employeeId = $_GET['id'];

    // Fetch employee data
    $sql = "SELECT * FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employeeId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $employee = $result->fetch_assoc();
    } else {
        die("Employee not found.");
    }
} else {
    die("Employee ID not specified.");
}

// Handle the form submission to update the employee details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $jobTitle = $_POST['job_title'];

    // Update employee details
    $updateSql = "UPDATE employees SET first_name = ?, last_name = ?, email = ?, job_title = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ssssi", $firstName, $lastName, $email, $jobTitle, $employeeId);

    if ($stmt->execute()) {
        // Success message and JavaScript redirect
        echo '<script>
                alert("Employee updated successfully!");
                window.location.href = "employees.php";
              </script>';
        exit; // Stop the script after sending the redirect
    } else {
        echo '<p style="color: red; text-align: center;">Error updating employee: ' . $conn->error . '</p>';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <style>
        /* Basic styling for the edit form */
        form {
            width: 300px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        /* Input fields and labels */
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Submit button styling */
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Go Back Link */
        .go-back-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .go-back-btn a {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .go-back-btn a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Edit Employee</h2>
    <form method="POST">
        <label for="first_name">First Name:</label><br>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($employee['first_name']); ?>" required><br><br>

        <label for="last_name">Last Name:</label><br>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($employee['last_name']); ?>" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required><br><br>

        <label for="job_title">Job Title:</label><br>
        <input type="text" id="job_title" name="job_title" value="<?php echo htmlspecialchars($employee['job_title']); ?>" required><br><br>

        <input type="submit" value="Update Employee">
    </form>

    <!-- Go Back Button (Optional) -->
    <div class="go-back-btn">
        <a href="employees.php">Go Back to Employees</a>
    </div>
</body>
</html>
