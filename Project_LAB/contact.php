<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];



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
    $sql = "INSERT INTO issues (fname, email, msg, notes) VALUES ('$name', '$email', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "Thank you ". $name . ", Your issue has been submitted.";
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

