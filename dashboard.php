
<?php
include('db_config.php');

// Check if the user is authenticated

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login page if not authenticated
    exit();
}

// Check if a recent login success message exists
if (isset($_SESSION['login_success_message']) && isset($_SESSION['login_success_timestamp'])) {
    $timestamp = $_SESSION['login_success_timestamp'];
    $currentTimestamp = time();
    
    // Display the message if it's within a reasonable duration (e.g., 5 minutes)
    if (($currentTimestamp - $timestamp) < 300) {
        echo "<p>{$_SESSION['login_success_message']}</p>";

        // Clear the success message to prevent it from displaying on subsequent page loads
        unset($_SESSION['login_success_message']);
        unset($_SESSION['login_success_timestamp']);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Registration System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  
</head>
<body>

 


<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">C.R.S</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="dashboard.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="registerClub.php">Register Club</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="registerMember.php">Register Member</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="display.php">Search Member</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Print Certificate</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid">
    <br>
<h2 style="text-align: center;">Club Registration System</h2>

<br><br>
<table class="table table-light table-hover">
    <thead>
        <tr>
            <th>Club Name</th>
            <th>Total Members</th>
            <th>Status</th>
            <th>Date Entered</th>
        </tr>
    </thead>
    <tbody>
    <?php
// Assuming $pdo is your database connection, and you have fetched $clubs from your database
// Replace this with your actual code to retrieve $clubs

// Example:
// $stmt = $pdo->query("SELECT * FROM clubs");
// $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if $clubs is set and not empty
if (isset($clubs) && is_array($clubs) && !empty($clubs)) {
    foreach ($clubs as $club) {
        // Fetch additional information for each club
        $clubId = $club['club_id'];
        $memberCountStmt = $pdo->prepare("SELECT COUNT(*) AS member_count FROM members WHERE club_id = ?");
        $memberCountStmt->execute([$clubId]);
        $memberCount = $memberCountStmt->fetchColumn();

        $status = ($memberCount > 0) ? 'Active' : 'Not Active';
        $dateEnteredStmt = $pdo->prepare("SELECT MIN(date_entered) AS date_entered FROM members WHERE club_id = ?");
        $dateEnteredStmt->execute([$clubId]);
        $dateEntered = $dateEnteredStmt->fetchColumn();
        ?>
        <tr>
            <td><?php echo $club['club_name']; ?></td>
            <td><?php echo $memberCount; ?></td>
            <td><?php echo $status; ?></td>
            <td><?php echo ($dateEntered) ? date('Y-m-d', strtotime($dateEntered)) : ''; ?></td>
        </tr>
    <?php
    }
} else {
    echo "No clubs found or an error occurred.";
}
?>
    </tbody>
</table>

<br><br>
<!-- Logout Form -->
<form action="process.php" method="post">
    <button type="submit" name="logout" class="btn btn-success">Logout</button>
</form>

</div>
<!-- Include this script in your HTML, preferably at the end of the body -->
<script src="inactivity.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
 
</body>
</html>
