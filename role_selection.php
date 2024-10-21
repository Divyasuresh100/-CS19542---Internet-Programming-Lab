<?php
session_start();
if (!isset($_SESSION['roles'])) {
    header('Location: login.php'); // Redirect if roles are not set
    exit;
}

$roles = $_SESSION['roles']; // Get the available roles for the user
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Role</title>
</head>
<body>
    <h2>Select Your Role</h2>
    <form method="POST" action="set_role.php">
        <?php foreach ($roles as $role): ?>
            <input type="radio" name="role" value="<?php echo htmlspecialchars($role); ?>" required> <?php echo ucfirst($role); ?><br>
        <?php endforeach; ?>
        <button type="submit">Continue</button>
    </form>
</body>
</html>
