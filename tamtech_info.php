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

// Fetch business information (you can modify this to match your database schema)
$sql = "SELECT * FROM business_info WHERE id = 1"; // Assuming you have a table 'business_info'
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $business = $result->fetch_assoc();
} else {
    die("Business information not found.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #28a745;
        }
        .business-info {
            margin-top: 20px;
        }
        .business-info p {
            font-size: 18px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Business Information</h1>
    <div class="business-info">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($business['name']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($business['address']); ?></p>
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($business['contact']); ?></p>
        <p><strong>Mission:</strong> <?php echo nl2br(htmlspecialchars($business['mission'])); ?></p>
        <p><strong>Founded:</strong> <?php echo htmlspecialchars($business['founded']); ?></p>
    </div>
</div>

</body>
</html>
