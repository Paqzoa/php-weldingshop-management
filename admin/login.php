<?php 


session_start();


# Check if user is already logged in, If yes then redirect him to index page
if (isset($_SESSION["adminloggedin"]) && $_SESSION["adminloggedin"] == TRUE) {
   
  echo "<script>" . "window.location.href='dashboard.php'" . "</script>";
  exit;
}


include 'includes/header.php';

if (isset($_GET['show_popup']) && $_GET['show_popup'] === 'true') {
    $popupContent = "Registration was successful ";
    echo "<script>openPopup('$popupContent');</script>";
  }

  if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    $popupContent = "Logout was successful ";
    echo "<script>openPopup('$popupContent');</script>";
  }
?>
<div class="wrapper">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header form-header" >
                   Admin Login
                </div>
                <div class="card-body">
          <?php

if (isset($_SESSION['login_err'])) {
    echo '<small class="text-danger">' . $_SESSION['login_err']. '</small>';
    unset($_SESSION['login_err']); 
}
          ?>
                    <form action="processes/login_process.php" method="post">
                        <div class="form-group">
                            <label for="username">Username/Email</label>
                            <input type="text" class="form-control" id="username" name="username" required>
     <?php
     if (isset($_SESSION['username_err'])) {
        echo '<small class="text-danger">'. $_SESSION['username_err'] . '</small>';
        unset($_SESSION['username_err']); // Clear the error message from the session
    }
     ?>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
   <?php
   if (isset($_SESSION['password_err'])) {
    echo '<small class="text-danger">'. $_SESSION['password_err'] . '</small>';
    unset($_SESSION['password_err']); // Clear the error message from the session
}
   ?>
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                    
                </div>
                <div class="reg-question">
                <p>Have no account? <a class="nav-link" href="admin_onboard.php">Register</a></p>
                </div>
                
            </div>
        </div>
    </div>
</div>

</div>

<?php include 'includes/footer.php'; ?>