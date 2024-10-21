<?php
session_start();
include 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Get the selected role (giver or raiser)

    // Fetch the user by email
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user info in session
            $_SESSION['user'] = $user;

            // Update role if necessary
            if ($role == 'giver' && !$user['is_giver']) {
                // If the user selects 'giver' and they are not yet a giver, update the role
                $updateQuery = "UPDATE users SET is_giver = 1 WHERE email = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("s", $email);
                $stmt->execute();
            } elseif ($role == 'raiser' && !$user['is_raiser']) {
                // If the user selects 'raiser' and they are not yet a raiser, update the role
                $updateQuery = "UPDATE users SET is_raiser = 1 WHERE email = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("s", $email);
                $stmt->execute();
            }

            // Redirect based on role selection
            if ($role == 'raiser') {
                header('Location: fundraiser_dashboard.php');
            } elseif ($role == 'giver') {
                header('Location: giver_dashboard.php');
            }
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Login</h2>
    <form method="POST" action="login.php">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Select Role</label>
            <select name="role" class="form-control" required>
                <option value="raiser">Raiser</option>
                <option value="giver">Giver</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    
    <p class="mt-3">Don't have an account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>

