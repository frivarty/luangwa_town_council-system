<!DOCTYPE html>
<html lang="en">
    <head>
        <title>

        </title>
    </head>
    <body>
<H2> Register As User</H2>
<!-- User Registration Form -->
<form action="process.php" method="post">
      <label for="name">name:</label>
    <input type="text" name="name" required>
     <br><br>
    <label for="nrc">NRC:</label>
    <input type="text" name="nrc" required>

    <br><br>

    <label for="username">Username:</label>
    <input type="text" name="username" required>

    <br><br>
    <label for="password">Password:</label>
    <input type="password" name="password" required>
    <br><br>
    <label for="isAdmin">Administrator?</label>
    <input type="checkbox" name="isAdmin">
    <br><br>
    <button type="submit" name="registerUser" class="btn btn-success">Register User</button>
</form>


<!-- Include this script in your HTML, preferably at the end of the body -->
<script src="inactivity.js"></script>

    </body>
</html>