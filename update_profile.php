<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
}

// Array to store messages
$message = array();

if (isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $fname = filter_var($fname, FILTER_SANITIZE_STRING);

    $mname = $_POST['mname'];
    $mname = filter_var($mname, FILTER_SANITIZE_STRING);

    $lname = $_POST['lname'];
    $lname = filter_var($lname, FILTER_SANITIZE_STRING);

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);

    if (!empty($fname)) {
        $update_fname = $conn->prepare("UPDATE `users` SET fname = ? WHERE id = ?");
        $update_fname->execute([$fname, $user_id]);
        $message[] = 'First name updated successfully!';
    }

    if (!empty($mname)) {
        $update_mname = $conn->prepare("UPDATE `users` SET mname = ? WHERE id = ?");
        $update_mname->execute([$mname, $user_id]);
        $message[] = 'Middle name updated successfully!';
    }

    if (!empty($lname)) {
        $update_lname = $conn->prepare("UPDATE `users` SET lname = ? WHERE id = ?");
        $update_lname->execute([$lname, $user_id]);
        $message[] = 'Last name updated successfully!';
    }

    if (!empty($email)) {
        $select_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $select_email->execute([$email]);
        if ($select_email->rowCount() > 0) {
            $message[] = 'Email already taken!';
        } else {
            $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
            $update_email->execute([$email, $user_id]);
            $message[] = 'Email updated successfully!';
        }
    }

    if (!empty($number)) {
        $select_number = $conn->prepare("SELECT * FROM `users` WHERE number = ?");
        $select_number->execute([$number]);
        if ($select_number->rowCount() > 0) {
            $message[] = 'Number already taken!';
        } else {
            $update_number = $conn->prepare("UPDATE `users` SET number = ? WHERE id = ?");
            $update_number->execute([$number, $user_id]);
        }
    }
    
    $message[] = 'Profile updated successfully!';
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
    <title>Update Profile</title>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!-- font awesome cdn link  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

<!-- custom css file link  -->
<link rel="stylesheet" href="css/style.css">

<button id="scrollToTopBtn" aria-label="Scroll to Top">&#9650;</button>


<body>

    <!-- header section starts  -->
    <?php include 'components/user_header.php'; ?>
    <!-- header section ends -->
    
    <section class="form-container update-form">
        <form action="" method="post">
            <h3>Update Profile</h3>
            <input type="text" name="fname" placeholder="First Name" class="box" maxlength="50" value="<?= $fetch_profile['fname']; ?>">
            <input type="text" name="mname" placeholder="Middle Name" class="box" maxlength="50" value="<?= $fetch_profile['mname']; ?>">
            <input type="text" name="lname" placeholder="Last Name" class="box" maxlength="50" value="<?= $fetch_profile['lname']; ?>">
            <input type="email" name="email" placeholder="Email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" value="<?= $fetch_profile['email']; ?>">
            <input type="number" name="number" placeholder="Phone Number" class="box" min="0" max="9999999999" maxlength="11" value="<?= $fetch_profile['number']; ?>">  
            <input type="submit" value="Update Now" name="submit" class="btn">
            <a href="update_password.php" class="btn">Change Password</a>
            <a href="profile.php" class="btn back-btn">Back</a>
        </form>
    </section>

    <?php
if(isset($message)){
   foreach($message as $message){
      echo '
         <script>
            swal({
               title: "Updated Info",
               text: "'.$message.'",
               icon: "success",
               button: "Okay",
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
