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

// Function to sanitize input
function sanitizeInput($data) {
    global $conn;
    return htmlspecialchars($conn->real_escape_string(trim($data)));
}

// Handle the form submission for position update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_position']) && isset($_POST['position'])) {
        $position = sanitizeInput($_POST['position']);
        // Process the position update logic here
        // For example, you can update the candidate's position in the database
    }

    // Handle status update (Hired/Rejected)
    if (isset($_POST['update_status'])) {
        $candidate_id = sanitizeInput($_POST['candidate_id']);
        $status = sanitizeInput($_POST['status']);

        // Prepare and bind
        $stmt = $conn->prepare("UPDATE recruitment SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $candidate_id);

        if ($stmt->execute()) {
            if ($status === 'Hired') {
                // Optionally, add to the employees table
                $stmt = $conn->prepare("SELECT cfname, clname, email FROM recruitment WHERE id = ?");
                $stmt->bind_param("i", $candidate_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $candidate = $result->fetch_assoc();

                // Insert into employees table
                $stmt = $conn->prepare("INSERT INTO employees (fname, lname, email, date_hired) VALUES (?, ?, ?, NOW())");
                $stmt->bind_param("sss", $candidate['cfname'], $candidate['clname'], $candidate['email']);
                $stmt->execute();
            }
            echo "<script>alert('Status updated successfully');</script>";
        } else {
            echo "<script>alert('Error updating status: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

// Fetch shortlisted candidates
$sql = "SELECT * FROM recruitment WHERE status IN ('Interviewed')";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hiring</title>
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
        <h1 Hiring Candidates</h1>
        <h2>Shortlisted Candidates</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Candidate First Name</th>
                    <th>Candidate Last Name</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th>Actions</th>
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
                        echo "<td>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='position' value='" . htmlspecialchars($row['id']) . "'>
                                <select name='position'>
                                    <option value='IT Officer'>IT Officer</option>
                                    <option value='HR Staff'>HR Staff</option>
                                </select>
                                <button type='submit' name='update_position'>Update Position</button>
                            </form>
                        </td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='candidate_id' value='" . htmlspecialchars($row['id']) . "'>
                                <select name='status'>
                                    <option value='Hired'>Hired</option>
                                    <option value='Rejected'>Rejected</option>
                                </select>
                                <button type='submit' name='update_status'>Update</button>
                            </form>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No shortlisted candidates found</td></tr>";
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