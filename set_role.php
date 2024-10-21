<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['role'])) {
    $role = $_POST['role'];
    $_SESSION['role'] = $role; // Set the selected role in the session

    if ($role == 'fundraiser') {
        header('Location: fundraiser_dashboard.php');
    } elseif ($role == 'giver') {
        header('Location: giver_dashboard.php');
    }
    exit;
} else {
    header('Location: role_selection.php');
    exit;
}
?>
