<?php
session_start();

# Check if user is already logged in, If yes then redirect him to index page
if (!isset($_SESSION["adminloggedin"]) && $_SESSION["adminloggedin"] == !TRUE) {
  echo "<script>" . "window.location.href='login.php'" . "</script>";
  exit;
}

include 'includes/header.php';
include 'configs/config.php';
if (isset($_GET['show_popup']) && $_GET['show_popup'] === 'true') {
    $popupContent = "Cost added succesfully";
    echo "<script>openPopup('$popupContent');</script>";
  }

$order_id = $_GET['id'];

// ...

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['confirmDelivery'])) {

        // Fetch the fixed cost incurred from the "orders" table
        $sql = "SELECT cost_incurred FROM orders WHERE order_id = ?";
        $stmt = mysqli_prepare($mysqli, $sql);
        mysqli_stmt_bind_param($stmt, "i", $order_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        if ($row = mysqli_fetch_assoc($result)) {
    
            $costIncurred = $row['cost_incurred'];
    
            // Get the final fee from the form
            $finalFee = $_POST['finalProductFee'];
    
            // Calculate profit and profit percentage
            $profit = $finalFee - $costIncurred;
            $profitPercentage = round(($profit / $costIncurred) * 100, 2);
    
            // Update the "final_fee," "profit," "profit_percentage," and "delivery_date" fields in the "orders" table
            date_default_timezone_set('Africa/Nairobi');
            $currentDate = date("Y-m-d");
            $updateSql = "UPDATE orders SET delivery_status = 'Delivered', final_fee = ?, profit = ?, percentage_profit = ?, delivery_date = ? WHERE order_id = ?";
            $updateStmt = mysqli_prepare($mysqli, $updateSql);
    
            mysqli_stmt_bind_param($updateStmt, "dddsi", $finalFee, $profit, $profitPercentage, $currentDate, $order_id);
    
            if (! mysqli_stmt_execute($updateStmt)) {
                echo "Error updating order details: " . mysqli_error($mysqli);
            }
    
            mysqli_stmt_close($updateStmt);
        } else {
            echo "Error fetching cost incurred from the orders table.";
        }
    }
    

    
     elseif (isset($_POST['updateDetails'])) {
        $actualCost = $_POST['actualCost'];
        $costDescription = $_POST['costDescription'];
    
        // Insert the cost details into the "cost" table
        $sql = "INSERT INTO cost (order_id, cost_description, cost_amount) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($mysqli, $sql);
        
        // Associate the cost with the order by using $order_id
        mysqli_stmt_bind_param($stmt, "isd", $order_id, $costDescription, $actualCost);
    
        if (mysqli_stmt_execute($stmt)) {
            
            echo "<script>" . "alert('Cost details added successfully!');" . "</script>";
            header("Location: view_order.php?id=$order_id&show_popup=true");
        } else {
            echo "Error in adding cost details: " . mysqli_error($mysqli);
        }
    
        mysqli_stmt_close($stmt);
    }
    // Handle the deletion of a cost detail
// Handle the deletion of a cost detail
elseif (isset($_POST['deleteCost'])) {
    $deleteCostId = $_POST['deleteCostId'];

    // Perform the deletion in the database
    $deleteSql = "DELETE FROM cost WHERE cost_id = ?";
    $deleteStmt = mysqli_prepare($mysqli, $deleteSql);
    mysqli_stmt_bind_param($deleteStmt, "i", $deleteCostId);

    if (mysqli_stmt_execute($deleteStmt)) {
        // Calculate the new total cost from the cost table
        $totalCost = 0;
        $selectCostSql = "SELECT cost_amount FROM cost WHERE order_id = ?";
        $selectCostStmt = mysqli_prepare($mysqli, $selectCostSql);
        mysqli_stmt_bind_param($selectCostStmt, "i", $order_id);
        mysqli_stmt_execute($selectCostStmt);
        $costResult = mysqli_stmt_get_result($selectCostStmt);

        while ($row = mysqli_fetch_assoc($costResult)) {
            $totalCost += $row['cost_amount'];
        }

        // Update the cost_incurred field in the orders table
        $updateCostSql = "UPDATE orders SET cost_incurred = ? WHERE order_id = ?";
        $updateCostStmt = mysqli_prepare($mysqli, $updateCostSql);
        mysqli_stmt_bind_param($updateCostStmt, "di", $totalCost, $order_id);

        if (mysqli_stmt_execute($updateCostStmt)) {
            header("Location: view_order.php?id=$order_id");
            exit;
        } else {
            echo "Error updating cost_incurred: " . mysqli_error($mysqli);
        }

        mysqli_stmt_close($updateCostStmt);
    } else {
        echo "Error in deleting cost detail: " . mysqli_error($mysqli);
    }

    mysqli_stmt_close($deleteStmt);
}


}

// ...



// Retrieve order details from the database
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

        // Check if the delivery status is not "Delivered"
        if ($orderStatus['delivery_status'] != 'Delivered') {
            echo "<th>Action</th>";
        }

        echo "</tr></thead>";
        echo "<tbody>";

        $totalCost = 0; // Initialize total cost

        while ($row = mysqli_fetch_assoc($costsResult)) {
            echo "<tr>";
            echo "<td>{$row['cost_description']}</td>";
            echo "<td>Ksh{$row['cost_amount']}</td>";

            // Check if the delivery status is not "Delivered"
            if ($orderStatus['delivery_status'] != 'Delivered') {
                // Add a delete button with a form for each cost detail
                echo "<td>
                        <form method='post'>
                            <input type='hidden' name='deleteCostId' value='{$row['cost_id']}'>
                            <button type='submit' name='deleteCost' class='btn btn-danger danger-btn'>Delete</button>
                        </form>
                      </td>";
            }

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
<?php if ($order['delivery_status'] === 'Not Delivered'): ?>
<div class="view-section2">
<div class="cost-details-form">
<h2 class="bg-primary">Add Cost Details</h2>
<form method="post">

    <div class="mb-3">
        <label for="actualCost" class="form-label"> Cost incurred</label>
        <input type="number" step="0.01" class="form-control" id="actualCost" name="actualCost" required>
    </div>
    <div class="mb-3">
        <label for="costDescription" class="form-label">Cost Description</label>
        <input type="text" class="form-control" id="costDescription" name="costDescription" required>
    </div>
    <button type="submit" name="updateDetails" class="btn btn-primary">Add Cost</button>
</form>
</div>
<div class="delivery">
<?php if ($order['cost_incurred'] > '0.00'): ?>
    <h2 class="bg-primary">Confirm Delivery</h2>
    <form method="post">
        <div class="mb-3">
            <label for="finalProductFee" class="form-label">Final Product Fee</label>
            <input type="number" step="0.01" class="form-control" id="finalProductFee" name="finalProductFee" required>
        </div>
        <button type="submit" name="confirmDelivery" class="btn btn-success">Confirm Delivery</button>
    </form>
<?php endif; ?>
</div>
<?php else: ?>
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
    
    // Close the database connection
    $stmt->close();
}

$mysqli->close();
?>

</div>
</div>
</div>
   
<?php endif; ?>
</div>
</div>
<?php
}

include 'includes/footer.php';
?>
