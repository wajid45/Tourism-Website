<?php

include conn.php;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $country = $_POST['country'];
    $remarks = $_POST['remarks'];

    $sql = "INSERT Into adventure(name, email, country, remarks) VALUES ('$name', '$email', '$country', '$remarks')";

    if(mysqli_query($conn,$sql)){
        echo"Form Submitted Sucessfully";
    }
    else{
        echo"Form Submission Failed";
    }

?>