<?php
// admin.php

// Check if the user is not logged in, redirect to login.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "project_lab_db";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from booking table where booked=0
$bookingResult = $conn->query("SELECT * FROM booking WHERE booked = 0");

// Fetch data from issues table where resolved=0
$issuesResult = $conn->query("SELECT * FROM issues WHERE resolved = 0");

// Function to update booked or resolved status
function updateStatus($tableName, $id) {
    global $conn;

    if ($tableName == "Booking")
    {
        $updateQuery = "UPDATE booking SET booked = 1 WHERE booking_id = $id";    
    }

    if($tableName == "issues")
    {
        $updateQuery = "UPDATE issues SET resolved = 1 WHERE issues_id = $id";    
    }

    // Execute the query
    $conn->query($updateQuery);
    header("Location: admin.php");
    exit();
}

// Handle form submission for booking table
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];
    $booking_id = mysqli_real_escape_string($conn, $booking_id);

    updateStatus("Booking", $booking_id);
}

// Handle form submission for issues table
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['issues_id'])) {
    $issues_id = $_POST['issues_id'];
    $issues_id = mysqli_real_escape_string($conn, $issues_id);

    updateStatus("issues", $issues_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <style>
        /* Add your styles here */
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }

        h2 {
            text-align: center;
        }

        .logout {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .tabs {
            display: flex;
            justify-content: space-between;
        }

        #updateButton {
            margin-right: 20px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
            text-align: left;
        }

        th, td {
            padding: 10px;
        }
    </style>
</head>
<body>

<h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>

<!-- Logout button -->
<a class="logout" href="?logout">Logout</a>

<!-- Add tabs for booking and issues data -->
<div class="tabs">
    <button onclick="openTab('bookingTab')">Booking</button>
    <button onclick="openTab('issuesTab')">Issues</button>
</div>

<!-- Booking Tab -->
<div id="bookingTab" class="tabcontent">
    <h3>Booking Data</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Vehicle</th>
            <th>Pickup Date</th>
            <th>Return Date</th>
            <th>Booked</th>
        </tr>
        <!-- Display booking data where booked=0 -->
        <?php
        while ($row = $bookingResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['booking_id']}</td>";
            echo "<td>{$row['fname']}</td>";
            echo "<td>{$row['vehicle']}</td>";
            echo "<td>{$row['pickup']}</td>";
            echo "<td>{$row['return_date']}</td>";
            echo "<td><input type='checkbox' name='booked[]' value='{$row['booking_id']}'></td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

<!-- Issues Tab -->
<div id="issuesTab" class="tabcontent">
    <h3>Issues Data</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Notes</th>
            <th>Resolved</th>
        </tr>
        <!-- Display issues data where resolved=0 -->
        <?php
        while ($row = $issuesResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['issues_id']}</td>";
            echo "<td>{$row['fname']}</td>";
            echo "<td>{$row['Email']}</td>";
            echo "<td>{$row['msg']}</td>";
            echo "<td>{$row['Notes']}</td>";
            echo "<td><input type='checkbox' name='resolved[]' value='{$row['issues_id']}'></td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

<form id="updateForm" name="updateForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="updateType">Type:</label>
    <select name="updateType" id="updateType" required>
        <option value="booking">Booking</option>
        <option value="issues">Issues</option>
    </select>

    <label for="updateID">ID:</label>
    <input type="number" name="updateID" id="updateID" required>

    <button type="button" onclick="confirmChange()">Confirm Update</button>
</form>

<!-- JavaScript to handle tabs -->
<script>
    function openTab(tabName) {
        var i, tabcontent, tabs;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tabs = document.getElementsByClassName("tabs");
        for (i = 0; i < tabs.length; i++) {
            tabs[i].className = tabs[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
    }
</script>

<!-- JavaScript to handle confirmation -->
<script>
    function confirmChange() {
        var type = document.getElementById("updateType").value;
        var id = document.getElementById("updateID").value;

        var confirmation = confirm("Are you sure you want to change the status?");
        if (confirmation) {
            
            document.getElementById(type + "_id").value = id;
            document.getElementById("confirm").value = "approve"; // Use a default value
            document.forms["statusForm"].submit();
        } else {
            alert("Change cancelled.");
        }
    }
</script>

<!-- Form to handle status change confirmation -->
<form id="statusForm" name="statusForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="hidden" name="booking_id" id="booking_id">
    <input type="hidden" name="issues_id" id="issues_id">
    <input type="hidden" name="confirm" id="confirm">
</form>

</body>
</html>
