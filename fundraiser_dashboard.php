<?php
session_start();
include 'db.php'; // Include your database connection file

// Check if the user is logged in and is a raiser
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'raiser') {
    header('Location: login.php');
    exit;
}

// Fetch campaigns from the database
$user_id = $_SESSION['user']['id'];
$query = "SELECT * FROM campaigns WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fundraiser Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Your Campaigns</h2>
    
    <?php if ($result->num_rows == 0): ?>
        <div class="alert alert-warning">You have no campaigns yet. Please create one!</div>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Campaign Title</th>
                    <th>Description</th>
                    <th>Amount Raised</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['description'])); ?></td>
                        <td><?php echo number_format($row['amount_raised'], 2); ?></td> <!-- Display formatted amount_raised -->
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h2 class="mt-5">Create New Campaign</h2>
    <form method="POST" action="create_campaign.php">
        <div class="form-group">
            <label>Campaign Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label>Goal Amount</label>
            <input type="number" name="goal_amount" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Campaign</button>
    </form>

    <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
</div>
</body>
</html>
