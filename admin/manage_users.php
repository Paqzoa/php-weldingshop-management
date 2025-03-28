<?php
session_start();

# Check if user is already logged in, If yes then redirect him to index page
if (!isset($_SESSION["adminloggedin"]) && $_SESSION["adminloggedin"] == !TRUE) {
  echo "<script>" . "window.location.href='login.php'" . "</script>";
  exit;
}


                    

                        
include 'includes/header.php';
include 'configs/config.php';

$sql = "SELECT userid,username,email,verification FROM users ";
$result = mysqli_query($mysqli, $sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

if (isset($_POST['deleteCost'])) {
    $deleteCostId = $_POST['deleteCostId'];

    // Perform the deletion in the database
    $deleteSql = "DELETE FROM users WHERE userid = ?";
    $deleteStmt = mysqli_prepare($mysqli, $deleteSql);
    mysqli_stmt_bind_param($deleteStmt, "i", $deleteCostId);

    if (mysqli_stmt_execute($deleteStmt)) {
        header("Location: manage_users.php");
    mysqli_stmt_close($deleteStmt);
}}

elseif (isset($_POST['verify'])) {
    $verifyId = $_POST['verifyid'];
   

    $verifySql = "UPDATE users SET verification = 'Verified' WHERE userid = ?";
    $verifystmt = mysqli_prepare($mysqli, $verifySql);
    mysqli_stmt_bind_param($verifystmt, "i", $verifyId);

    if (mysqli_stmt_execute($verifystmt)) {
        header("Location: manage_users.php");
    mysqli_stmt_close($verifystmt);
}}
elseif (isset($_POST['block'])) {
    $blockId = $_POST['blockId'];
   

    $blockSql = "UPDATE users SET verification = 'Not Verified' WHERE userid = ?";
    $blockstmt = mysqli_prepare($mysqli, $blockSql);
    mysqli_stmt_bind_param($blockstmt, "i", $blockId);

    if (mysqli_stmt_execute($blockstmt)) {
        header("Location: manage_users.php");
    mysqli_stmt_close($blockstmt);
}}


else{

}

}


?>
<div class="wrapper">
<div class="manage_users">

    
   
    <?php if (mysqli_num_rows($result) > 0): ?>
      <h1 class="bg-info">Users Details </h1>
      <div class="manage_users_table">
        <table >
          
      
          <thead>
          
            <tr>
            <th>User Id</th>
              <th>Username</th>
              <th>Email</th>
            
              <th>Verified</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>{$row['userid']}</td>";
              echo "<td>{$row['username']}</td>";
              echo "<td>{$row['email']}</td>";
              if ($row['verification'] == 'Verified') {
                echo '<td><i class="fa duotone fa-check" style="font-size:14px;color:green"></i></td>';
              } else {
                echo '<td><i class="fa fa-times" style="font-size:14px;color:red"></i></td>';
              }
              echo "<td class='action-row'>
                        <form method='post'>
                            <input type='hidden' name='deleteCostId' value='{$row['userid']}'>
                            <button type='submit' name='deleteCost' class='btn btn-danger danger-btn'>Delete</button>
                        </form>";
              echo ($row['verification'] == 'Verified')?
              "<form method='post'>
                    <input type='hidden' name='blockId' value='{$row['userid']}'>
                    <button type='submit' name='block' class='btn btn-primary danger-btn'>Block</button>
                </form>"
              :"
              <form method='post'>
                        <input type='hidden' name='verifyid' value='{$row['userid']}'>
                        <button type='submit' name='verify' class='btn btn-success danger-btn'>Verify</button>
                    </form>";
                   

                        
                    
                    echo " </td>";
                      
                     
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
        </div>
    <?php else: ?>
      <h1 class='check-data'>No available data</h1>
    <?php endif; ?>



</div>
    </div>

<?php
include 'includes/footer.php';
?>