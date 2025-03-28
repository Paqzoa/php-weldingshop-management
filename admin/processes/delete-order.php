<?php
session_start();

# Check if user is already logged in, If yes then redirect him to index page
if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == !TRUE) {
  echo "<script>" . "window.location.href='../login.php'" . "</script>";
  exit;
}

include '../configs/config.php';
$order_id = $_GET['id'];

// Begin a database transaction
mysqli_begin_transaction($mysqli);

// Attempt to delete order details from the cost table
$deleteCostSql = "DELETE FROM cost WHERE order_id = ?";
$deleteCostStmt = mysqli_prepare($mysqli, $deleteCostSql);
mysqli_stmt_bind_param($deleteCostStmt, "i", $order_id);

// Attempt to delete the order from the orders table
$deleteOrderSql = "DELETE FROM orders WHERE order_id = ?";
$deleteOrderStmt = mysqli_prepare($mysqli, $deleteOrderSql);
mysqli_stmt_bind_param($deleteOrderStmt, "i", $order_id);

// Execute the deletion operations and commit the transaction
if (mysqli_stmt_execute($deleteCostStmt) && mysqli_stmt_execute($deleteOrderStmt)) {
    // Commit the transaction
    mysqli_commit($mysqli);
    
    // Redirect back to the list of undelivered orders
    
    header("Location: ../dashboard.php?order_deleted=true");
    
} else {
    // If any deletion operation fails, roll back the transaction
    mysqli_rollback($mysqli);
    
    // Display an error message
    echo "<script>" . "alert('Error deleting order and associated details');" . "</script>";
    echo "<script>" . "window.location.href='../dashboard.php'" . "</script>";
}

// Close the statements
mysqli_stmt_close($deleteCostStmt);
mysqli_stmt_close($deleteOrderStmt);
?>
