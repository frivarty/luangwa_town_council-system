

<?php
include('db_config.php');

// Check if a recent logout success message exists
if (isset($_SESSION['logout_success_message']) && isset($_SESSION['logout_success_timestamp'])) {
    $timestamp = $_SESSION['logout_success_timestamp'];
    $currentTimestamp = time();
    
    // Display the toast message if it's within a reasonable duration (e.g., 5 minutes)
    if (($currentTimestamp - $timestamp) <300) {
        echo "<div class='toast'>
                {$_SESSION['logout_success_message']}
              </div>";

        // Clear the success message to prevent it from displaying on subsequent page loads
        unset($_SESSION['logout_success_message']);
        unset($_SESSION['logout_success_timestamp']);
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



    <br><br>
    <div  class="position-absolute top-45 start-50 translate-middle-x"><h2>Club Registration System</h2></div>

<div class="position-absolute top-50 start-50 translate-middle">

<!-- User Login Form -->
<form action="process.php" method="post">
    <label for="username">Username:</label>
    <br>
    <input type="text" name="username" required>

    <br><br>

    <label for="password">Password:</label>
    <br>
    <input type="password" name="password" required>

    <br><br>

    <button type="submit" name="login" class="btn btn-success">Login</button>
</form>

<p><a href="registration.php" class="link-info link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Register</a></p>

    </div>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
 
</body>
</html>