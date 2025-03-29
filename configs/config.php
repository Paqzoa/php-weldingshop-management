
<?php

$host = "sql305.infinityfree.com"; // Get this from InfinityFree MySQL Databases
$username = "if0_37712493";
$password = "Moses2339";
$database = "if0_37712493_managestock";

$mysqli = new mysqli($host, $username, $password, $database);

# Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

?>
