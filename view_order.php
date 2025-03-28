<?php
session_start();

# Check if user is already logged in, If yes then redirect him to index page
if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == !TRUE) {
  echo "<script>" . "window.location.href='login.php'" . "</script>";
  exit;
}
else{
    if($_SESSION["verification"]== "Not Verified"){
      echo "<script>" . "window.location.href='admin_permissions.php'" . "</script>";
      exit;
    }
  
  }

include 'includes/header.php';
include 'configs/config.php';
if (isset($_GET['show_popup']) && $_GET['show_popup'] === 'true') {
    $popupContent = "Cost added succesfully";
    echo "<script>openPopup('$popupContent');</script>";
  }

$order_id = $_GET['id'];
$sql = "SELECT * FROM orders WHERE order_id = ?";
$stmt = mysqli_prepare($mysqli, $sql);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

// Check if the order exists in the database
if (mysqli_num_rows($result) === 0) {
    echo '<h1 class="check-data" >Order does not exist.</h1>';
} else {
    $order = mysqli_fetch_assoc($result);
?>
<div class="view-order-wrapper">
    <div class="view-section1">
    <div class="order-details">
        <div class="inner-details">
<h1 class="bg-info">Order Details </h1>
<p class="text-naming">Customer Name: <span class="text-highlight"><?php echo $order['customer_name']; ?></span></p>
<p class="text-naming">Order Type:  <span class="text-highlight"><?php echo $order['order_type']; ?></span></p>
<p class="text-naming">Requested Delivery Date: <span class="text-highlight"><?php echo $order['requested_delivery_date']; ?></span></p>
<p class="text-naming">Order date(Placed): <span class="text-highlight"><?php echo $order['order_request_date']; ?></span></p>
<p class="text-naming">Delivered: <?php 
if ($order['delivery_status'] == 'Delivered') {
   echo '<i class="fa duotone fa-check" style="font-size:20px;color:green"></i>';
}
elseif($order['delivery_status'] == 'Not Delivered'){
    echo '<i class="fa fa-times" style="font-size:20px;color:red"></i>';
}
?></p>
<p class="text-naming">Order Description: <span class="text-highlight"><?php echo $order['order_description']; ?></span></p>
</div>
</div>

  
<div class="cost-page">

<?php

// Fetch and display the cost details for the specific order
// Fetch and display the cost details for the specific order
$sql = "SELECT * FROM cost WHERE order_id = ?";
$stmt = mysqli_prepare($mysqli, $sql);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);

$costsResult = mysqli_stmt_get_result($stmt);

// Retrieve the delivery status from the orders table
$sql = "SELECT delivery_status FROM orders WHERE order_id = ?";
$stmt = mysqli_prepare($mysqli, $sql);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$statusResult = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($statusResult) > 0) {
    $orderStatus = mysqli_fetch_assoc($statusResult);

    if (mysqli_num_rows($costsResult) > 0) {
        echo'<h2 class="cost_header bg-info">Cost Details </h2>';
        echo "<div class='cost-table'>"; // Open the 'cost-table' div here
        echo "<table class='table'>";
        echo "<thead><tr><th>Cost Description</th><th>Cost Amount</th>";

      

        echo "</tr></thead>";
        echo "<tbody>";

        $totalCost = 0; // Initialize total cost

        while ($row = mysqli_fetch_assoc($costsResult)) {
            echo "<tr>";
            echo "<td>{$row['cost_description']}</td>";
            echo "<td>Ksh{$row['cost_amount']}</td>";

            // Check if the delivery status is not "Delivered"
           

            echo "</tr>";

            // Add the cost amount to the total cost
            $totalCost += $row['cost_amount'];
        }

        echo "</tbody>";
        echo "</table>";

        // Display the total cost
        echo "<p class='total-lable'>Total Cost: <span class='text-highlight'>Ksh$totalCost</span></p>";

        echo "</div>"; // Close the 'cost-table' div here

        // Update the cost_incurred field in the orders table
        $updateCostSql = "UPDATE orders SET cost_incurred = ? WHERE order_id = ?";
        $updateCostStmt = mysqli_prepare($mysqli, $updateCostSql);
        mysqli_stmt_bind_param($updateCostStmt, "di", $totalCost, $order_id);

        if (! mysqli_stmt_execute($updateCostStmt)) {
            echo "Error updating cost incurred: " . mysqli_error($mysqli);
        } 
       

        mysqli_stmt_close($updateCostStmt);
    } else {
        echo " <h1 class='check-data'>No available data</h1>";
    }
} else {
    echo "<p>No delivery status found for this order.</p>";
}

