<?php
// Include database connection and start session
include 'components/connect.php';


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
   $fname = $_POST['fname'];
   $mname = $_POST['mname'];
   $lname = $_POST['lname'];
   $email = $_POST['email'];
   $number = $_POST['number'];
   $password = $_POST['pass'];
   $confirmPassword = $_POST['cpass'];

   // Validate password
   if ($password != $confirmPassword) {
       $message[] = 'Password is not matched.';
   } elseif (!validatePassword($password)) {
       $message[] = 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.';
      } elseif (!validatePhoneNumber($number)) {
         $message[] = 'Phone number must have exactly 11 digits.';
      } else {
       // Hash the password using password_hash
       $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

       $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
       $select_user->execute([$email, $number]);
       $row = $select_user->fetch(PDO::FETCH_ASSOC);

       if ($select_user->rowCount() > 0) {
           $message[] = 'This email or number is already existed.';
       } else {
           $insert_user = $conn->prepare("INSERT INTO `users`(fname, mname, lname, email, number, password) VALUES(?,?,?,?,?,?)");
           $insert_user->execute([$fname, $mname, $lname, $email, $number, $hashedPassword]);

           $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
           $select_user->execute([$email, $hashedPassword]);
           $row = $select_user->fetch(PDO::FETCH_ASSOC);

           if ($select_user->rowCount() > 0) {
               $_SESSION['user_id'] = $row['id'];

               // Add SweetAlert for successful account creation
               $successMessage = 'Successfully created an account.';
           }
       }
   }
}
function validatePhoneNumber($phoneNumber) {
   // Remove non-numeric characters
   $numericPhoneNumber = preg_replace('/\D/', '', $phoneNumber);
   
   // Check if the resulting numeric string has exactly 11 digits
   return strlen($numericPhoneNumber) === 11;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sign up</title>

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
         font-size: 20px; 
         color: black; 
      }
      
      .highlight {
         border: 2px solid red;
      }
      
   </style>

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="form-container">

   <form action="" method="post">
      <br>
   <h3>Sign up</h3>
   <input type="text" name="fname" value="<?php echo isset($fname) ? $fname : ''; ?>" required placeholder="First Name" class="box" maxlength="50">
   <input type="text" name="mname" value="<?php echo isset($mname) ? $mname : ''; ?>" required placeholder="Middle Name" class="box" maxlength="50">
<input type="text" name="lname" value="<?php echo isset($lname) ? $lname : ''; ?>" required placeholder="Last Name" class="box" maxlength="50">
<input type="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required placeholder="Email Address" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
<input type="number" name="number" value="<?php echo isset($number) ? $number : ''; ?>" required placeholder="Phone Number" class="box" oninput="if(this.value.length > 11) this.value = this.value.slice(0, 11);">

<script>
function validatePhoneNumber(input) {
    var phoneNumber = input.value.replace(/\D/g, ''); // Remove non-numeric characters
    var requiredDigits = 11; // Required number of digits

    if (phoneNumber.length !== requiredDigits) {
        // Highlight the input or provide feedback to the user
        input.classList.add('highlight');
    } else {
        // Remove the highlight if the input is valid
        input.classList.remove('highlight');
    }
}
</script>

<style>
/* Add this style for highlighting */
.highlight {
    border: 2px solid red;
}
</style>


      <div class="password-container">
         <input type="password" name="pass" required placeholder="Password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <span class="eye-icon" onclick="togglePassword('pass')">
            <i class="fas fa-eye" id="eye-icon-pass"></i>
         </span>
      </div>
      <div class="password-container">
         <input type="password" name="cpass" required placeholder="Confirm Password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <span class="eye-icon" onclick="togglePassword('cpass')">
            <i class="fas fa-eye" id="eye-icon-cpass"></i>
         </span>
      </div>
      <input type="submit" value="Register" name="submit" class="btn">
      <p>Already have an account? <a href="login.php" style="text-decoration: underline;">Log in</a></p>
   </form>

</section>

<?php
if(isset($message)){
   foreach($message as $message){
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

if(isset($successMessage)){
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
    var form = document.getElementById('myForm');

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
        form.classList.add('highlight'); // Add class to highlight form
    } else {
        passwordInput.type = "password";
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
        form.classList.remove('highlight'); // Remove class to unhighlight form
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
