<?php
session_start();
require("dbconnect.php");

// Connect to server and select databse.
$connection=pg_connect ("dbname=$database user=$user password=$password host=$host");
if (!$connection) {
  die("Not connected : " . pg_error());
}

// username and password sent from form
$username=$_POST['username'];
$password=$_POST['password'];
$hashpass = md5($mypassword);

// To protect against SQL injection
$username = stripslashes($username);
$password = stripslashes($password);

$query = "SELECT * FROM public.\"Registered_Users\" WHERE username = '$username' AND password = '$hashpass'";
$result = pg_exec($connection, $query);

// Count matching rows returned
$count = pg_numrows($result);

// If result matched $username and $password, table row must be 1 row
if($count==1){
// Register $username session and redirect to file "index.php"
$_SESSION["username"] = $username;
header("location:index.php");
}
else {
header("location:login_fail.php");
}
?>