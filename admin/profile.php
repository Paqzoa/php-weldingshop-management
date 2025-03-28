<?php
session_start();

# Check if user is already logged in, If yes then redirect him to index page
if (!isset($_SESSION["adminloggedin"]) && $_SESSION["adminloggedin"] == !TRUE) {
  echo "<script>" . "window.location.href='login.php'" . "</script>";
  exit;
}

include 'includes/header.php';
include 'configs/config.php';
if (isset($_GET['show_popup']) && $_GET['show_popup'] === 'true') {
    $popupContent = "Password changed successfully!";
    echo "<script>openPopup('$popupContent');</script>";
  }

  if (isset($_SESSION['current_password_err'])) {
    $password_change_error = $_SESSION['password_change_error'];

    unset($_SESSION['password_change_error']);
}
?>
<div class="profile">

<div class="container mt-5">
        <div class="row">
            <!-- User Information Section -->
            <div class="col-md-6 cards-profile">
                <div class="card">
                    <div class="card-header profile-headers">
                        User Information
                    </div>
                    <div class="card-body">
    <!-- Display user information here -->
    <p><strong>Username:</strong> <?php echo $_SESSION['adminusername']; ?></p>
    <p><strong>Email:</strong> <?php echo $_SESSION['adminemail']; ?></p>
    <!-- Add other session variables for additional details if needed -->
</div>

                </div>
            </div>

            <!-- Change Password Section -->
            <div class="col-md-6 cards-profile">
                <div class="card">
                    <div class="card-header profile-headers">
                        Change Password
                    </div>
                    <div class="card-body">
                    <?php

if (isset($_SESSION['current_password_err'])) {
    echo '<small class="text-danger">' . $_SESSION['current_password_err']. '</small>';
    unset($_SESSION['current_password_err']); 
}
          ?>
                        <form id="change-password-form" action="processes/passwordchange.php" method="post">
                            <div class="form-group">
                                <label for="current-password">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                          
                            <div class="form-group">
                            
                            
                                <label for="new-password">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                               
                               
                            </div>
                            <?php

if (isset($_SESSION['new_password_err'])) {
    echo '<small class="text-danger">' . $_SESSION['new_password_err']. '</small>';
    unset($_SESSION['new_password_err']); 
}
          ?>
                            <div class="form-group">
                            
                                <label for="confirm-new-password">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
                            </div>
                            <?php

if (isset($_SESSION['confirm_new_password_err'])) {
    echo '<small class="text-danger">' . $_SESSION['confirm_new_password_err']. '</small>';
    unset($_SESSION['confirm_new_password_err']); 
}
          ?>
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>