<?php
session_start();
include 'db.php'; // Include your database connection file

// Check if the user is logged in and is a giver
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'giver') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user']['id'];
    $campaign_id = $_POST['campaign_id'];
    $amount = $_POST['amount'];

    // Validate the donation amount
    if ($amount <= 0) {
        echo "Invalid donation amount.";
        exit;
    }

    // Insert the donation into the donations table
    $insert_donation = "INSERT INTO donations (user_id, campaign_id, amount, date) 
                        VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($insert_donation);
    $stmt->bind_param("iid", $user_id, $campaign_id, $amount);

    if ($stmt->execute()) {
        // Update the amount raised for the selected campaign
        $update_campaign = "UPDATE campaigns 
                            SET amount_raised = amount_raised + ? 
                            WHERE id = ?";
        $stmt_update = $conn->prepare($update_campaign);
        $stmt_update->bind_param("di", $amount, $campaign_id);

        if ($stmt_update->execute()) {
            // Redirect back to the giver dashboard with a success message
            header("Location: giver_dashboard.php?success=1");
            exit;
        } else {
            echo "Error updating campaign: " . $conn->error;
        }
    } else {
        echo "Error inserting donation: " . $conn->error;
    }
} else {
    // If the request is not POST, redirect to the giver dashboard
    header('Location: giver_dashboard.php');
    exit;
}
?>

