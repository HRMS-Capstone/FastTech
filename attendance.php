<?php
// Database connection settings
$host = "localhost";
$db = "hrm";
$user = "root";
$pass = "";

// Create a database connection
$conn = new mysqli($host, $user, $pass, $db);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get attendance data
function getAttendanceData() {
    global $conn;

    $sql = "SELECT a.id, a.employee_id, 
                   CONCAT(e.first_name, ' ', e.last_name) AS employee_name, 
                   a.date, a.time_in, a.time_out, a.status 
            FROM attendance a
            JOIN employees e ON a.employee_id = e.id
            WHERE a.time_in IS NOT NULL  -- Filter for employees who have timed in
            ORDER BY a.date ASC, a.time_in ASC"; 

    $stmt = $conn->prepare($sql); // Prepare the statement
    $stmt->execute(); // Execute the statement
    $result = $stmt->get_result(); // Get the result set

    if ($result->num_rows > 0) {
        // Output the attendance data in HTML table format
        echo "<table border='1' class='attendance-table'>";
        echo "<tr><th>ID</th><th>Employee Name</th><th>Date</th><th>Time In</th><th>Time Out</th><th>Status</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["employee_id"] . "</td>"; // Display employee_id
            echo "<td>" . $row["employee_name"] . "</td>";
            echo "<td>" . $row["date"] . "</td>";
            echo "<td>" . date('h:i A', strtotime($row["time_in"])) . "</td>"; // Format time_in
            echo "<td>" . date('h:i A', strtotime($row["time_out"])) . "</td>"; // Format time_out
            echo "<td style='background-color: " . getStatusColor($row["status"]) . "; color: #000;'>" . $row["status"] . "</td>"; // Add color to status
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No attendance records found.";
    }

    $stmt->close(); // Close the statement
}

// Function to get the status color
function getStatusColor($status) {
    switch ($status) {
        case "Present":
            return "#90ee90"; // Light green for "Present"
        case "Absent":
            return "#f08080"; // Light red for "Absent"
        case "Leave":
            return "#ffd700"; // Light yellow for "Leave"
        case "Late":
            return "#FFA500"; // Orange for "Late"
        default:
            return "#fff"; // Default white if status is invalid
    }
}

// Handle form submission using AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get attendance data from the POST request
    $employee_id = $_POST["employee_id"];
    $date = $_POST["date"];
    $time_in = $_POST["time_in"];
    $time_out = $_POST["time_out"];
    $status = $_POST["status"];

    // Prepare the SQL query to insert the attendance data
    $sql = "INSERT INTO attendance (employee_id, date, time_in, time_out, status) 
            VALUES (?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters to the statement
    $stmt->bind_param("issss", $employee_id, $date, $time_in, $time_out, $status);

    // Execute the statement
    if ($stmt->execute()) {
        // Attendance data saved successfully
        // Get the employee name from the employees table
        $sql = "SELECT first_name, last_name FROM employees WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $employee = $result->fetch_assoc();
        $employeeName = $employee['first_name'] . ' ' . $employee['last_name'];

        // Send a success response to the client with the ID and employee name
        echo json_encode(['success' => true, 'message' => 'Attendance added successfully!', 'employeeId' => $employee_id, 'employeeName' => $employeeName]); // Send employee_id
    } else {
        // Error saving attendance data
        echo json_encode(['success' => false, 'message' => 'Error saving attendance data: ' . $stmt->error]);
    }

    // Close the statement
    $stmt->close();

    // Close the database connection
    $conn->close();
    exit; // Stop further execution
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance System</title>
    <style>
        body {
            font-family: sans-serif;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

       /* CSS for the attendance table */
.attendance-table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
    font-family: sans-serif; /* Add a font for better readability */
    border: 1px solid #ddd; /* Add a subtle border around the table */
}

.attendance-table th, .attendance-table td {
    padding: 8px;
    text-align: left;
    border: 1px solid #ddd;
}

.attendance-table th {
    background-color: #007bff; /* Light blue for header background */
    color: #fff; /* White text for contrast */
    font-weight: bold; /* Make header text bold */
}

.attendance-table tr:nth-child(even) {
    background-color: #f9f9f9; /* Light gray for even rows */
}

/* Style for the "Present" status */
.attendance-table td:nth-child(6):contains("Present") {
    background-color: #90ee90; /* Light green for "Present" */
    color: #000; /* Black text for contrast */
}

/* Style for the "Absent" status */
.attendance-table td:nth-child(6):contains("Absent") {
    background-color: #f08080; /* Light red for "Absent" */
    color: #000; /* Black text for contrast */
}

/* Style for the "Leave" status */
.attendance-table td:nth-child(6):contains("Leave") {
    background-color: #ffd700; /* Light yellow for "Leave" */
    color: #000; /* Black text for contrast */
}

/* Style for the "Late" status */
.attendance-table td:nth-child(6):contains("Late") { 
    background-color: #FFA500; /* Orange for "Late" */
    color: #000; /* Black text for contrast */
}

        /* Style for the success prompt */
        #successPrompt {
            position: fixed; /* Position it relative to the viewport */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* Center the prompt */
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            color: white;
            padding: 20px;
            border-radius: 5px;
            z-index: 100; /* Ensure it's on top */
        }
    </style>
