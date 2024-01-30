<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
}

// Fetch user profile information
$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$user_id]);

// Check if the user profile exists
if ($select_profile->rowCount() > 0) {
    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
} else {
    // Redirect or handle the case where the profile does not exist
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
    <title>My Profile</title>

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
        <h3>Profile</h3>
        <p><a href="home.php">Home</a> <span> / My Profile Information</span></p>
    </div>

    <section class="user-details">

        <div class="user">
            <img src="images/customer.png" alt="">
            <p><i class="fas fa-user"></i><span><span><?= $fetch_profile['fname'] . ' ' . $fetch_profile['mname'] . ' ' . $fetch_profile['lname']; ?></span></span></p>
            <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number']; ?></span></p>
            <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email']; ?></span></p>
            <a href="update_profile.php" class="btn">Update Profile</a>
            <p class="address"><i class="fas fa-map-marker-alt"></i><span><?php if ($fetch_profile['address'] == '') {echo 'Enter your Full Address';} else {echo $fetch_profile['address'];} ?></span></p>
            <a href="update_address.php" class="btn">Update Address</a>
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
