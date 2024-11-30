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

// Fetch payroll data from the database
$sql = "SELECT id, employee_id, amount, pay_date FROM payroll";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<h2>Payroll List</h2>';
    echo '<table>';
    echo '<thead><tr><th>ID</th><th>Employee ID</th><th>Amount</th><th>Pay Date</th></tr></thead>';
    echo '<tbody>';
    
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['employee_id'] . '</td>';
        echo '<td>' . $row['amount'] . '</td>';
        echo '<td>' . $row['pay_date'] . '</td>';
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
} else {
    echo 'No payroll records found.';
}

$conn->close();
?>
