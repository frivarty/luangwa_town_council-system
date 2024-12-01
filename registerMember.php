



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
<form action="process.php" method="post">
    <label for="clubName">Club Name:</label>
    <input type="text" name="clubName" required>
    <button type="submit" name="submitClub"class="btn btn-success">Register Club</button>
</form>
<br>

<form action="process.php" method="post">
    <label for="memberId">Member ID:</label>
    <input type="text" name="memberId">
    <br><br>
    <label for="personName">Person Name:</label>
    <input type="text" name="personName" required>
    
    <label for="clubName">Select Club:</label>
    <select name="clubName" required>
        <?php
            
           // Include db_config.php for database connection
         include('db_config.php');

          // Retrieve club names from the database
          try {
           $stmt = $pdo->query("SELECT club_name FROM clubs");
           while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='{$row['club_name']}'>{$row['club_name']}</option>";
    }
        } catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
        }
        ?>
    </select>
    <br><br>
    <button type="submit" name="submitPerson" class="btn btn-success">Register Person</button>
</form>
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