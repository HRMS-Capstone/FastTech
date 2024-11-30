<?php
$host = 'localhost'; // your database host
$db = 'hrm'; // updated database name
$user = 'root'; // your database username
$pass = ''; // your database password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Login logic
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION['username'] = $username; // Store username in session
            header("Location: welcome.php"); // Redirect to welcome page
            exit();
        } else {
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
        .button-container {
            display: flex;
            justify-content: space-between; /* Space out buttons evenly */
            margin-top: 10px; /* Add some top margin */
        }
        .button-container input[type="submit"] {
            flex: 1; /* Make buttons take equal space */
            margin: 0 5px; /* Add some space between buttons */
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
        
        <div class="button-container">
            <input type="submit" name="login" value="Login">
        </div>
    </form>

    <!-- Separate form for Register button -->
    <form action="register.php" method="GET" style="margin-top: 10px;">
        <input type="submit" value="Register" style="width: 100%;">
    </form>
</div>

</body>
</html>
