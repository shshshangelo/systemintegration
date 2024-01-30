<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Management Dashboard</title>

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
   <link rel="stylesheet" href="../css/sales_style.css">

</head>
<body>

<?php include '../components/sales_header.php' ?>

<!-- admin dashboard section starts  -->

<section class="dashboard">

   <h1 class="heading" style="color: #ffffff; cursor: default;">Management Dashboard</h1>

   <div class="box-container">

   <div class="box">
   <br>

      <h3>Hi!</h3>
      <p><?= $fetch_profile['name']; ?></p>
   </div>


   <div class="box">
      <?php
         $total_pendings = 0;
         $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE order_status = ?");
         $select_pendings->execute(['Preparing your Food']);
         while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
            $total_pendings += $fetch_pendings['total_price'];
         }
      ?>
      <h3><span>₱</span><?= $total_pendings; ?><span></span></h3>
      <p>Total Pending Payment Amount of Customer</p>
      <a href="pending.php" class="btn">Pending Payment Amount</a>
   </div>

   <div class="box">
      <?php
         $total_completes = 0;
         $select_completes = $conn->prepare("SELECT * FROM `completed_orders` WHERE order_status = ?");
         $select_completes->execute(['Completed']);
         while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
            $total_completes += $fetch_completes['total_price'];
         }
      ?>
      <h3><span>₱</span><?= $total_completes; ?><span></span></h3>
      <p>Total Completed Payment Amount of Customer</p>
      <a href="complete.php" class="btn">Completed Payment Amount</a>
   </div>

   <div class="box">
      <?php
         $total_completes = 0;
         $select_completes = $conn->prepare("SELECT * FROM `completed_orders` WHERE order_status = ?");
         $select_completes->execute(['Completed']);
         while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
            $total_completes = $select_completes->rowCount();
         }
      ?>
      <h3><span></span><?= $total_completes; ?><span></span></h3>
      <p>Total Completed Orders of Customer</p>
      <a href="complete.php" class="btn">Total Completed Orders</a>
   </div>

   <div class="box">
    <?php
    $select_cancelled_orders = $conn->prepare("SELECT * FROM `cancelled_orders`");
    $select_cancelled_orders->execute();
    $numbers_of_cancelled_orders = $select_cancelled_orders->rowCount();
    ?>
    <h3><?= $numbers_of_cancelled_orders; ?></h3>
    <p>Total Cancelled Orders of Customer</p>
    <a href="cancel.php" class="btn">Total Cancelled Orders</a>
</div>
      
   <div class="box">
      <?php
         $select_messages = $conn->prepare("SELECT * FROM `messages`");
         $select_messages->execute();
         $numbers_of_messages = $select_messages->rowCount();
      ?>
      <h3><?= $numbers_of_messages; ?></h3>
      <p>New Reviews of Customers</p>
      <a href="messages.php" class="btn">All Feedbacks</a>
   </div>

   <div class="box">
      <?php
         $select_users = $conn->prepare("SELECT * FROM `users`");
         $select_users->execute();
         $numbers_of_users = $select_users->rowCount();
      ?>
      <h3><?= $numbers_of_users; ?></h3>
      <p>Customer's Account</p>
      <a href="users_accounts.php" class="btn">Customers Account</a>
   </div>

   </div>

</section>

<!-- admin dashboard section ends -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>
