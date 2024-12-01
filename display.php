<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Persons and Clubs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  
    <style>
        body {
            text-align: center;
        }

        h2 {
            margin-top: 50px;
        }

        table {
            width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }
    </style>
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
          <a class="nav-link" href="#">Register Club</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Register Member</a>
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
<h2>Display Persons and Clubs</h2>



<form action="display.php" method="post">
    <label for="searchTerm">Search:</label>
    <input type="text" name="searchTerm" required>
    <button type="submit" name="search">Search</button>
</form>


<?php

session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); // Redirect to login page or handle unauthorized access
    exit();
}

// ... (existing code remains unchanged)


if (isset($_POST['search'])) {
    // Handle search and display results
    $searchTerm = $_POST['searchTerm'];

    // Database credentials
    include('db_config.php');


    try {
       

        $stmt = $pdo->prepare("SELECT members.member_id, members.person_name, clubs.club_name 
                               FROM members 
                               JOIN clubs ON members.club_id = clubs.club_id 
                               WHERE members.person_name LIKE :searchTerm 
                                  OR members.member_id LIKE :searchTerm 
                                  OR clubs.club_name LIKE :searchTerm");
        $stmt->execute(['searchTerm' => "%{$searchTerm}%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            echo "<table>
                    <tr>
                        <th>Member ID</th>
                        <th>Person Name</th>
                        <th>Club Name</th>
                    </tr>";
            foreach ($results as $result) {
                echo "<tr>
                        <td>{$result['member_id']}</td>
                        <td>{$result['person_name']}</td>
                        <td>{$result['club_name']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "No matching records found.";
        }
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
?>

<br>
<!-- Back button using JavaScript -->
<button onclick="goBack()">Back</button>

<script>
    function goBack() {
        window.history.back();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
 
<!-- Include this script in your HTML, preferably at the end of the body -->
<script src="inactivity.js"></script>

</body>
</html>
