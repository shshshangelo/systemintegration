<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_POST['update_payment'])) {
   $order_id = $_POST['order_id'];
   $order_status = $_POST['order_status'];

   // Add a timestamp for 'Completed' status
   $timestamp = ($order_status === 'Completed') ? date('Y-m-d H:i:s') : null;

   $update_status = $conn->prepare("UPDATE `orders` SET order_status = ?, completed_timestamp = ? WHERE id = ?");
   $update_status->execute([$order_status, $timestamp, $order_id]);

   if ($order_status === 'Completed') {
      // Move the completed order to the 'completed_orders' table
      $move_to_completed = $conn->prepare("INSERT INTO `completed_orders` (user_id, placed_on, fname, mname, lname, address, total_products, total_price, method, order_status, completed_timestamp)
                                      SELECT user_id, placed_on, fname, mname, lname, address, total_products, total_price, method, order_status, completed_timestamp
                                      FROM `orders`
                                      WHERE id = ?");
      $move_to_completed->bindParam(':timestamp', $timestamp, PDO::PARAM_STR);
      $move_to_completed->execute([$order_id]);

      // Move the completed order to the 'order_history' table
      $move_to_history = $conn->prepare("INSERT INTO `order_history` (user_id, placed_on, fname, mname, lname, address, total_products, total_price, method, order_status)
                                      SELECT user_id, placed_on, fname, mname, lname, address, total_products, total_price, method, order_status
                                      FROM `orders`
                                      WHERE id = ?");
      $move_to_history->execute([$order_id]);

      // Optionally, you can keep the order in the 'orders' table if needed

      // Remove the order from the 'orders' table
      $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
      $delete_order->execute([$order_id]);

      // Redirect to placed_orders.php
      header('location: placed_orders.php');
      exit(); // Make sure to exit to prevent further code execution
  }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
    $delete_order->execute([$delete_id]);
    header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Customer Order Lists</title>

   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

   <!-- bootstrap cdn link  -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
   <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <button id="scrollToTopBtn" aria-label="Scroll to Top">&#9650;</button>


   <style>
    .box-container {
        display: flex;
        flex-wrap: wrap;
    }

    .box {
        background-color: transparent;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease-in-out;
        border: 1px solid #ccc;
        padding: 10px;
        margin: 10px;
        max-width: 400px;
    }

    .box:hover {
        transform: scale(1.02);
    }

    .box p {
        margin-bottom: 5px;
        font-size: 18px; /* Adjust the font size as needed */
        color: black; /* Set text color to black */
    }

    .empty {
        padding: 1.5rem;
        text-align: center;
        width: 100%;
        font-size: 2rem;
        text-transform: capitalized;
        color: red;
    }

    /* Responsive styles */
    @media screen and (max-width: 768px) {
        .box {
            width: calc(50% - 20px);
        }
    }

    @media screen and (max-width: 480px) {
        .box {
            width: calc(100% - 20px);
        }
    }

    /* Style for Completed status */
    .box p span[status="Completed"] {
        color: red;
    }

    .box-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space; /* Adjust the justification as needed */
    }

    .box {
        flex-basis: calc(33.33% - 20px); /* Adjust the width and margin as needed */
        box-sizing: border-box;
    }

    p {
        margin: 5px 0;
    }

    span {
        font-weight: bold;
    }
      .bigger-sweet-alert {
         font-size: 24px;
      }
   </style>

</head>

<body>

   <?php include '../components/sales_header.php' ?>

   <!-- placed orders section starts  -->

   <section class="placed-orders">
      <h1 class="heading">Pending Customer Orders</h1>

      <div class="box-container">

         <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                // Calculate estimated delivery time (assuming 5 minutes)
                $placedTime = strtotime($fetch_orders['placed_on']);
                $estimatedDeliveryTime = date('Y-m-d H:i:s', $placedTime + (5 * 60)); // Adding 5 minutes
        ?>

               <div class="box">
            <p>Date/Time Placed On: <span><?= $fetch_orders['placed_on']; ?></span></p>
                  <p>Customer Name: <span><?= $fetch_orders['fname'] . ' ' . $fetch_orders['mname'] . ' ' . $fetch_orders['lname']; ?></span></p>
                  <p>Total Menu: <span><?= $fetch_orders['total_products']; ?></span>
                  <p>Total Due: <span>₱<?= $fetch_orders['total_price']; ?></span></p>
                  <p>Payment Method: <span><?= $fetch_orders['method']; ?></span></p>
                  <p>Delivery Time: <span><?= $estimatedDeliveryTime; ?></span></p>
                  <p>Order Status: 
                  <label for="order_id" style="font-size: 18px; font-weight: bold; color: red;">
                  <span><?= $fetch_orders['order_status']; ?></span></p>
               </div>
   <?php
            }
         } else {
            echo '<p class="Empty"></p>';
         }
         ?>
   <!-- custom js file link  -->
   <script src="../js/admin_script.js"></script>

</body>

</html>
