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
   <title>HeadChef Dashboard</title>

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

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- admin dashboard section starts  -->

<br><br>
<br>
<br>
<br>
<br>
<br>

<section class="dashboard">
   <h1 class="heading">HeadChef Dashboard</h1>

   <div class="box-container">

   <div class="box">
      <h3>Hello!</h3>
      <p><?= $fetch_profile['name']; ?></p>
   </div>

   <div class="box">
      <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         $numbers_of_products = $select_products->rowCount();
      ?>
      <h3><?= $numbers_of_products; ?></h3>
      <p>Total Menu</p>
      <a href="products.php" class="btn">Menu Lists</a>
   </div>

   <div class="box">
      <?php
         $total_pendings = 0;
         $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE order_status = ?");
         $select_pendings->execute(['Preparing your Food']);
         while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
            $checkrows = $select_pendings->rowCount();
            if($checkrows === 0 || $checkrows === null){
               $total_pendings = 0;
            } else {
               $total_pendings = $select_pendings->rowCount();
            }
            // $total_pendings += $fetch_pendings['total_price'];
         }
      ?>

      <h3><span></span><?= $total_pendings; ?><span></span></h3>
      <p>Customer Orders</p>
      <a href="placed_orders.php" class="btn">Orders</a>

   </div>

   <div class="box">
    <?php
    $select_cancelled_orders = $conn->prepare("SELECT * FROM `cancelled_orders`");
    $select_cancelled_orders->execute();
    $numbers_of_cancelled_orders = $select_cancelled_orders->rowCount();
    ?>
    <h3><?= $numbers_of_cancelled_orders; ?></h3>
    <p>Cancelled Orders</p>
    <a href="cancelled_orders.php" class="btn">Cancelled Orders</a>
</div>


   <div class="box">
      <?php
         $select_orders = $conn->prepare("SELECT * FROM `completed_orders`");
         $select_orders->execute();
         $numbers_of_orders = $select_orders->rowCount();
      ?>
      <h3><?= $numbers_of_orders; ?></h3>
      <p>All Orders</p>
      <a href="completed_orders.php" class="btn">Total Orders</a>
        </div>

        

   </div>

</section>

<!-- admin dashboard section ends -->


<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>