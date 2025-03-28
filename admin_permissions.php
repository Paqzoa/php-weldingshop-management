<?php
session_start();

# Check if user is already logged in, If yes then redirect him to index page
if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == !TRUE) {
  echo "<script>" . "window.location.href='login.php'" . "</script>";
  exit;
}




include 'includes/header.php';
include 'configs/config.php';
?>
<div class="wrapper">
<div class="permissions">
<h1>Admin permissions to Use this app Required!</h1>


</div>
</div>
<?php


include 'includes/footer.php';
?>