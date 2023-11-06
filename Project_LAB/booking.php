<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $vehicle = $_POST['vehicle'];
    $email = $_POST['email'];
    $phone = $_POST['number'];
    $message = $_POST['message'];

    $pickup_date = $_POST['pickup_date'];
    $pickup_time = $_POST['pickup_time'];
    $pickup = $pickup_date.' '.$pickup_time;

    $return_date = $_POST['return_date'];
    $return_time = $_POST['return_time'];
    $ret = $return_date.' '.$return_time;


    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "project_lab_db";


    // Create a connection to the database
    $conn = new mysqli($servername, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    // SQL query to insert data into the database
    $sql = "INSERT INTO booking (fname, vehicle, pickup, return_date, email, phone, comment) VALUES ('$name', '$vehicle','$pickup', '$ret', '$email', '$phone', '$message')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION["name"] = $name;
        $_SESSION["vehicle"] = $vehicle;
        $_SESSION["pickup"] = $pickup;
        $_SESSION["ret"] = $ret;
        
        echo '<script type="text/javascript">
           window.location = "acknowledgement.php"
           </script>';
    } else {
        echo "Error: ". $conn->error;
    }

    // Close the database connection
    $conn->close();

} else {
    // Handle the case where the page is accessed directly (not through form submission)
    echo "Access Denied";
}
?>

