<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];

   // Add a check to confirm before deletion
   $confirm_delete = "Swal.fire({
      title: 'Are you sure?',
      text: 'You won\'t be able to recover this message!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete it!'
   }).then((result) => {
      if (result.isConfirmed) {
         window.location.href = 'messages.php?delete=" . $delete_id . "';
      }
   });";

   echo "<script>{$confirm_delete}</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages</title>

   <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

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

   <button id="scrollToTopBtn" aria-label="Scroll to Top">&#9650;</button>

</head>
<body>

<?php include '../components/sales_header.php' ?>

<!-- messages section starts  -->

<section class="messages">

   <h1 class="heading">Feedbacks</h1>

   <div class="box-container">

   <?php
$select_messages = $conn->prepare("SELECT * FROM `messages` ORDER BY id DESC");
$select_messages->execute();
      if($select_messages->rowCount() > 0){
               while($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)){
   ?>
<div class="box">
   <p>First Name: <span><?= $fetch_messages['fname']; ?></span></p>
   <p>Middle Name: <span><?= $fetch_messages['mname']; ?></span></p>
   <p>Last Name: <span><?= $fetch_messages['lname']; ?></span></p>
   <p>Mobile Number: <span><?= $fetch_messages['number']; ?></span></p>
   <p>Email: <span><?= $fetch_messages['email']; ?></span></p>
   <p>Message: <span><?= $fetch_messages['message']; ?></span></p>
   <p>Rating: <span><?= $fetch_messages['rating']; ?></span></p>
</div>
   <?php
         }
      }else{
         echo '<p class="empty">you have no messages</p>';
      }
   ?>

   </div>

</section>

<!-- messages section ends -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

<!-- Add the SweetAlert CDN link -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
   function showConfirmAlert(deleteId) {
      Swal.fire({
         title: 'Are you sure?',
         text: 'You won\'t be able to recover this message!',
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#d33',
         cancelButtonColor: '#3085d6',
         confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
         if (result.isConfirmed) {
            // Use AJAX to perform deletion without reloading the page
            $.ajax({
               type: 'GET',
               url: 'messages.php?delete=' + deleteId,
               success: function () {
                  Swal.fire(
                     'Deleted!',
                     'The message has been deleted.',
                     'success'
                  ).then(() => {
                     // Reload the page after successful deletion
                     location.reload();
                  });
               }
            });
         }
      });
   }
</script>


</body>
</html>
