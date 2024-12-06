<?php
// Database connection
$host = "localhost";
$db = "hrm";
$user = "root"; // Change as necessary
$pass = ""; // Change as necessary

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Employee details
$employee_names = ["John Doe", "Jane Smith", "Alice Johnson"]; // Example names
$selected_employee_name = isset($_POST['employee_name']) ? $_POST['employee_name'] : $employee_names[0]; // Default to the first employee
$payroll_period = "September 1-15, 2024";
$total_days_present = 15;
$night_shift_hours = 0;
$overtime_hours = 0;

// Earnings
$base_pay = 4950.00;
$night_shift_differential = 345.63;
$overtime_pay = 0; // Assuming no overtime for simplicity
$campaign_allowance = 0; // Example value
$regular_holiday_pay = 0; // Example value

// Special Non-Working Pay Deductions
$paid_leave_used = 0; // Example value for paid leave used
$leave_without_pay = 0; // Example value for leave without pay
$total_days_absent = 0; // Example value for total days absent

// Deductions
$late_30mins_count = 0; // Example: 2 instances of being 30 minutes late
$late_1hour_count = 0; // Example: 1 instance of being 1 hour late
$undertime_30mins_count = 0; // Example: 1 instance of being 30 minutes undertime
$undertime_1hour_count = 0; // Example: 0 instances of being 1 hour undertime
$sss = 200.00; // Example value for SSS deduction
$philhealth = 200; // Example value
$pag_ibig = 0; // Example value
$cash_advance = 0; // Example value

// Late deductions calculation
$late_30mins_deduction = 31.25; // Deduction for 30 minutes late
$late_1hour_deduction = 62.50; // Deduction for 1 hour late
$lates = ($late_30mins_count * $late_30mins_deduction) + ($late_1hour_count * $late_1hour_deduction);

// Undertime deductions calculation
$undertime_30mins_deduction = 31.25; // Deduction for 30 minutes undertime
$undertime_1hour_deduction = 62.50; // Deduction for 1 hour undertime
$undertimes = ($undertime_30mins_count * $undertime_30mins_deduction) + ($undertime_1hour_count * $undertime_1hour_deduction);

// Special Non-Working Pay deductions calculation
$special_non_working_deductions = $paid_leave_used + $leave_without_pay;

// Calculations
$gross_pay = $base_pay + $night_shift_differential + $overtime_pay + $campaign_allowance + $regular_holiday_pay;
$total_deductions = $lates + $undertimes + $sss + $philhealth + $pag_ibig + $cash_advance + $special_non_working_deductions;
$net_pay = $gross_pay - $total_deductions;

// Output in a table format
echo "<style>
    body {
        text-align: center;
        font-family: Arial, sans-serif;
    }
    table {
        margin: 0 auto;
        border-collapse: collapse;
        width: 80%;
    }
    th, td {
        border: 1px solid #000;
        padding: 10px;
        text-align: left;
        color: black;
    }
    th {
        background-color: #f2f2f2;
    }
    .net-pay {
        background-color: #f2f2f2; /* Same background color as header */
    }
</style>";

echo "<h1>Payroll Summary</h1>";
echo "<form method='POST'>";
echo "<label for='employee_name'>Select Employee Name:</label>";
echo "<select name='employee_name' id='employee_name' onchange='this.form.submit()'>";
foreach ($employee_names as $name) {
    $selected = ($name == $selected_employee_name) ? "selected" : "";
    echo "<option value='$name' $selected>$name</option>";
}
echo "</select>";
echo "</form>";

echo "<table>";
echo "<tr><th>Field</th><th>Amount</th></tr>";
echo "<tr><td>Employee Name</ td><td>$selected_employee_name</td></tr>";
echo "<tr><td>Payroll Period</td><td>$payroll_period</td></tr>";
echo "<tr><td>Total Days Present</td><td>$total_days_present</td></tr>";
echo "<tr><td>Night Shift Differential</td><td>₱" . number_format($night_shift_differential, 2) . "</td></tr>";
echo "<tr><td>Overtime Pay</td><td>₱" . number_format($overtime_pay, 2) . "</td></tr>";
echo "<tr><td>Campaign Allowance</td><td>₱" . number_format($campaign_allowance, 2) . "</td></tr>";
echo "<tr><td>Regular Holiday Pay</td><td>₱" . number_format($regular_holiday_pay, 2) . "</td></tr>";

// Special Non-Working Pay Deductions Section
echo "<tr><td colspan='2'><strong>Special Non-Working Pay Deductions</strong></td></tr>";
echo "<tr><td>Paid Leave Used</td><td>₱" . number_format($paid_leave_used, 2) . "</td></tr>";
echo "<tr><td>Leave Without Pay</td><td>₱" . number_format($leave_without_pay, 2) . "</td></tr>";
echo "<tr><td>Total Days Absent</td><td>$total_days_absent</td></tr>";

// Deductions Section
echo "<tr><td colspan='2'><strong>Deductions</strong></td></tr>";
echo "<tr><td>Lates</td><td>₱" . number_format($lates, 2) . "</td></tr>";
echo "<tr><td>Undertime</td><td>₱" . number_format($undertimes, 2) . "</td></tr>";
echo "<tr><td>SSS</td><td>₱" . number_format($sss, 2) . "</td></tr>";
echo "<tr><td>PhilHealth</td><td>₱" . number_format($philhealth, 2) . "</td></tr>";
echo "<tr><td>Pag-IBIG</td><td>₱" . number_format($pag_ibig, 2) . "</td></tr>";
echo "<tr><td>Cash Advance</td><td>₱" . number_format($cash_advance, 2) . "</td></tr>";
echo "<tr><td>Total Deductions</td><td>₱" . number_format($total_deductions, 2) . "</td></tr>";
echo "<tr><td><strong>Net Pay</strong></td><td><strong>₱" . number_format($net_pay, 2) . "</strong></td></tr>";
echo "</table>";

// Add Print and Email buttons below the table
echo "<div style='margin-top: 20px; display: flex; justify-content: space-between; width: 80%; margin: 0 auto;'>";
echo "<button onclick='window.print()'>Print</button>";
echo "<button onclick='sendEmail()'>Send via Email</button>";
echo "</div>";

// JavaScript function for sending email (this is just a placeholder)
echo "<script>
function sendEmail() {
    alert('Email functionality is not implemented. You need to set up a server-side script to handle this.');
}
</script>";

// Add CSS styles
echo "<style>
    .print-button, .email-button {
        padding: 40x 40px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 20px;
    }
    .print-button {
        background-color: #4CAF50;
        color: #fff;
    }
    .print-button:hover {
        background-color: #3e8e41;
    }
    .email-button {
        background-color: #03A9F4;
        color: #fff;
    }
    .email-button:hover {
        background-color: #039BE5;
    }
</style>";
?>
