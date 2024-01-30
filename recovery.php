<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
 } else {
    $user_id = '';
 }

 // Function to validate the password and return a boolean
function validatePassword($password) {
    // Password must be at least 8 characters long
    // Should include at least one uppercase letter, one lowercase letter, one number, and one special character
    return (
        strlen($password) >= 8 &&
        preg_match('/[A-Z]/', $password) &&
        preg_match('/[a-z]/', $password) &&
        preg_match('/[0-9]/', $password) &&
        preg_match('/[^A-Za-z0-9]/', $password)
    );
}


if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message[] = 'Invalid email format';
    } else {
        // Validate passwords
        if ($newPassword != $confirmPassword) {
            $message[] = 'Confirm password is not matched.';
        } elseif (!validatePassword($newPassword)) {
            $message[] = 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.';
        } else {
            // Hash the new password using password_hash
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the user's password in the database
            $update_password = $conn->prepare("UPDATE `users` SET password = ? WHERE email = ?");
            $update_password->execute([$hashedPassword, $email]);

            if ($update_password->rowCount() > 0) {
                // Password updated successfully
                $successMessage = 'Password updated successfully. You can now log in with your new password.';
            } else {
                $message[] = 'Email not found.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Password Recovery</title>

   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <button id="scrollToTopBtn" aria-label="Scroll to Top">&#9650;</button>

   
   <style>
      .password-container {
         position: relative;
      }

      .eye-icon {
         position: absolute;
         top: 50%;
         right: 10px;
         transform: translateY(-50%);
         cursor: pointer;
         font-size: 20px; /* Adjust the size as needed */
         color: black; /* Adjust the color as needed */
      }
   </style>
   
</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->
<br><br><br><br><br>
<br><br>

<section class="form-container">
   <form action="" method="post">
      <h3>Password Recovery</h3>
      <input type="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required placeholder="Email Address" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <div class="password-container">
      <input type="password" name="new_password" required placeholder="New Password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <span class="eye-icon" onclick="togglePassword('new_password')">
         <i class="fas fa-eye" id="eye-icon-new_password"></i>
      </span>
   </div>
   <div class="password-container">
      <input type="password" name="confirm_password" required placeholder="Confirm Password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <span class="eye-icon" onclick="togglePassword('confirm_password')">
         <i class="fas fa-eye" id="eye-icon-confirm_password"></i>
      </span>
   </div>

   <input type="submit" value="Submit" name="submit" class="btn">
   <a href="login.php" class="btn back-btn">Back</a>
</form>

<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
         <script>
            swal({
               title: "Error",
               text: "'.$message.'",
               icon: "warning",
               button: "Close",
            });
         </script>
      ';
   }
}

if (isset($successMessage)) {
   echo '
      <script>
         swal({
            title: "Success",
            text: "'.$successMessage.'",
            icon: "success",
            button: "Close",
         }).then(() => {
            window.location.href = "login.php";
         });
      </script>
   ';
}
?>

<div class="loader">
   <img src="images/loader.gif" alt="">
</div>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>
   function togglePassword(inputId) {
      var passwordInput = document.getElementsByName(inputId)[0];
      var eyeIcon = document.getElementById('eye-icon-' + inputId);

      if (passwordInput.type === "password") {
         passwordInput.type = "text";
         eyeIcon.classList.remove('fa-eye');
         eyeIcon.classList.add('fa-eye-slash');
      } else {
         passwordInput.type = "password";
         eyeIcon.classList.remove('fa-eye-slash');
         eyeIcon.classList.add('fa-eye');
      }
   }
</script>

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
