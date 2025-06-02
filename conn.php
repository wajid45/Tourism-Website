<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adventure";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    echo"Connection failed ";
}
else{
    echo("Connected successfully");
}

?>