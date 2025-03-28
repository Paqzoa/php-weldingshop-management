<?php
session_start();

$user_login_err = $user_password_err = $login_err = "";

include "../configs/config.php";

$user_login = $user_password = "";

// Define the maximum number of login attempts and the lockout time in seconds
$max_attempts = 3; // Adjust this value as needed
$lockout_time = 300; // 300 seconds = 5 minutes

// Define a session variable to store the timestamp of the last login attempt
if (!isset($_SESSION['last_login_attempt'])) {
    $_SESSION['last_login_attempt'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user has reached the maximum login attempts within the lockout time
    if ($_SESSION['login_attempts'] < $max_attempts || (time() - $_SESSION['last_login_attempt']) > $lockout_time) {
        if (empty(trim($_POST["username"]))) {
            $user_login_err = "Please enter your username or an email id.";
        } else {
            $user_login = trim($_POST["username"]);
        }

        if (empty(trim($_POST["password"]))) {
            $user_password_err = "Please enter your password.";
        } else {
            $user_password = trim($_POST["password"]);
        }

        if (empty($user_login_err) && empty($user_password_err)) {
            $sql = "SELECT userid, username, email, password, verification FROM users WHERE username = ? OR email = ?";
            
            if ($stmt = mysqli_prepare($mysqli, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $param_user_login, $param_user_login);
                $param_user_login = $user_login;

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        mysqli_stmt_bind_result($stmt, $id, $username, $email, $hashed_password, $verification);

                        if (mysqli_stmt_fetch($stmt)) {
                            if (password_verify($user_password, $hashed_password)) {
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;
                                $_SESSION["email"] = $email;
                                $_SESSION["loggedin"] = TRUE;
                                $_SESSION["verification"] = $verification;
                                $login_success = true;
                            } else {
                                $login_err = "Invalid username or password.";
                            }
                        }
                    } else {
                        $login_err = "Invalid username or password.";
                    }
                } else {
                    echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
                    header("Location: ../login.php");
                    exit;
                }

                mysqli_stmt_close($stmt);
            }
        }

        // Increment login attempts if login failed
        if ($login_success) {
            // Reset login attempts upon successful login
            $_SESSION['login_attempts'] = 0;
            $_SESSION['last_login_attempt'] = 0;

            header("Location: ../dashboard.php?show_popup=true");
            exit;
        } else {
            $_SESSION['login_err'] = $login_err;
            
            // Increment login attempts upon failed login
            $_SESSION['login_attempts']++;
            $_SESSION['last_login_attempt'] = time();
            
            header("Location: ../login.php");
            exit;
        }
    } else {
        // Handle the case where the user has reached the maximum login attempts within the lockout time
        $_SESSION['login_err'] = "Maximum login attempts exceeded. Please try again later.";
        header("Location: ../login.php");
        exit;
    }
}

mysqli_close($mysqli);
?>
