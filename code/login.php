<?php
session_start();
if (isset($_SESSION['user_id']))
{
header("Location: /");
}
include ('/var/connect.php');
$dbconnect = mysqli_connect($GLOBALS["mysql_hostname"], $GLOBALS["mysql_username"], $GLOBALS["mysql_password"], $GLOBALS["mysql_database"]);
if ($dbconnect->connect_error)
{
die("Database connection failed: " . $dbconnect->connect_error);
}
if (!empty($_POST['username']) && !empty($_POST['password']))
{
unset($Username, $Password, $ReturnedID);
$Username = $_POST['username'];
$Password = $_POST['password'];
$records = $dbconnect->prepare('SELECT us_rowid, us_username, us_password FROM users WHERE us_username = ?');
$records->bind_param('s', $Username);
$records->execute();
$records->store_result();
$records->bind_result($ID, $Username, $Password);
while ($records->fetch())
{
$ReturnedID = $ID;
$ReturnedUsername = $Username;
$ReturnedPassword = $Password;
}
$records->close();
$message = '';
if (!empty($ReturnedID) && password_verify($_POST['password'], $ReturnedPassword))
{
$_SESSION['user_id'] = $ReturnedID;
$_SESSION['user_name'] = $ReturnedUsername;
header("Location: /");
}
else
{
$message = 'Sorry, those credentials do not match';
}
}
?>
<!DOCTYPE html>
<html>
  <body>
    <head>
      <meta charset="UTF-8">
      <title>AD Demo Page
      </title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    </head>
    <?php if (!empty($message)): ?>
    <p>
      <?=$message
?>
    </p>
    <?php
endif; ?>
    <div class="container">
      <h1>Login
      </h1>
      <span>or 
        <a href="register.php">register here
        </a>
      </span>
      <form action="login.php" method="POST">
        <div class="form-group">
          <input class="form-control" type="text" placeholder="Enter your username" name="username">
          <input class="form-control" type="password" placeholder="and password" name="password">
          <input class="form-control" type="submit">
        </div>
      </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
    </script>
  </body>
</html>