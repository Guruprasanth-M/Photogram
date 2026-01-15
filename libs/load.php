<?php

function load_template($name)
{
    include $_SERVER['DOCUMENT_ROOT']."/app/_templates/$name.php"; //not consistant.
}

function validate_credentials($username, $password)
{
    if ($username == "123@123" and $password == "password") {
        return true;
    } else {
        return false;
    }
}

function signup($user, $pass, $email, $phone)
{
    $servername = "test";
    $username = "test";
    $password = "test";
    $dbname = "test";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO `auth` (`username`, `password`, `email`, `phone`, `active`)
    VALUES ('$user', '$pass', '$email', '$phone', '1');";
    $error = false;
    try {
        if ($conn->query($sql) === true) {
            // return true on success to match callers expecting a truthy success
            $error = true;
        } else {
            $error = $conn->error;
        }
    } catch (mysqli_sql_exception $e) {
        // catch database exceptions (e.g., duplicate key) and return the message
        $error = $e->getMessage();
    }
    
    $conn->close();
    return $error;
}
