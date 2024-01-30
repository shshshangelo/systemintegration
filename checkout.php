<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

if(isset($_POST['submit'])){
   $fname = $_POST['fname'];
   $fname = filter_var($fname, FILTER_SANITIZE_STRING);
   $mname = $_POST['mname'];
   $mname = filter_var($mname, FILTER_SANITIZE_STRING);
   $lname = $_POST['lname'];
   $lname = filter_var($lname, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){
      if($email == ''){
         $message[] = '';
      }else{
         $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, fname, mname, lname, number, email, address, method, total_products, total_price) VALUES(?,?,?,?,?,?,?,?,?,?)");
         $insert_order->execute([$user_id, $fname, $mname, $lname, $number, $email, $address, $method, $total_products, $total_price]);

         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart->execute([$user_id]);

         $message[] = 'Your order successfully placed. Please wait until your order is arrived. ';
      }
   }else{
      $message[] = 'Please wait until your order is arrived';
   }
}

$selectedPaymentMethod = isset($_GET['paymentMethod']) ? $_GET['paymentMethod'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <!-- header section starts  -->
   <?php include 'components/user_header.php'; ?>
   <!-- header section ends -->

   <button id="scrollToTopBtn" aria-label="Scroll to Top">&#9650;</button>


   <div class="heading">
      <h3>Checkout</h3>
      <p><a href="home.php">Home</a> <span> / Checkout</span></p>
   </div>

   <section class="checkout">
      <form action="" method="post">

         <div class="cart-items">
            <h3>My Orders</h3>
            <?php
               $grand_total = 0;
               $cart_items[] = '';
               $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
               $select_cart->execute([$user_id]);
               if($select_cart->rowCount() > 0){
                  while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                     $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
                     $total_products = implode($cart_items);
                     $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
            ?>
            <p><span class="name"><?= $fetch_cart['name']; ?></span><span class="price">₱<?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?></span></p>
            <?php
                  }
               }else{
                  echo '<p class="empty">Looks like your cart is empty. Please, Order now.</p>';
               }
            ?>
            <p class="grand-total"><span class="name">Total Due:</span><span class="price">₱<?= number_format($grand_total, 2); ?></span></p>
            <a href="cart.php" class="btn">View My Orders</a>
         </div>

         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
         <input type="hidden" name="fname" value="<?= $fetch_profile['fname'] ?>">
         <input type="hidden" name="mname" value="<?= $fetch_profile['mname'] ?>">
         <input type="hidden" name="lname" value="<?= $fetch_profile['lname'] ?>">
         <input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
         <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
         <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>">

         <div class="user-info">
            <h3>your info</h3>
            <p><i class="fas fa-user"></i><span><?= $fetch_profile['fname'] . ' ' . $fetch_profile['mname'] . ' ' . $fetch_profile['lname'] ?></span></p>
            <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number'] ?></span></p>
            <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?></span></p>
            <a href="update_profile.php" class="btn">update info</a>
            <h3>delivery address</h3>
            <p><i class="fas fa-map-marker-alt"></i><span><?php if($fetch_profile['address'] == ''){echo 'Enter your Full Address';}else{echo $fetch_profile['address'];} ?></span></p>
            <a href="update_address.php" class="btn">update address</a>
            <select name="method" id="paymentMethod" class="box" onchange="handlePaymentMethodChange()">
    <option value="" disabled selected>--Select Payment Method--</option>
    <option value="Cash on Delivery">Cash on Delivery</option>
    <option value="Card" <?php echo ($selectedPaymentMethod === 'Card') ? 'selected' : ''; ?>>Card</option>
    <option value="Gcash">Gcash</option>
    <option value="Maya">Maya</option>
</select>


<script>
        function handlePaymentMethodChange() {
            var paymentMethodSelect = document.getElementById('paymentMethod');
            if (paymentMethodSelect.value === 'Gcash' || paymentMethodSelect.value === 'Maya') {
                showQRCode(paymentMethodSelect.value);
            } else if (paymentMethodSelect.value === 'Card') {
                // Store the selected payment method in a session variable
                <?php $_SESSION['selected_payment_method'] = 'Card'; ?>
                // Redirect to the card payment page
                window.location.href = 'card_payment.php';
            }
        }


               function showQRCode(paymentMethod) {
                  var qrCodeImageSrc;
                  // Set the QR code image source based on the selected payment method
                  if (paymentMethod === 'Gcash') {
                     qrCodeImageSrc = 'images/generated_gcash_qr_code.png';
                  } else if (paymentMethod === 'Maya') {
                     qrCodeImageSrc = 'images/maya_qr_code.jpg'; // Replace with the actual path or URL for Maya
                  }

                  swal({
                     title: `${paymentMethod} QR Code`,
                     content: {
                        element: 'img',
                        attributes: {
                           src: qrCodeImageSrc,
                           alt: `${paymentMethod} QR Code`,
                           style: 'width: 100%; max-width: 300px;',
                        },
                     },
                     buttons: {
                        done: {
                           text: "Done",
                           value: "done",
                        },
                     },
                  }).then((value) => {
                     // Handle button click if needed
                     if (value === "done") {
                        // Do something when the "Done" button is clicked
                        console.log("Done button clicked");
                     }
                  });
               }
            </script>

            </select>
            <input type="submit" value="place order" class="btn <?php if($fetch_profile['address'] == ''){echo 'disabled';} ?>" style="width:100%; background:var(--red); color:var(--white);" name="submit">
         </div>

      </form>
   </section>

   <?php
   if(isset($message)){
      foreach($message as $message){
         echo '
            <script>
               swal({
                  title: "Thank you",
                  text: "'.$message.'",
                  icon: "success",
                  button: "Close",
               }).then(function() {
                  window.location.href = "orders.php";
               });
            </script>
         ';
      }
   }
   ?>

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
