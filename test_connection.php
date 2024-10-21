<?php
$conn = new mysqli('localhost', 'root', '', 'gofundme_mini');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
