<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Add your head content here -->
    <title>Cancelled Orders</title>
</head>

<body>

<?php include '../components/admin_header.php' ?>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<!-- jQuery (required for Bootstrap JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

<!-- Popper.js (required for Bootstrap JavaScript plugins) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-rs0p4MlJS9VvlCk70R1T0tkw6CsS87+a9/NYrOLv/GsPF0MVUeKYYaCk78QaQ1M6" crossorigin="anonymous"></script>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- Font Awesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">


<!-- custom css file link  -->
<link rel="stylesheet" href="../css/admin_style.css">


    <section class="cancelled-orders">
        <h1 class="heading">Cancelled Orders</h1>

        <style>
            /* Your existing styles above this comment */

            /* Add specific styles for cancelled orders */
            .box-container-cancelled {
                display: flex;
        flex-wrap: wrap;
        justify-content: center;
            }

            .box-cancelled {
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

            .box-cancelled:hover {
                transform: scale(1.02);
            }

            .box-cancelled p {
                margin-bottom: 5px;
                font-size: 18px;
            }

            .Empty-cancelled {
                padding: 1.5rem;
                text-align: center;
                width: 100%;
                font-size: 2rem;
                text-transform: capitalize;
                color: red;
            }

            /* Responsive styles for cancelled orders */
            @media screen and (max-width: 768px) {
                .box-cancelled {
                    width: calc(50% - 20px);
                }
            }

            @media screen and (max-width: 480px) {
                .box-cancelled {
                    width: calc(100% - 20px);
                }
            }

            /* Style for Cancelled status */
            .box-cancelled p span[status="Cancelled"] {
                color: white;
            }

            .box-container-cancelled {
                display: flex;
                flex-wrap: wrap;
                justify-content: space; /* Adjust the justification as needed */
            }

            .box-cancelled {
                flex-basis: calc(33.33% - 20px); /* Adjust the width and margin as needed */
                box-sizing: border-box;
            }
        </style>

<div class="box-container-cancelled">

<?php
$select_cancelled_orders = $conn->prepare("
    SELECT co.*, u.fname, u.mname, u.lname, u.email, u.number, u.address
    FROM `cancelled_orders` co
    JOIN `users` u ON co.user_id = u.id
    ORDER BY co.cancelled_on DESC
");
$select_cancelled_orders->execute();

if ($select_cancelled_orders->rowCount() > 0) {
    while ($fetch_cancelled_orders = $select_cancelled_orders->fetch(PDO::FETCH_ASSOC)) {
        ?>

        <div class="box-cancelled">
            <p>Cancelled On: <span><?= $fetch_cancelled_orders['cancelled_on']; ?></span></p>
            <p>First Name: <span><?= $fetch_cancelled_orders['fname']; ?></span></p>
            <p>Middle Name: <span><?= $fetch_cancelled_orders['mname']; ?></span></p>
            <p>Last Name: <span><?= $fetch_cancelled_orders['lname']; ?></span></p>
            <p>Email: <span><?= $fetch_cancelled_orders['email']; ?></span></p>
            <p>Number: <span><?= $fetch_cancelled_orders['number']; ?></span></p>
            <p>Address: <span><?= $fetch_cancelled_orders['address']; ?></span></p>
            <p>Order Status:
    <label for="order_id" style="font-size: 18px; font-weight: bold; color: red;">Cancelled
    </label>        
</div>

<?php
    }
} else {
    echo '<p class="Empty-cancelled">No cancelled orders yet.</p>';
}
?>

</div>


    </section>

    <script src="../js/admin_script.js"></script>

</body>

</html>
