<?php
// Database connection details
$host = 'localhost';
$db = 'hrm';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all employees from the database
$sql = "SELECT id, first_name, last_name, email, job_title FROM employees";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<h2>Employees List</h2>';
    echo '<table border="1">';
    echo '<thead><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Job Title</th><th>Actions</th></tr></thead>';
    echo '<tbody>';

    while ($row = $result->fetch_assoc()) {
        $employeeId = $row['id'];
        $employeeName = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); // Full name of the employee

        // Display each employee in a row
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['first_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['last_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['email']) . '</td>';
        echo '<td>' . htmlspecialchars($row['job_title']) . '</td>';

        // Action buttons for Edit and Delete
        echo '<td>';
        echo '<button class="btn-action btn-edit" onclick="window.location.href=\'edit_employee.php?id=' . $employeeId . '\'">Edit</button> ';
        echo '<button class="btn-action btn-delete" onclick="deleteEmployee(' . $employeeId . ', \'' . $employeeName . '\')">Delete</button>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
} else {
    echo 'No employees found.';
}

$conn->close();
?>

<!-- Add Employee Button (Floating button) -->
<button class="plus-button" onclick="openModal()">+</button>

<!-- Modal for Add Employee -->
<div id="employeeModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Add Employee</h2>
        <form id="employeeForm" onsubmit="return submitForm(event)">
            <label for="first_name">First Name:</label><br>
            <input type="text" name="first_name" required><br><br>

            <label for="last_name">Last Name:</label><br>
            <input type="text" name="last_name" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" name="email" required><br><br>

            <label for="job_title">Job Title:</label><br>
            <input type="text" name="job_title" required><br><br>

            <button type="submit">Add Employee</button>
        </form>
    </div>
</div>

<script>
    // Open the modal
    function openModal() {
        document.getElementById("employeeModal").style.display = "flex";
    }

    // Close the modal
    function closeModal() {
        document.getElementById("employeeModal").style.display = "none";
    }

    // Close modal if clicked outside
    window.onclick = function(event) {
        if (event.target == document.getElementById("employeeModal")) {
            closeModal();
        }
    }

    // Function to handle AJAX form submission without page reload
    function submitForm(event) {
        event.preventDefault(); // Prevent the default form submission (page reload)

        var form = document.getElementById("employeeForm");
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "add_employees.php", true); // Send the form data to add_employees.php
        xhr.onload = function () {
            if (xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert(response.message); // Success message
                    closeModal(); // Close the modal after adding employee
                    location.reload(); // Reload the page to show the new employee
                } else {
                    alert("Error adding employee: " + response.error); // Display error message
                }
            } else {
                alert("Error with the request.");
            }
        };
        xhr.send(formData);
    }

    // Function to delete an employee with confirmation
    function deleteEmployee(employeeId, employeeName) {
        if (confirm("Are you sure you want to delete " + employeeName + "?")) {
            // Make AJAX request to delete the employee
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_employee.php", true);
            var formData = new FormData();
            formData.append('id', employeeId);
            xhr.onload = function () {
                if (xhr.status == 200) {
                    var response = xhr.responseText;
                    if (response == 'success') {
                        alert('Employee deleted successfully!');
                        location.reload(); // Reload to update the list
                    } else {
                        alert('Error deleting employee.');
                    }
                }
            };
            xhr.send(formData);
        }
    }
</script>

<style>
    /* Floating + Button */
    .plus-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #28a745;
        color: white;
        font-size: 24px;
        padding: 15px;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }
    .plus-button:hover {
        background-color: #218838;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        width: 400px;
    }
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* Button styles for Edit and Delete */
    .btn-action {
        padding: 6px 12px;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        margin: 5px;
    }

    .btn-edit {
        background-color: #007bff;
    }

    .btn-edit:hover {
        background-color: #0056b3;
    }

    .btn-delete {
        background-color: #dc3545;
    }

    .btn-delete:hover {
        background-color: #c82333;
    }

    .btn-action:focus {
        outline: none;
    }
</style>
