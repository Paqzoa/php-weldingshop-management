<?php
session_start();

require_once "../configs/config.php";

// Define error variables
$username_err = $password_err = $email_err = '';
$username = $password = $email = $confirm_password = $newpassword = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    // Username validation
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
        if (!preg_match("/^[a-zA-Z0-9_ -]+$/", $username)) {
            $username_err = "Username can only contain letters, numbers, underscores, hyphens, and spaces.";
        }
         else {
            # Prepare a select statement
            $sql = "SELECT userid FROM users WHERE username = ?";
    
            if ($stmt = mysqli_prepare($mysqli, $sql)) {
                # Bind variables to the statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_username);
    
                # Set parameters
                $param_username = $username;
    
                # Execute the prepared statement 
                if (mysqli_stmt_execute($stmt)) {
                    # Store result
                    mysqli_stmt_store_result($stmt);
    
                    # Check if username is already registered
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $username_err = "This username is already registered.";
                    }
                } else {
                    echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
                }
    
                # Close statement 
                mysqli_stmt_close($stmt);
            }
        }
    }

    // Email validation
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email address";
    } else {
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Please enter a valid email address.";
        } else {
            # Prepare a select statement
            $sql = "SELECT userid FROM users WHERE email = ?";

            if ($stmt = mysqli_prepare($mysqli, $sql)) {
                # Bind variables to the statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_email);

                # Set parameters
                $param_email = $email;

                # Execute the prepared statement 
                if (mysqli_stmt_execute($stmt)) {
                    # Store result
                    mysqli_stmt_store_result($stmt);

                    # Check if email is already registered
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $email_err = "This email is already registered.";
                    }
                } else {
                    echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
                }

                # Close statement
                mysqli_stmt_close($stmt);
            }
        }
    }

    // Validate password
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    function isStrongPassword($password) {
        // Define your password requirements
        $min_length = 8;
        $has_uppercase = preg_match('/[A-Z]/', $password);
        $has_lowercase = preg_match('/[a-z]/', $password);
        $has_digit = preg_match('/\d/', $password);
        $has_special_char = preg_match('/[^a-zA-Z\d]/', $password);
    
        // Check if the password meets all requirements
        if (strlen($password) < $min_length || !$has_uppercase || !$has_lowercase || !$has_digit || !$has_special_char) {
            return false;
        }
    
        return true;
    }
    
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
        if (strlen($password) < 8) {
            $password_err = "Password must contain at least 8 or more characters.";
           
        }
        else{
            if(!isStrongPassword($password)){
                $password_err = "Password must contain  lowercase, upercase,numbers and special characters";
            }
        }
        if ($password !== $confirm_password) {
            $password_err = "Passwords do not match.";
        } else {
            $newpassword = $confirm_password;
        }
    }

    // Perform validation before entering the database
    if (empty($username_err) && empty($email_err) && empty($password_err)) {
        $sql = "INSERT INTO users(username, email, password) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($mysqli, $sql)) {
            # Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);

            # Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($newpassword, PASSWORD_DEFAULT);

            # Execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $registration_success = true;
            } else {
                echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
            }

            # Close statement
            mysqli_stmt_close($stmt);
        }
    }

    if ($registration_success) {
        header("Location: ../login.php?show_popup=true");
        exit;
    } else {
        $_SESSION['password_err'] = $password_err;
        $_SESSION['username_err'] = $username_err;
        $_SESSION['email_err'] = $email_err;
        header("Location: ../register.php");
        exit;
    }

    mysqli_close($mysqli);
} else {
    header("Location: ../register.php");
    exit;
}
?>
