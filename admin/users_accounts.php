<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_users->execute([$delete_id]);
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_order->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);
   header('location:users_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Users Accounts</title>

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

   <!-- SweetAlert CDN link -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <button id="scrollToTopBtn" aria-label="Scroll to Top">&#9650;</button>


</head>

<body>

   <?php include '../components/sales_header.php' ?>

   <!-- user accounts section starts  -->

   <section class="accounts">

      <h1 class="heading">Users Account</h1>

      <div class="box-container">

         <?php
         $select_account = $conn->prepare("SELECT * FROM `users`");
         $select_account->execute();
         if ($select_account->rowCount() > 0) {
            while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <div class="box">
                  <p>First Name: <span><?= $fetch_accounts['fname']; ?></span> </p>
                  <p>Middle Name: <span><?= $fetch_accounts['mname']; ?></span> </p>
                  <p>Last Name: <span><?= $fetch_accounts['lname']; ?></span> </p>
                  <p>Email: <span><?= $fetch_accounts['email']; ?></span> </p>
                  <p>Number: <span><?= $fetch_accounts['number']; ?></span> </p>
                  <p>Address: <span><?= $fetch_accounts['address']; ?></span> </p>

                  <a href="#" class="delete-btn" onclick="confirmDelete(<?= $fetch_accounts['id']; ?>)">delete</a>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">no accounts available</p>';
         }
         ?>

      </div>

   </section>

   <!-- user accounts section ends -->

  <!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

<script>
   function confirmDelete(userId) {
      Swal.fire({
         title: 'Remove',
         text: 'You want to remove this account?',
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#d33',
         cancelButtonColor: '#3085d6',
         confirmButtonText: 'Yes, remove it!',
         customClass: {
            popup: 'larger-sweetalert' // Add the custom class for the SweetAlert
         }
      }).then((result) => {
         if (result.isConfirmed) {
            // Redirect to delete with user ID
            window.location.href = 'users_accounts.php?delete=' + userId;
         }
      });
   }
</script>

<style>
   /* Add this to your CSS file or within a <style> tag in your HTML file */
   .larger-sweetalert {
      font-size: 18px; /* Adjust the font size as needed */
   }
</style>

</body>

</html>
