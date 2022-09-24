<?php
$hostName = "localhost";
$userName = "root";
$password = "Gravity#007";
$databaseName = "test_live";
 $conn = new mysqli($hostName, $userName, $password, $databaseName);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>