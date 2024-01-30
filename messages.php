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
   <title>Customer Feedbacks</title>


   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <button id="scrollToTopBtn" aria-label="Scroll to Top">&#9650;</button>

</head>
<body>

<style>
.heading{
   text-align: center;
   margin-bottom: 2rem;
   text-transform: capitalize;
   color: var(--white);
   font-size: 5rem;
   height: 5rem;
}

.messages .box-container{
   display: grid;
   grid-template-columns: repeat(auto-fit, 33rem);
   gap:1.5rem;
   justify-content: center;
   align-items: flex-start;
}

.messages .box-container .box{
   background-color: brown;
   border-radius: .5rem;
   box-shadow: var(--box-shadow);
   border:var(--border);
   padding:2rem;
   padding-top: 1rem;
}

.messages .box-container .box p{
   padding: .5rem 0;
   font-size: 1.8rem;
   color:var(--black);
   font-weight: bold;
}

.messages .box-container .box p span{
   color:black;
}

    h3 {
        font-size: 4rem ;
        color: white;
        text-align: center;
        margin-bottom: 15px; /* Adjust margin-bottom as needed */
    }

</style>

<?php include 'components/user_header.php'; ?>

<!-- messages section starts  -->

<section class="messages">


<h3>Customer's Feedbacks</h3>

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
   <p>Message: <span><?= $fetch_messages['message']; ?></span></p>
   <p>Rating: <span><?= $fetch_messages['rating']; ?></span></p>
</div>
   <?php
         }
      }else{
         echo '<p class="empty">No feedbacks yet</p>';
      }
   ?>

   </div>

</section>

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

<div class="loader">
   <img src="images/loader.gif" alt="">
</div>


</body>
</html>
