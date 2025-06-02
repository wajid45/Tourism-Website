<?php
$conn = mysqli_connect("localhost", "root", "", "adventure");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Get and sanitize form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);

    // Insert into database
    $sql = "INSERT INTO contacts (name, email, country, remarks) VALUES ('$name', '$email', '$country', '$remarks')";

    if (mysqli_query($conn, $sql)) {
        echo "<h2>Form Submitted Successfully</h2>";
    } else {
        echo "<h2>Form Submission Failed: " . mysqli_error($conn) . "</h2>";
    }
}

mysqli_close($conn);
?>