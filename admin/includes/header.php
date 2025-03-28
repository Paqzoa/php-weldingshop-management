<?php
session_start();
$username = isset($_SESSION['adminusername']) ? $_SESSION['adminusername'] : '';


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <title>ARTCO METALS</title>
    <script src="./js/pop.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/ArtcoApp/admin">ARTCO METALS</a>
        
        <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button> -->
        <button class="navbar-toggler" type="button" id="menu-toggle">
           
    <span class="navbar-toggler-icon"></span>
</button>
<?php
            if (isset($_SESSION["adminloggedin"]) && $_SESSION["adminloggedin"] == TRUE) {
            echo '<p class="username" style="color:white;">Welcome ' . $username . '<i class="fa fa-circle" style="color:green;"></i></p>';}
            ?>

       
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/ArtcoApp/admin">Home<i class="fa fa-home" style="font-size:15px;color:white"></i></a>
                </li>
                
                    <?php

                if (!isset($_SESSION["adminloggedin"]) && $_SESSION["adminloggedin"] == !TRUE) {
                    echo'
                    <li class="nav-item">

                    <a class="nav-link" href="login.php">Login<i class="fa fa-sign-in" style="font-size:15px;color:white" aria-hidden="true"></i></a>
                    </li>';
                }
                else{

                   
                    echo'
                    <li class="nav-item">

                    <a class="nav-link" href="dashboard.php">Dashboard<i class="fa fa-dashboard" style="font-size:15px;color:white"></i></a>
                    
                    </li>';
                    echo'
                    <li class="nav-item">

                    <a class="nav-link" href="order-page.php">Add order<i class="fa fa-first-order" style="font-size:15px;color:white" aria-hidden="true"></i></a>
                    
                    </li>';
                    echo'
                    <li class="nav-item">

                    <a class="nav-link" href="manage_users.php">Manage users<i class="fa fa-dashboard" style="font-size:15px;color:white"></i></a>
                    
                    </li>';

                    echo'
                    <li class="nav-item">

                    <a class="nav-link" href="logout.php">Logout<i class="fa fa-sign-out" style="font-size:15px;color:white" aria-hidden="true"></i></a>
                    
                    </li>';
                    echo '<li class="nav-item">
    <a class="nav-link" href="profile.php">
        <div class="circle-icon">
            <i class="fa fa-user" style="font-size:25px;color:black" aria-hidden="true"></i>
        </div>
        
    </a>
</li>';

                }
               
?>
                    
               
                <!-- <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li> -->
            </ul>
        </div>
    </nav>
    <div id="popup" class="popup">
        <div class="popup-content">
        <span class="close-button" onclick="closePopup()">&times;</span>

            <h6 id="popup-text"></h6>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const menuToggle = document.getElementById("menu-toggle");
        const navbarNav = document.getElementById("navbarNav");

        menuToggle.addEventListener("click", function () {
            navbarNav.classList.toggle("show");
        });
    });
</script>

    
   