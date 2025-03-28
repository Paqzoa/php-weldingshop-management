<?php
session_start();
include "../configs/config.php";
$order_form_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $customerName = $_POST['customerName'];
    $customerPhone = $_POST['customerPhone'];
    $orderType = $_POST['orderType'];
    $rawRequestedDeliveryDate = $_POST['requestedDeliveryDate']; // Updated field name
  
    $orderDescription = $_POST['orderDescription'];

    // Validate form data
    if (empty($customerName) || empty($customerPhone) || empty($orderType) || empty($rawRequestedDeliveryDate)  || empty($orderDescription)) {
        $order_form_error ="All field required";
        $error_message = "errormessage_err=" . urlencode($order_form_error) ;
        header("Location: ../order-page.php?" . $error_message);
        exit;
        
    } else {
        // Convert raw requested delivery date input to valid MySQL date format
        $convertedRequestedDeliveryDate = date('Y-m-d', strtotime($rawRequestedDeliveryDate));
        date_default_timezone_set('Africa/Nairobi');

        $currentDateTime = date("Y-m-d H:i:s");
        // Use prepared statements to prevent SQL injection
        $sql = "INSERT INTO orders (customer_name, customer_phone, order_type, requested_delivery_date, order_description, order_request_date) VALUES (?, ?, ?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($mysqli, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_customerName, $param_customerPhone, $param_orderType, $param_requestedDeliveryDate, $param_orderDescription, $param_order_request_date);

            // Set parameters
            $param_customerName = $customerName;
            $param_customerPhone = $customerPhone;
            $param_orderType = $orderType;
            $param_requestedDeliveryDate = $convertedRequestedDeliveryDate;
            $param_orderDescription = $orderDescription;
            $param_order_request_date = $currentDateTime;

            // Execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // echo "<script>" . "alert('Order submitted successfully!');" . "</script>";
                // echo "<script>" . "window.location.href='../order-page.php'" . "</script>";
                header("Location: ../order-page.php?show_popup=true");
            } else {
                echo "Error: " . mysqli_error($mysqli);
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
}
?>
