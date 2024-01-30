<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About Us</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <button id="scrollToTopBtn" aria-label="Scroll to Top">&#9650;</button>


</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>About Us</h3>
   <p><a href="home.php">Home</a> <span> / About Us</span></p>
</div>

<!-- about section starts  -->

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/about-img.svg" alt="">
      </div>

      <div class="content">
         <h3>Why FlavorFussion Eats?</h3>
         <p>Our online food ordering system seamlessly connects you to a world of flavors. Savor the convenience, relish the choices, and let your cravings be the guide. Welcome to a feast of simplicity – where every click brings you closer to a delightful dining experience. Order, eat, repeat!"</p>
         <a href="menu.php" class="btn">Our Menu</a>
      </div>

   </div>

</section>

<!-- about section ends -->

<!-- steps section starts  -->


<h1 class="title">About Us</h1>

<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<section class="steps">

   <h1 class="title">simple steps to order</h1>

   <div class="box-container">

      <div class="box">
      <img src="images/step-1.png" alt="">

         <h3>Choose Menu</h3>
         <p>"Explore a culinary journey where every dish tells a story, and every bite is a moment to savor."</p>
      </div>

      <div class="box">
      <img src="images/step-2.png" alt="">

         <h3>Fast Delivery</h3>
         <p>"From our kitchen to your doorstep in a heartbeat. Swift, reliable, and deliciously quick."</p>
      </div>

      <div class="box">
      <img src="images/step-3.png" alt="">

         <h3>Enjoy</h3>
         <p>"Sit back, relax, and savor the moment. Your favorite flavors delivered, just as you imagined. Bon appétit!"</p>
      </div>

   </div>

</section>

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

<!-- custom js file link  -->
<script src="js/script.js"></script>


</body>
</html>