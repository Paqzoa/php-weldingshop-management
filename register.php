<?php 
session_start();

# Check if user is already logged in, If yes then redirect him to index page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == TRUE) {
  echo "<script>" . "window.location.href='dashboard.php'" . "</script>";
  exit;
}



include 'includes/header.php'; 

?>
<?php global $password_err; ?> <!-- Include the global variable -->
<div class="wrapper">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header form-header">
                    Register
                </div>
                <div class="card-body">
                    <?php
                   if (isset($_SESSION['error'])) {
                    echo '<p class="text-danger">' . $_SESSION['error'] . '</p>';
                    unset($_SESSION['error']); // Clear the error message
                }
                    ?>
                    <form action="processes/register_process.php" method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                            <?php
                              if (isset($_SESSION['username_err'])) {
                                echo '<p class="text-danger">' . $_SESSION['username_err'] . '</p>';
                                unset($_SESSION['username_err']); // Clear the error message
                            }
   
    ?>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <?php
                             if (isset($_SESSION['email_err'])) {
                                echo '<p class="text-danger">' . $_SESSION['email_err'] . '</p>';
                                unset($_SESSION['email_err']); // Clear the error message
                            }
   
    ?>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-container">
        <input type="password" class="form-control toggle-password-input" id="password" name="password" required>
        <span class="toggle-password" onclick="togglePasswordVisibility()">
            <i class="fa fa-eye-slash toggle-password-icon" aria-hidden="true"></i>
        </span>
    </div>
                            <?php
                             if (isset($_SESSION['password_err'])) {
                                echo '<p class="text-danger">' . $_SESSION['password_err'] . '</p>';
                                unset($_SESSION['password_err']); // Clear the error message
                            }
   
    ?>
                        </div>
                        <div class="form-group">
                            <label for="confirm-password">Confirm Password</label>
                            <input type="password" class="form-control toggle-password-input" id="confirm-password" name="confirm-password" required>
                            <?php
                             if (isset($_SESSION['password_err'])) {
                                echo '<p class="text-danger">' . $_SESSION['password_err'] . '</p>';
                                unset($_SESSION['password_err']); // Clear the error message
                            }
  
    ?>
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- <script>
    function togglePasswordVisibility(inputId) {
        var passwordInput = document.getElementById(inputId);
        var icon = passwordInput.parentElement.querySelector('.fa');

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            passwordInput.type = "password";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }
</script> -->

<?php include 'includes/footer.php'; ?>