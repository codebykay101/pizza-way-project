<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "codejttf_5Imp3r_mAk3r_B0x_h4mS";
$password = "us^GVs2LGwn3k7";
$dbname = "codejttf_pizzaway_server";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
