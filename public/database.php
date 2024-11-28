
<?php
function getDbConnection()
{
    $hostName = "localhost";
    $dbUser = "root";
    $dbPassword = "";
    $dbName = "first_task";

    $conn = new mysqli($hostName, $dbUser, $dbPassword, $dbName);

    if ($conn->connect_error) {
        die("Something went wrong: " . $conn->connect_error);
    }
    return $conn; 
}
?>

