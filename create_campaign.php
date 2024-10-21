<?php
session_start();
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $goal_amount = $_POST['goal_amount'];
    $user_id = $_SESSION['user']['id'];

    // Insert new campaign with initial amount_raised set to 0
    $query = "INSERT INTO campaigns (user_id, title, description, goal_amount, amount_raised) VALUES (?, ?, ?, ?, 0)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issd", $user_id, $title, $description, $goal_amount);

    if ($stmt->execute()) {
        // Redirect back to dashboard or show success message
        header('Location: fundraiser_dashboard.php');
        exit;
    } else {
        echo "Error creating campaign.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Campaign</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Create a New Campaign</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="title">Campaign Title:</label>
            <input type="text" class="form-control" name="title" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="goal_amount">Goal Amount:</label>
            <input type="number" step="0.01" class="form-control" name="goal_amount" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Campaign</button>
    </form>
    <a href="fundraiser_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
</body>
</html>
