<?php

include 'components/connect.php';

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];

    // Retrieve order details from order_history table
    $selectOrder = $conn->prepare("SELECT * FROM `order_history` WHERE id = ?");
    $selectOrder->execute([$orderId]);

    if ($selectOrder->rowCount() > 0) {
        $orderDetails = $selectOrder->fetch(PDO::FETCH_ASSOC);

        // Insert the same order details into the orders table as a new order
        $insertOrder = $conn->prepare("INSERT INTO `orders` (user_id, fname, mname, lname, address, total_products, total_price, method, order_status, placed_on)
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insertOrder->execute([
            $orderDetails['user_id'],
            $orderDetails['fname'],
            $orderDetails['mname'],
            $orderDetails['lname'],
            $orderDetails['address'],
            $orderDetails['total_products'],
            $orderDetails['total_price'],
            $orderDetails['method'],
            'Preparing your Food', // Set the initial status for the new order
            date('Y-m-d H:i:s') // Set the current timestamp
        ]);

        // Redirect back to the order history page
        header('Location: history.php');
    } else {
        // Handle the case where the order ID is not found
        echo 'Invalid order ID';
    }
} else {
    // Handle the case where the order ID is not provided
    echo 'Invalid request';
}

?>
