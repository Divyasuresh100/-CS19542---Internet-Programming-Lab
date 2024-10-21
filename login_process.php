<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user;
        if ($user['role'] == 'giver') {
            header('Location: giver_dashboard.php');
        } else {
            header('Location: raiser_dashboard.php');
        }
    } else {
        echo "Invalid credentials";
    }
}
?>
