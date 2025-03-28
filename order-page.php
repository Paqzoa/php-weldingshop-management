<?php
session_start();

# Check if the user is already logged in. If not, redirect to the login page.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  echo "<script>window.location.href='login.php'</script>";
  exit;
}
else{
    if($_SESSION["verification"]== "Not Verified"){
      echo "<script>" . "window.location.href='admin_permissions.php'" . "</script>";
      exit;
    }
  
  }
include 'includes/header.php';
if (isset($_GET['show_popup']) && $_GET['show_popup'] === 'true') {
    $popupContent = "Order Added Successfully";
    echo "<script>openPopup('$popupContent');</script>";
  }
?>

<div class="wrapper">
<div class="container"><div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5 order-form">
                <div class="card-header form-header" >
                <h1>Fill Order</h1>
                </div>
                <div class="card-body ">
<?php
    if (isset($_GET['errormessage_err'])) {
        echo '<small class="text-danger">' . htmlspecialchars($_GET['errormessage_err']) . '</small>';
    }
    ?>
    <!-- <div class="add-order-header"> -->
    
   
    
    <form action="processes/order-process.php" method="post">
    
        <div class="mb-3">
            <label for="customerName" class="form-label label1">Customer Name</label>
            <input type="text" class="form-control" id="customerName" name="customerName" required>
        </div>
        <div class="mb-3">
            <label for="customerPhone" class="form-label">Customer Phone</label>
            <input type="tel" class="form-control" id="customerPhone" name="customerPhone" required>
        </div>
        <div class="mb-3">
            <label for="orderType" class="form-label">Order Type</label>
            <select class="form-select" id="orderType" name="orderType" required>
                <option value="Complete">Complete</option>
                <option value="Accessories">Accessories</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="requestedDeliveryDate" class="form-label">Requested Delivery Date</label>
            <input type="date" class="form-control" id="requestedDeliveryDate" name="requestedDeliveryDate" required>
        </div>
        <!-- <div class="mb-3">
            <label for="orderCost" class="form-label">Estimated Order Cost</label>
            <input type="number" step="0.01" class="form-control" id="orderCost" name="orderCost" required>
        </div> -->
        <div class="mb-3">
            <label for="orderDescription" class="form-label">Order Description</label>
            <textarea class="form-control" id="orderDescription" name="orderDescription" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Order</button>
    </form>
</div>
</div>
</div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
