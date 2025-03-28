<?php include 'includes/header.php';
session_start();
?>
<div class="index-wrapper">
<div class="index-sections">
<div class="section1">

    <div class="welcoming">
    
        <h2>Hello! <br>Welcome to</h2>
        <h1>ARTCO</h1>
        <h4>Metals</h4>
        <p>Discover Wide range of tools and metals to make your life better</p>
        <div class="links">
            <div class="learn-more">
            <a href="about.php" class="about bg-info">Learn More&rarr;</a>
            </div>
        
        <?php
        if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == !TRUE) {
            echo '<div class="loginurl">
                    <a class="nav-link bg-primary" href="login.php">Login&rarr;</a>
                </div>';
        }
        ?>
        

        </div>

       
    </div>
    
    
</div>

<div class="section2">
<div class="image-container">
     <img src="images/work5.png" alt="SVG Image" class="svg-image">
   
    </div>
</div>
       
      
   
</div>

</div>

   


<?php include 'includes/footer.php'; ?>
