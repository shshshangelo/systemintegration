<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

    <!-- SweetAlert library link -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

       <button id="scrollToTopBtn" aria-label="Scroll to Top">&#9650;</button>



       <style>
    .order-history {
        margin-top: 5px;
    }

    .box-container {
        display: flex;
        flex-wrap: wrap;
    }

    .box {
        background-color: #66806A;
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
        color: white;
        font-weight: bold;
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

    
        .delete-btn{
 
            background-color: red;
            color: #fff;
            border: none;
            padding: 9px 10px;
            border-radius: 5px;
            font-size: 19px;
            cursor: pointer;
        }

        .order-again-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 9px 10px;
            border-radius: 5px;
            font-size: 19px;
            cursor: pointer;
        }

        .order-again-btn:hover {
            background-color: #0056b3;
        }

        .delete-btn:hover {
            background-color: #c82333;

        }

    </style>
</head>

<body>

<style>
    /* Custom styles for larger SweetAlert messages */
.larger-sweetalert-container {
    font-size: 2rem;
}

.larger-sweetalert-popup {
    width: 50%;
    height: 50%;
}

.larger-sweetalert-title {
    font-size: 3rem;
}

.larger-sweetalert-content {
    font-size: 2rem;
}

.larger-sweetalert-confirm-button,
.larger-sweetalert-cancel-button {
    font-size: 2rem;
}

/* Custom styles for larger success messages */
.larger-sweetalert-container.success {
    font-size: 2rem;
}

.larger-sweetalert-popup.success {
    width: 50%;
    height: 50%;}

.larger-sweetalert-title.success {
    font-size: 3rem;
}

.larger-sweetalert-content.success {
    font-size: 1.6rem;
}

.larger-sweetalert-confirm-button.success,
.larger-sweetalert-cancel-button.success {
    font-size: 2rem;
}
</style>

    <!-- header section starts  -->
    <?php include 'components/user_header.php'; ?>
    <!-- header section ends -->

    <div class="heading">
        <h3>Order History</h3>
        <p><a href="home.php">Home</a> <span> / Order History</span></p>
    </div>

    <section class="order-history">

        <div class="box-container">

            <?php
            if ($user_id == '') {
                echo '<p class="empty">Please login to view your order history.</p>';
            } else {
                try {
                    $select_history = $conn->prepare("SELECT * FROM `order_history` WHERE user_id = ? ORDER BY placed_on DESC");
                    $select_history->execute([$user_id]);
                } catch (PDOException $e) {
                    echo 'Error executing query: ' . $e->getMessage();
                    die(); // Stop execution if there's an error
                }

                if ($select_history->rowCount() > 0) {
                    while ($fetch_history = $select_history->fetch(PDO::FETCH_ASSOC)) {
                        if ($fetch_history['order_status'] == 'Completed') {
                            $placedTime = strtotime($fetch_history['placed_on']);
                            $estimatedDeliveryTime = date('Y-m-d H:i:s', $placedTime + (15 * 60)); // Assuming 15 minutes delivery time
                                        }
                        ?>
                        <div class="box">
                            <p>Date/Time Placed On: <span><?= $fetch_history['placed_on']; ?></span></p>
                            <p>First Name: <span><?= $fetch_history['fname']; ?></span></p>
                            <p>Middle Name: <span><?= $fetch_history['mname']; ?></span></p>
                            <p>Last Name: <span><?= $fetch_history['lname']; ?></span></p>
                            <p>Full Address: <span><?= $fetch_history['address']; ?></span></p>
                            <p>Your Orders: <span status="<?= $fetch_history['order_status']; ?>"><?= $fetch_history['total_products']; ?></span></p>
                            <p>Total Due: <span>₱<?= $fetch_history['total_price']; ?></span></p>
                            <p>Payment Method: <span><?= $fetch_history['method']; ?></span></p>
               
                     <p>Order Status: <span status="<?= $fetch_history['order_status']; ?>"><?= $fetch_history['order_status']; ?></span></p>
                     <?php
        // Display Delivered Date/Time only if the order is Completed
        if ($fetch_history['order_status'] == 'Completed') {
            echo '<p>Delivered Date/Time: <span>' . $estimatedDeliveryTime . '</span></p>';
        }
        ?>
                     <button class="order-again-btn" onclick="orderAgain(<?= $fetch_history['id']; ?>)">Order Again</button>
                            <button class="delete-btn" onclick="deleteOrder(<?= $fetch_history['id']; ?>)">Delete</button>
                        </div>
                    <?php
                    }
                } else {
                    echo '<p class="empty">No order history available!</p>';
                }
            }
            ?>

        </div>

    </section>


<script>
   // Function to reorder the same products
function orderAgain(orderId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to order this again?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#007bff',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Yes, order again!',
        customClass: {
            container: 'larger-sweetalert-container',
            popup: 'larger-sweetalert-popup',
            header: 'larger-sweetalert-header',
            title: 'larger-sweetalert-title',
            closeButton: 'larger-sweetalert-close-button',
            icon: 'larger-sweetalert-icon',
            image: 'larger-sweetalert-image',
            content: 'larger-sweetalert-content',
            input: 'larger-sweetalert-input',
            actions: 'larger-sweetalert-actions',
            confirmButton: 'larger-sweetalert-confirm-button',
            cancelButton: 'larger-sweetalert-cancel-button',
            footer: 'larger-sweetalert-footer'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Successfully Ordered!', '', 'success');
            setTimeout(() => {
                // You can use AJAX to perform the reorder without refreshing the page
                // For simplicity, redirecting to a PHP page for ordering again
                window.location.href = "order_again.php?id=" + orderId;
            }, 2000); // Delay the redirection for 2 seconds
        }
    });
}

// Function to delete an order
function deleteOrder(orderId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to delete this receipt?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        customClass: {
            container: 'larger-sweetalert-container',
            popup: 'larger-sweetalert-popup',
            header: 'larger-sweetalert-header',
            title: 'larger-sweetalert-title',
            closeButton: 'larger-sweetalert-close-button',
            icon: 'larger-sweetalert-icon',
            image: 'larger-sweetalert-image',
            content: 'larger-sweetalert-content',
            input: 'larger-sweetalert-input',
            actions: 'larger-sweetalert-actions',
            confirmButton: 'larger-sweetalert-confirm-button',
            cancelButton: 'larger-sweetalert-cancel-button',
            footer: 'larger-sweetalert-footer'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Successfully Removed!', '', 'success');
            setTimeout(() => {
                // You can use AJAX to perform the deletion without refreshing the page
                // For simplicity, redirecting to a PHP page for deletion
                window.location.href = "delete_order.php?id=" + orderId;
            }, 3000); // Delay the redirection for 3 seconds
        }
    });
}

</script>


<div class="loader">
        <img src="images/loader.gif" alt="">
    </div>



    <!-- custom js file link  -->
    <script src="js/script.js"></script>

    <style>
   /* Base styles for all screen sizes */
body {
  font-family: 'Arial', sans-serif;
  margin: 0;
  padding: 0;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

/* Responsive styles using media queries */

/* For screens smaller than 600px */
@media only screen and (max-width: 600px) {
  body {
    font-size: 14px;
  }

  .container {
    padding: 10px;
  }
}

/* For screens between 600px and 900px */
@media only screen and (min-width: 601px) and (max-width: 900px) {
  body {
    font-size: 16px;
  }

  .container {
    padding: 15px;
  }
}

/* For screens larger than 900px */
@media only screen and (min-width: 901px) {
  body {
    font-size: 18px;
  }

  .container {
    padding: 20px;
  }
}
</style>

</body>

</html>
