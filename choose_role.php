<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Role</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5 text-center">
    <h2>Choose Role</h2>
    <a href="raiser_dashboard.php" class="btn btn-primary">Raise Funds</a>
    <a href="giver_dashboard.php" class="btn btn-success">Donate Funds</a>
</div>
</body>
</html>
