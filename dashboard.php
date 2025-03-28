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
  $popupContent = "Login was successful";
  echo "<script>openPopup('$popupContent');</script>";
}
if (isset($_GET['order_deleted']) && $_GET['order_deleted'] === 'true') {
  $popupContent = "Order and associated details deleted successfully!";
  echo "<script>openPopup('$popupContent');</script>";
}

// Fetch undelivered orders from the database
$sql = "SELECT * FROM orders ORDER BY order_request_date DESC";
$result = mysqli_query($mysqli, $sql);
?>


<div class="wrapper">
  <div class="dashboard-wrapper">
    <div class="customer-order-section">
      
       
    <div class="table-container">
    
   
      <?php if (mysqli_num_rows($result) > 0): ?>
        <h1 class="bg-info">Customer Orders</h1>
       <div class="dashboard-table">
          <table >
            
        
            <thead>
            
              <tr>
                <th>Customer Name</th>
                <th>Sales Price (Ksh)</th>
                <th>Requested Delivery Date</th>
                <th>Delivered</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['customer_name']}</td>";
                echo "<td>{$row['final_fee']}</td>";
                echo "<td>{$row['requested_delivery_date']}</td>";
                if ($row['delivery_status'] == 'Delivered') {
                  echo '<td><i class="fa duotone fa-check" style="font-size:14px;color:green"></i></td>';
                } else {
                  echo '<td><i class="fa fa-times" style="font-size:14px;color:red"></i></td>';
                }
                echo "<td><a href='view_order.php?id={$row['order_id']}'>View</a> </td>";
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
   
    <div class="analysis-section">
      <div class="details">
      <?php
$months = array();
$profits = array();
$dataAvailable = false; // Flag to check if data is available

// Get the current date
$currentYear = date('Y');
$currentMonth = date('m');

// Loop to retrieve data for the last three months
for ($i = 0; $i < 3; $i++) {
    // Calculate the start and end dates for the current month
    $start_date = date('Y-m-01', strtotime("-$i months"));
    $end_date = date('Y-m-t', strtotime("-$i months"));

    // Retrieve monthly information from the database
    $sql = "SELECT DATE_FORMAT(delivery_date, '%M %Y') AS month, SUM(profit) AS total_profit
            FROM orders
            WHERE delivery_status='Delivered' AND delivery_date >= ? AND delivery_date <= ?
            GROUP BY month";
    
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();

    if ($stmt->errno) {
        echo "Query execution error: " . $stmt->error;
    } else {
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $monthYear = $row['month'];
            $totalProfit = $row['total_profit'];
            $months[] = $monthYear;
            $profits[] = $totalProfit;
            $dataAvailable = true; // Data is available
        }
        
        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$mysqli->close();

// If no data is available, display a message
if (!$dataAvailable) {
    echo "<h1 class='check-data'>No available data</h1>";
} else {
    // Reverse the order of the arrays
    $months = array_reverse($months);
    $profits = array_reverse($profits);

    // Loop through the data and echo it
    for ($i = 0; $i < count($months); $i++) {
        echo "<div class='returns'>";
        echo "<h4>Monthly Profits For last three months</h4>";
        echo "<p class='text-naming'>" . $months[$i] . "=>: <span class='text-highlight'>Ksh " . number_format($profits[$i], 2) . "</span></p>";
        
        echo "</div>";
    }
}
?>

</div>
<?php
if (!$dataAvailable) {
  echo "<div class='month-returns'><h1 class='check-data'>No available data</h1></div>";
}
 else {
 echo" <div class='chart-container mt-5 ' >
 <canvas id='profitChart' width='400' height='200'></canvas>

</div>";
}
?>
       
    </div>
      
  </div>
</div>
</div>



<?php include 'includes/footer.php'; ?>
