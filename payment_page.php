<?php
session_start();
include 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Get campaign ID and amount from the URL
$campaign_id = isset($_GET['campaign_id']) ? intval($_GET['campaign_id']) : 0;
$amount = isset($_GET['amount']) ? floatval($_GET['amount']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve payment details from the form
    $cardNumber = $_POST['cardNumber'];
    $expDate = $_POST['expDate'];
    $cvv = $_POST['cvv'];
    $user_id = $_SESSION['user']['id']; // Assuming user ID is stored in session
    $donationAmount = floatval($_POST['amount']);

    // Insert payment details into the database
    $stmt = $conn->prepare("INSERT INTO payments (campaign_id, user_id, amount, card_number, exp_date, cvv) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $campaign_id, $user_id, $donationAmount, $cardNumber, $expDate, $cvv);

    if ($stmt->execute()) {
        // Payment successfully recorded, now update the campaign's amount_raised

        // Fetch current amount_raised for the campaign
        $campaignQuery = $conn->prepare("SELECT amount_raised FROM campaigns WHERE id = ?");
        $campaignQuery->bind_param("i", $campaign_id);
        $campaignQuery->execute();
        $campaignQuery->bind_result($currentAmountRaised);
        $campaignQuery->fetch();
        $campaignQuery->close();

        // Calculate new amount_raised
        $newAmountRaised = $currentAmountRaised + $donationAmount;

        // Update the campaign's amount_raised
        $updateStmt = $conn->prepare("UPDATE campaigns SET amount_raised = ? WHERE id = ?");
        $updateStmt->bind_param("di", $newAmountRaised, $campaign_id);

        if ($updateStmt->execute()) {
            echo "<script>alert('Payment successful! Amount raised has been updated.');</script>";
            header('Location: giver_dashboard.php'); // Adjust this as needed
            exit;
        } else {
            echo "<script>alert('Failed to update campaign amount raised.');</script>";
        }

        $updateStmt->close();
    } else {
        echo "<script>alert('Payment failed. Please try again.');</script>";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .payment-form {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="payment-form">
            <h2>Payment Details</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" value="<?php echo htmlspecialchars($amount); ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="cardNumber">Card Number</label>
                    <input type="text" name="cardNumber" id="cardNumber" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="expDate">Expiration Date (MM/YY)</label>
                        <input type="text" name="expDate" id="expDate" class="form-control" placeholder="MM/YY" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="cvv">CVV</label>
                        <input type="text" name="cvv" id="cvv" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Pay Now</button>
            </form>
        </div>
    </div>
</body>
</html>