</head>
<body>

<h2>Attendance System</h2>

<form id="attendanceForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="employee_id">Employee ID:</label>
    <input type="text" name="employee_id" id="employee_id" required><br><br>

    <label for="date">Date:</label>
    <input type="date" name="date" id="date" required><br><br>

    <label for="time_in">Time In:</label>
    <input type="time" name="time_in" id="time_in" required><br><br>

    <label for="time_out">Time Out:</label>
    <input type="time" name="time_out" id="time_out" required><br><br>

    <label for="status">Status:</label>
    <select name="status" id="status">
        <option value="Present">Present</option>
        <option value="Absent">Absent</option>
        <option value="Leave">Leave</option>
        <option value="Late">Late</option> <!-- Added Late option -->
    </select><br><br>

    <input type="submit" value="Add Attendance">
</form>

<?php 
    // Display the attendance data (call the function here)
    getAttendanceData(); 
?>

    <script>
        // Get the form element
        const attendanceForm = document.getElementById('attendanceForm');
        // Get the table element
        const attendanceTable = document.querySelector('.attendance-table');

        // Add an event listener to the form's submit event
        attendanceForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Get the form data
            const formData = new FormData(attendanceForm);

            // Get values from the form
            const employeeId = formData.get('employee_id');
            const date = formData.get('date');
            const timeIn = formData.get('time_in');
            const timeOut = formData.get('time_out');
            const status = formData.get('status');

            // Send the data to the server using AJAX
            fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                // Handle the response from the server
                if (response.ok) {
                    return response.json(); // Parse the response as JSON
                } else {
                    throw new Error('Network response was not ok.');
                }
            })
            .then(data => {
                // Display the success message (if any)
                if (data.success) {
                    // Create a success message element
                    const successMessage = document.createElement('div');
                    successMessage.id = 'successPrompt'; 
                    successMessage.innerHTML = '<div style=\"text-align: center; font-size: 20px; color: green;\">' + data.message + '</div>'; 
                    document.body.appendChild(successMessage);

                    // Hide the success message after 3 seconds
                    setTimeout(function() {
                        successMessage.remove();
                    }, 3000);

                    // Add the new row to the table after successful insertion
                    const newRow = attendanceTable.insertRow();
                    newRow.insertCell().textContent = data.employeeId; // Display employeeId
                    newRow.insertCell().textContent = data.employeeName; // Employee Name
                    newRow.insertCell().textContent = date; // Date
                    newRow.insertCell().textContent = timeIn; // Time In
                    newRow.insertCell().textContent = timeOut; // Time Out
                    const statusCell = newRow.insertCell();
                    statusCell.textContent = status; // Status
                    statusCell.style.backgroundColor = getStatusColor(status); // Apply status color

                    // Clear the form
                    attendanceForm.reset(); // Clear the form fields
                } else {
                    // Handle errors
                    // Display an error message
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                // Handle errors
                console.error('Fetch error:', error);
            });
        });

        // This JavaScript code will be executed after the page loads
        // It will automatically hide the prompt after 3 seconds
        setTimeout(function() {
            var successPrompt = document.getElementById('successPrompt');
            if (successPrompt) {
                successPrompt.remove(); // Remove the prompt from the DOM
            }
        }, 3000); // 3000 milliseconds (3 seconds)
    </script>

</body>
</html>