mysqli_stmt_close($stmt);


?>
</div>
</div>

<div class="view-section2">
    
<div class="return-information">
    <div class="infoheader bg-success"><h2>Returns Information</h2></div>
    
        <div class="otherinfo">

        
            <div class="product-returns">
        
            <h4 class="returns-title bg-info">Product Returns</h4>
            <?php
// Retrieve profit, profit percentage, final fee, and cost from the database
$sql = "SELECT profit, percentage_profit, final_fee, cost_incurred FROM orders WHERE order_id = ?";
$stmt = mysqli_prepare($mysqli, $sql);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $profit = $row['profit'];
    $profitPercentage = $row['percentage_profit'];
    $finalFee = $row['final_fee'];
    $costIncurred = $row['cost_incurred'];

    echo "<p class='text-naming'>Profit Amount: <span class='text-highlight'>Ksh $profit</span></p>";
    echo "<p class='text-naming'>Profit Percentage: <span class='text-highlight'>$profitPercentage%</span></p>";
    echo "<p class='text-naming'>Final Fee: <span class='text-highlight'>Ksh $finalFee</span></p>";
    echo "<p class='text-naming'>Cost Incurred: <span class='text-highlight'>Ksh $costIncurred</span></p>";
} else {
    echo "Error fetching profit and profit percentage from the database.";
}
mysqli_stmt_close($stmt);
?>
    
            </div>



 


            <div class="month-returns">
            <h4 class="returns-title bg-info">Monthly Returns</h4>
            <?php 
    date_default_timezone_set('Africa/Nairobi');
$currentYear = date('Y');
$currentMonth = date('m');

// Calculate the start and end dates of the current month
$start_date = $currentYear . "-" . $currentMonth . "-01";
$end_date = date('Y-m-d', strtotime($start_date . ' + 1 month'));

// Retrieve monthly information from the database
$sql = "SELECT
SUM(profit) AS total_profit,
ROUND((SUM(final_fee) - SUM(cost_incurred)) / SUM(cost_incurred) * 100, 2) AS percentage_profit,
SUM(final_fee) AS total_final_fee,
SUM(cost_incurred) AS total_cost_incurred
FROM
orders
WHERE
delivery_status = 'Delivered'
AND DATE_FORMAT(delivery_date, '%Y-%m-%d') >= ?
AND DATE_FORMAT(delivery_date, '%Y-%m-%d') < ?
";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();

if ($stmt->errno) {
    echo "Query execution error: " . $stmt->error;
} else {
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $totalProfit = $row['total_profit'];
        $averageProfitPercentage = $row['percentage_profit'];
        $totalFinalFee = $row['total_final_fee'];
        $totalCostIncurred = $row['total_cost_incurred'];

        echo "<div class='month-returns'>";
        echo "<p class='text-naming'>Profit Amount - $currentYear-$currentMonth: <span class='text-highlight'>Ksh $totalProfit</span></p>";
        echo "<p class='text-naming'>Average Profit Percentage - $currentYear-$currentMonth: <span class='text-highlight'>$averageProfitPercentage%</span></p>";
        echo "<p class='text-naming'>Total Sales-$currentYear-$currentMonth: <span class='text-highlight'>Ksh $totalFinalFee</span></p>";
        echo "<p class='text-naming'>Cost Incurred - $currentYear-$currentMonth: <span class='text-highlight'>Ksh $totalCostIncurred</span></p>";
        echo "</div>";
    } else {
        echo "No data available for the current month.";
    }
    
    $stmt->close();
}

$mysqli->close();
?>
    

            </div>
        </div>
</div>

</div>
</div>
<?php
}

include 'includes/footer.php';
?>












