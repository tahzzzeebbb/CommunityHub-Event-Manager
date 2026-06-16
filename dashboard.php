<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Not logged in, redirect to login page
    header('Location: login.html');
    exit;
}

// Access user info from session
$username = htmlspecialchars($_SESSION['username']);
$role = $_SESSION['role'];
$community_id = $_SESSION['community_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard - Community Event Manager</title>
  <link rel="stylesheet" href="css/dashboard.css" />
</head>
<body>
  <div class="dashboard-container">
    <header>
      <h1>Welcome, <?php echo $username; ?>!</h1>
      <a href="modules/auth/logout.php" id="logout-btn">Logout</a>
    </header>

    <section>
      <p>Your role: <strong><?php echo $role; ?></strong></p>
      <p>Your community ID: <strong><?php echo $community_id; ?></strong></p>
      <!-- Future: List of events, quick links, etc -->
    </section>
  </div>
</body>
</html>
