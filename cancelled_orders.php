<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Redirect to home if the user is not logged in
    header('location:home.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancelled Orders</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- SweetAlert CDN link -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

    <button id="scrollToTopBtn" aria-label="Scroll to Top">&#9650;</button>

</head
>
<body>

<style>
      .box-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .box {
        background-color: green;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease-in-out;
        display: grid;
        align-items: flex-start;
        margin: 15px;
        color: white;
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
</style>

    <!-- Include your header file -->
    <?php include 'components/user_header.php'; ?>

    <div class="heading">
        <h3>Cancelled Orders</h3>
        <p><a href="home.php">Home</a> <span> / Cancelled Orders</span></p>
    </div>
    <section class="cancelled-orders">

        <div class="box-container">

            <?php
            // Prepare and execute SQL statement to select cancelled orders with additional user details
            $select_cancelled_orders = $conn->prepare("
                SELECT co.*, u.fname, u.mname, u.lname, u.email, u.number, u.address
                FROM `cancelled_orders` co
                JOIN `users` u ON co.user_id = u.id
                WHERE co.user_id = ?
                ORDER BY co.cancelled_on DESC
            ");
            $select_cancelled_orders->execute([$user_id]);

            if ($select_cancelled_orders->rowCount() > 0) {
                while ($fetch_cancelled_orders = $select_cancelled_orders->fetch(PDO::FETCH_ASSOC)) {
                    // Display cancelled order details as needed
                    // Example:
                    echo '<div class="box">';
                    echo '<p>Cancelled On: ' . $fetch_cancelled_orders['cancelled_on'] . '</p>';
                    echo '<p>First Name: ' . $fetch_cancelled_orders['fname'] . '</p>';
                    echo '<p>Middle Name: ' . $fetch_cancelled_orders['mname'] . '</p>';
                    echo '<p>Last Name: ' . $fetch_cancelled_orders['lname'] . '</p>';
                    echo '<p>Email: ' . $fetch_cancelled_orders['email'] . '</p>';
                    echo '<p>Number: ' . $fetch_cancelled_orders['number'] . '</p>';
                    echo '<p>Address: ' . $fetch_cancelled_orders['address'] . '</p>';
                    echo '<p style="color: red;">Order Status: Cancelled</p>'; // Add this line with the style
                    // Display other details if needed
                    echo '</div>';
                }
            } else {
                echo '<p class="empty">No cancelled orders yet!</p>';
            }
            ?>

        </div>

    </section>

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
