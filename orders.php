<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('Location: home.php');
    exit; // Make sure to exit after header redirection
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- SweetAlert CDN link -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

    <button id="scrollToTopBtn" aria-label="Scroll to Top">&#9650;</button>

<style>

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
        font-weight: bold;
    }

    .box-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center; /* Adjust the justification as needed */
    }

    .box {
        flex-basis: calc(33.33% - 20px); /* Adjust the width and margin as needed */
        box-sizing: border-box;
    }


    .box {
        border: 1px solid #ccc;
        padding: 10px;
        margin: 10px;
        max-width: 400px;
    }

    p {
        margin: 5px 0;
    }

    span {
        font-weight: bold;
    }

    .cancel-btn {
        background-color: #dc3545;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .cancel-btn:hover {
        background-color: #c82333;
    }

    .rate-btn {
        background-color: #4CAF50; /* Green */
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .rate-btn:hover {
        background-color: #45a049;
    }
    
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
    font-size: 5rem;
}
</style>

</head>

<body>

    <!-- header section starts  -->
    <?php include 'components/user_header.php'; ?>
    <!-- header section ends -->

    <div class="heading">
        <h3>Orders</h3>
        <p><a href="home.php">Home</a> <span> / Total Orders</span></p>
    </div>

    <section class="orders">
        <div class="box-container">
            <?php
            if ($user_id == '') {
                echo '<p class="empty">Please login to see your orders</p>';
            } else {
                $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? ORDER BY placed_on DESC");
                $select_orders->execute([$user_id]);
                if ($select_orders->rowCount() > 0) {
                    while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                        $placedTime = strtotime($fetch_orders['placed_on']);
                        $estimatedDeliveryTime = date('Y-m-d H:i:s', $placedTime + (15 * 60)); // Adding 15 minutes
                        ?>
                        <div class="box">
                            <p>Date/Time Placed On: <span><?= $fetch_orders['placed_on']; ?></span></p>
                            <p>First Name: <span><?= $fetch_orders['fname']; ?></span></p>
                            <p>Middle Name: <span><?= $fetch_orders['mname']; ?></span></p>
                            <p>Last Name: <span><?= $fetch_orders['lname']; ?></span></p>
                            <p>Full Address: <span><?= $fetch_orders['address']; ?></span></p>
                            <p>Your Orders: <span><?= $fetch_orders['total_products']; ?></span></p>
                            <p>Total Due: <span>₱<?= $fetch_orders['total_price']; ?></span></p>
                            <p>Payment Method: <span><?= $fetch_orders['method']; ?></span></p>
                            <p>Order Status: <span style="color:<?php
                                                                if ($fetch_orders['order_status'] == 'Preparing your Food') {
                                                                    echo 'red';
                                                                } elseif ($fetch_orders['order_status'] == 'Cancelled') {
                                                                    echo 'grey';
                                                                } else {
                                                                    echo 'green';
                                                                };
                                                                ?>"><?= $fetch_orders['order_status']; ?></span> </p>

                            <?php
                            // Display Delivery Time only if the order is Completed
                            if ($fetch_orders['order_status'] == 'Completed') {
                            ?>
                                <p>Delivered Date/Time: <span><?= $estimatedDeliveryTime; ?></span></p>
                                <button class="rate-btn" onclick="redirectToMessages()">Rate Us</button>
                                <script>
                                    function redirectToMessages() {
                                        window.location.href = "contact.php";
                                    }
                                </script>
                            <?php
                            }

                // Display Cancel My Order button only if the order is not Completed
                if ($fetch_orders['order_status'] != 'Completed') {
                ?>
                    <form id="cancelOrderForm<?= $fetch_orders['id']; ?>" method="post" action="cancel_order.php">
                        <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                        <button type="button" class="cancel-btn" onclick="confirmCancel('<?= $fetch_orders['id']; ?>')">Cancel My Order</button>
                    </form>
                <?php
                }
                ?>
            </div>
<?php
        }
    } else {
        echo '<p class="empty">no orders placed yet!</p>';
    }
}
?>

<script>
    function confirmCancel(orderId) {
        Swal.fire({
            title: "Are you sure you want to cancel the order?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes", // Add a comma here
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
                // If the user confirms, submit the form
                document.getElementById("cancelOrderForm" + orderId).submit();
            }
        });
    }
</script>



    </section>
    </div>


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
