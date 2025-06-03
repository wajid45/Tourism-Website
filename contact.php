<?php
// Database connection settings
$host = "localhost";
$dbname = "adventure";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get and sanitize form data
$full_name = $conn->real_escape_string($_POST['full_name']);
$email = $conn->real_escape_string($_POST['email']);
$phone = $conn->real_escape_string($_POST['phone']);
$travel_date = $_POST['travel_date'];
$travelers = (int)$_POST['travelers'];
$country = $conn->real_escape_string($_POST['country']);
$destination = $conn->real_escape_string($_POST['destination']);
$remarks = $conn->real_escape_string($_POST['remarks']);

// SQL Insert Query
$sql = "INSERT INTO tour_bookings (full_name, email, phone, travel_date, travelers, country, destination, remarks)
        VALUES ('$full_name', '$email', '$phone', '$travel_date', $travelers, '$country', '$destination', '$remarks')";

if ($conn->query($sql) === TRUE) {
    echo "Booking successful! We will contact you shortly.";
    header('Location: index.html');
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
