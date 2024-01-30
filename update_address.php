<?php
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:home.php');
}

if(isset($_POST['submit'])) {
   $address = $_POST['city'] .', '.$_POST['barangay'] .', '. $_POST['postal_code'] .', '. $_POST['area'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);

   $update_address = $conn->prepare("UPDATE `users` SET address = ? WHERE id = ?");
   $update_address->execute([$address, $user_id]);

   $message[] = 'Your address was successfully changed.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Address</title>

   <!-- prompt message link -->
   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <button id="scrollToTopBtn" aria-label="Scroll to Top">&#9650;</button>

   
</head>
<body>

<?php include 'components/user_header.php' ?>

<section class="form-container">
   <br><br>   <br><br>
   <br><br>

   <form action="" method="post">
      <h3>Enter your Full Address</h3>
      <!-- Removed region and province fields -->
      <input type="text" class="box" placeholder="Enter City" required maxlength="50" name="city">
      <input type="text" class="box" placeholder="Enter Barangay" required maxlength="50" name="barangay">
      <input type="number" class="box" placeholder="Postal Code" required max="999999" min="0" maxlength="10" name="postal_code">
      <input type="text" class="box" placeholder="Street Name, Building, House No." required maxlength="50" name="area">
      <input type="submit" value="Save Address" name="submit" class="btn">
      <a href="checkout.php" class="btn">Back</a>
   </form>
</section>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<!-- SweetAlert script -->
<script>
   <?php
   if (isset($message) && !empty($message)) {
      echo 'swal({
               title: "Success!",
               text: "Your profile address was successfully updated.",
               icon: "success",
               button: "OK",
           }).then(function() {
               window.location.href = "checkout.php";
           });';
   }
   ?>
</script>

<div class="loader">
   <img src="images/loader.gif" alt="">
</div>

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