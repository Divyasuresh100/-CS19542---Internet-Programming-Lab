<?php
session_start();
include 'db.php';

// Ensure the user is logged in and is a giver
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'giver') {
    header('Location: login.php');
    exit;
}

// Fetch all campaigns
$query = "SELECT * FROM campaigns";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Giver Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        // JavaScript to redirect to the payment page
        function redirectToPayment(campaignId, amount) {
            // Redirect to the payment page URL with campaign ID and amount as query parameters
            window.location.href = 'payment_page.php?campaign_id=' + campaignId + '&amount=' + amount; 
        }
    </script>
</head>
<body>
<div class="container mt-5">
    <h2>Available Campaigns</h2>

    <?php if ($result->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Campaign Title</th>
                    <th>Description</th>
                    <th>Goal Amount</th>
                    <th>Amount Raised</th>
                    <th>Donate</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($campaign = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($campaign['title']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($campaign['description'])); ?></td>
                        <td><?php echo htmlspecialchars($campaign['goal_amount']); ?></td>
                        <td><?php echo htmlspecialchars($campaign['amount_raised']); ?></td>
                        <td>
                            <!-- Donation form -->
                            <form onsubmit="event.preventDefault(); redirectToPayment(<?php echo $campaign['id']; ?>, this.amount.value);">
                                <input type="number" name="amount" placeholder="Amount" required min="1" class="form-control mb-2">
                                <button type="submit" class="btn btn-primary">Donate</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No campaigns available at the moment.</p>
    <?php endif; ?>

    <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
</div>
</body>
</html>
