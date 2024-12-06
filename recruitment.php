<?php
// Database connection
$host = "localhost";
$db = "hrm";
$user = "root"; // Change as necessary
$pass = ""; // Change as necessary

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $cfname = filter_var(trim($_POST['cfname']), FILTER_SANITIZE_STRING);
    $clname = filter_var(trim($_POST['clname']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $status = filter_var(trim($_POST['status']), FILTER_SANITIZE_STRING);
    $date_applied = $_POST['date_applied']; // Assuming date is in correct format

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO recruitment (cfname, clname, email, status, date_applied) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $cfname, $clname, $email, $status, $date_applied);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Recruitment data added successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    // Close the statement
    $stmt->close();
}

// Fetch data from the database
$sql = "SELECT * FROM recruitment";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruitment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background: black;
            color: white;
            padding: 20px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            box-sizing: border-box;
        }

        .sidebar h2 {
            margin-top: 0;
            font-size: 24px;
            color: white;
        }

        .sidebar a {
            display: block;
            margin: 10px 0;
            text-decoration: none;
            color: #28a745;
            font-size: 18px;
        }

        .sidebar a:hover {
            text-decoration: underline;
            color: #61ff61;
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #28a745;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <h1>Recruitment Form</h1>
        <form method="POST" action="recruitment.php">
            <label for="cfname">Candidate First Name:</label><br>
            <input type="text" id="cfname" name="cfname" required><br><br>
            <label for="clname">Candidate Last Name:</label><br>
            <input type="text" id="clname" name="clname" required><br><br>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <label for="status">Status:</label><br>
            <select id="status" name="status" required>
                <option value="Applied">Applied</option>
                <option value="Interviewed">Interviewed</option>
                <option value="Hired">Hired</option>
                <option value="Rejected">Rejected</option>
            </select><br><br>

            <label for="date_applied">Date Applied:</label><br>
            <input type="date" id="date_applied" name="date_applied" required><br><br>

            <button type="submit">Submit</button>
        </form>

        <h2>Recruitment Records</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Candidate First Name</th>
                    <th>Candidate Last Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Date Applied</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['cfname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['clname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['date_applied']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>