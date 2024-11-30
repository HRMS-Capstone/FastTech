<?php
// Start the session
session_start();

// Database connection
$host = 'localhost';
$db = 'hrm'; 
$user = 'root'; 
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle POST request for login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Get username and password from form
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Sanitize username (to avoid SQL injection, although prepared statements are used)
        $username = $conn->real_escape_string($username);

        // Check user credentials
        $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($hashed_password, $role);
        $stmt->fetch();

        if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
            // Valid login
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role; // Store role in session

            // Remember Me functionality
            if (isset($_POST['remember_me'])) {
                setcookie("username", $username, time() + (86400 * 30), "/"); // 30 days cookie
            }

            header("Location: welcome.php");
            exit();
        } else {
            // Invalid credentials
            $message = "Invalid username or password.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        p {
            text-align: center;
            color: red;
        }
        .remember-me {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Login</h2>
    <p><?php echo $message; ?></p>

    <!-- Login Form -->
    <form method="POST" action="">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>

        <!-- Remember Me Checkbox -->
        <div class="remember-me">
            <label><input type="checkbox" name="remember_me"> Remember Me</label>
        </div>

        <input type="submit" name="login" value="Login">
    </form>

    <!-- Register link -->
    <form action="register.php" method="GET" style="margin-top: 10px;">
        <input type="submit" value="Register" style="width: 100%;">
    </form>
</div>

</body>
</html>
