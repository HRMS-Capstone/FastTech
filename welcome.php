<?php  
session_start();

// Logout functionality
if (isset($_POST['logout'])) {
    session_unset(); // Unset session variables
    session_destroy(); // Destroy session
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Session timeout logic
$timeout_duration = 1800; // 30 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset(); // Unset session variables
    session_destroy(); // Destroy session
    header("Location: login.php"); // Redirect to login page
    exit();
}
$_SESSION['last_activity'] = time(); // Update last activity time

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'] ?? 'common'; // Default role is 'common'

// Database connection
$host = 'localhost';
$db = 'hrm';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Replace dynamic data query with mock data for now
$employee_count = 12; // Replace with actual query result
$payroll_count = 15;  // Replace with actual query result
$hiring_count = 8;    // Replace with actual query result
$performance_count = 5; // Replace with actual query result

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    min-height: 100vh; /* Ensure full height of the page */
    margin: 0;
    padding: 0;
}

.sidebar {
    position: fixed; /* Make the sidebar fixed */
    top: 0;
    left: 0;
    width: 250px;
    background: black;
    color: white;
    padding: 20px;
    height: 100vh; /* Full height of the screen */
    display: flex;
    flex-direction: column;
    justify-content: center; /* Center the content vertically */
    align-items: center; /* Center the items horizontally */
    text-align: center; /* Optional, centers the text */
    box-sizing: border-box; /* Ensures padding is included in width and height */
}

.sidebar h2 {
    margin-top: 0;
    font-size: 24px;
    color: white;
}

.sidebar img {
    width: 100px;
    height: 100px;
    border-radius: 50%; /* Circle the logo */
    object-fit: cover;
    margin-bottom: 20px;
}

.sidebar a {
    display: block;
    margin: 10px 0;
    text-decoration: none;
    color: #28a745; /* Green color for links */
    font-size: 18px; /* Adjust font size */
}

.sidebar a:hover {
    text-decoration: underline;
    color: #61ff61; /* Lighter green on hover */
}

.main-content {
    margin-left: 250px; /* Leave space for the fixed sidebar */
    flex: 1;
    padding: 20px;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.logout {
    /*background-color: #dc3545; */
    /*color: white;*/
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 20px;
}

.logout:hover {
    background-color: #c82333;
}

.chart-container {
    position: relative;
    margin: auto;
    height: 40vh;
    width: 80vw;
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

<div class="sidebar">
    <!-- Logo as a clickable link -->
    <a href="tamtech.html">
        <img src="images/tamtech.jpg" alt="Business Logo" width="100" height="100"> <!-- Logo inside a circle -->
    </a>
    <a href="#" id="attendance-link">Attendance</a>
    <a href="#" id="employees-link">Employees</a>
    <?php if ($role === 'admin'): ?>
        <a href="#" id="payroll-link">Payroll</a>
        <a href="#">Application</a> 
        <a href="#">Recruitment</a> 
    <?php endif; ?>
    <a href="#">Performance</a>
    <a href="#">Reports</a>

    <!-- Logout button form at the bottom -->
    <form action="" method="POST">
        <button type="submit" name="logout" class="logout">Logout</button>
    </form>
</div>


<!-- Main content section -->
<div class="main-content" id="main-content">
    <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    
    <h3>Dashboard Overview</h3>
    
    <div class="chart-container">
        <canvas id="employeeChart"></canvas>
    </div>

    <h3>Recent Activities</h3>
    <ul>
        <li>Employee John Doe was added on Oct 1, 2024.</li>
        <li>Payroll processed for October 2024.</li>
        <li>Updated performance reviews for several employees.</li>
    </ul>
</div>

<script>
    const ctx = document.getElementById('employeeChart').getContext('2d');
    const employeeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Employees', 'Payroll', 'Hiring', 'Recruiting', 'Performance'],
            datasets: [{
                label: 'Monthly Overview',
                data: [<?php echo $employee_count; ?>, <?php echo $payroll_count; ?>, <?php echo $hiring_count; ?>, <?php echo $performance_count; ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Handle the "Employees" link click
    $("#employees-link").click(function() {
        $.ajax({
            url: "get_employees.php", // PHP file that fetches employee data
            type: "GET",
            success: function(response) {
                // Update the main content with the employee list
                $("#main-content").html(response);
            },
            error: function() {
                alert("Error loading employee data.");
            }
        });
    });

    // Handle the "Payroll" link click
    $("#payroll-link").click(function() {
        $.ajax({
            url: "get_payroll.php", // PHP file that fetches payroll data
            type: "GET",
            success: function(response) {
                // Update the main content with the payroll list
                $("#main-content").html(response);
            },
            error: function() {
                alert("Error loading payroll data.");
            }
        });
    });

    // Handle the "Attendance" link click
    $("#attendance-link").click(function() {
        $.ajax({
            url: "attendance.php", // PHP file that fetches attendance data
            type: "GET",
            success: function(response) {
                // Update the main content with the attendance list
                $("#main-content").html(response);
            },
            error: function() {
                alert("Error loading attendance data.");
            }
        });
    });
</script>

</body>
</html>
