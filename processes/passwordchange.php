<?php
session_start();

require_once "../configs/config.php";

// Initialize error messages in session variables
$_SESSION['current_password_err'] = $_SESSION['new_password_err'] = $_SESSION['confirm_new_password_err'] = '';

$current_password = $new_password = $confirm_new_password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    // Validate the current password
    if (empty(trim($_POST["current_password"]))) {
        $_SESSION['current_password_err'] = "Please enter your current password.";
    } else {
        $sql = "SELECT password FROM users WHERE userid = ?";

        if ($stmt = mysqli_prepare($mysqli, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $_SESSION["id"];
            
            // Execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $hashed_password);

                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($_POST["current_password"], $hashed_password)) {
                            // Current password is correct
                            $current_password = trim($_POST["current_password"]);
                        } else {
                            $_SESSION['current_password_err'] = "Incorrect current password.";
                        }
                    }
                }
            } else {
                echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
            }
            
            mysqli_stmt_close($stmt);
        }
    }

    // New password validation
    if (empty(trim($_POST["new_password"]))) {
        $_SESSION['new_password_err'] = "Please enter a new password.";
    } elseif (strlen(trim($_POST["new_password"])) < 8) {
        $_SESSION['new_password_err'] = "Password must contain at least 8 characters.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    // Confirm new password validation
    if (empty(trim($_POST["confirm_new_password"]))) {
        $_SESSION['confirm_new_password_err'] = "Please confirm the new password.";
    } else {
        $confirm_new_password = trim($_POST["confirm_new_password"]);
        if ($new_password !== $confirm_new_password) {
            $_SESSION['confirm_new_password_err'] = "Passwords do not match.";
        }
    }

    // If there are no errors, update the password in the database
    if (empty($_SESSION['current_password_err']) && empty($_SESSION['new_password_err']) && empty($_SESSION['confirm_new_password_err'])) {
        $sql = "UPDATE users SET password = ? WHERE userid = ?";

        if ($stmt = mysqli_prepare($mysqli, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];

            if (mysqli_stmt_execute($stmt)) {
                header("Location: ../profile.php?show_popup=true");
                exit;
            } else {
                echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($mysqli);
}

// Redirect to the profile page
header("Location: ../profile.php");
exit;
?>
