<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id']) && isset($_POST['order_id'])) {
    $user_id = $_SESSION['user_id'];
    $order_id = $_POST['order_id'];

    // Fetch the order details before deleting it
    $select_order = $conn->prepare("SELECT * FROM `orders` WHERE id = ? AND user_id = ? ORDER BY id DESC");
    $select_order->execute([$order_id, $user_id]);

    if ($select_order->rowCount() > 0) {
        $order_details = $select_order->fetch(PDO::FETCH_ASSOC);

        // Move the order details to the cancelled orders table or file (you can customize this part)
        // Example: Move to a separate table
        $insert_cancelled_order = $conn->prepare("INSERT INTO cancelled_orders (user_id, order_id, cancelled_on) VALUES (?, ?, NOW())");
        $insert_cancelled_order->execute([$user_id, $order_id]);

        // Delete the order from the original orders table
        $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ? AND user_id = ?");
        $delete_order->execute([$order_id, $user_id]);

        // Redirect back to the orders page
        header('Location: cancelled_orders.php');
        exit();
    }
}

// Redirect to home if the user or order is not valid
header('Location: home.php');
exit();
?>
