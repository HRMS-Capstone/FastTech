<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        .welcome {
            margin-bottom: 20px;
        }
        a {
            display: block; /* Make links stack below each other */
            margin: 10px 0;
            text-decoration: none;
            color: #28a745;
        }
        a:hover {
            text-decoration: underline;
        }
        .logout {
            background-color: #dc3545;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        .logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Dashboard</h2>
    <p class="welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</p>
    <a href="profile.php">View Profile</a>
    <a href="employees.php">Employees</a>  <!-- Link to Employees page -->
    <a href="attendance.php">Attendance</a>  <!-- Link to Attendance page -->
    <a href="settings.php">Settings</a>
    <form action="login.php" method="POST" style="display:inline;">
        <button type="submit" name="logout" class="logout">Logout</button>
    </form>
</div>

</body>
</html>