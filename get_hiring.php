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

// Assuming you want to fetch the current user's personal info
$username = $_SESSION['username'];

// Query to get the personal information of the employee based on the logged-in user
$sql = "SELECT * FROM employees WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $employee = $result->fetch_assoc();  // Get the employee's personal information
} else {
    die("Employee not found.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hiring Form</title>
    <style>
        /* Your styles for the form */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h3 {
            color: #333;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            margin-top: 10px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .personal-info {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h3>Hiring Form</h3>

<!-- Display Employee Personal Info -->
<div class="personal-info">
    <h4>Personal Information</h4>
    <p><strong>Name:</strong> <?= htmlspecialchars($employee['first_name']) . ' ' . htmlspecialchars($employee['last_name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($employee['email']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($employee['phone']) ?></p>
</div>

<!-- Display the Hiring Form -->
<form action="process_hiring.php" method="POST">
    <label for="job_title">Job Title:</label>
    <input type="text" id="job_title" name="job_title" required>

    <label for="department">Department:</label>
    <input type="text" id="department" name="department" required>

    <label for="salary">Salary:</label>
    <input type="number" id="salary" name="salary" required>

    <label for="status">Job Status:</label>
    <select id="status" name="status" required>
        <option value="open">Open</option>
        <option value="closed">Closed</option>
    </select>

    <button type="submit">Submit</button>
</form>

</body>
</html>
