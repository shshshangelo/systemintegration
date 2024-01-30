<?php

include '../components/connect.php';

session_start();

if (isset($_POST['submit'])) {

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $pass = sha1(filter_var($_POST['pass'], FILTER_SANITIZE_STRING));

    // Check if the user is an admin
    $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ? AND password = ?");
    $select_admin->execute([$name, $pass]);

    if ($select_admin->rowCount() > 0) {
        $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

        // Check if the user is a Management based on username pattern
        if (strpos($fetch_admin['name'], 'Management') === 0) {
            // Salesperson found, redirect to sales dashboard
            $_SESSION['admin_id'] = $fetch_admin['id'];
            header('location:sales.php');
            exit();
        } else {
            // HeadChef found, redirect to Headchef dashboard
            $_SESSION['admin_id'] = $fetch_admin['id'];
            header('location:dashboard.php');
            exit();
        }
    } else {
        $message[] = 'Incorrect username or password!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">
    
</head>

<body>

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


    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '
         <script>
            swal({
               title: "Please try again",
               text: "' . $message . '",
               icon: "warning",
               button: "Close",
            });
         </script>
      ';
        }
    }
    ?>


<!-- admin login form section starts  -->
<section class="form-container">
    <form action="" method="POST">
        <h3>Sign in</h3>
        <div class="input-container">
            <input type="text" name="name" maxlength="20" required placeholder="Username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>
        <div class="input-container password-container">
            <input type="password" name="pass" maxlength="20" required placeholder="Password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <span class="eye-icon" onclick="togglePassword('pass')">
                <i class="fas fa-eye" id="eye-icon-pass"></i>
            </span>
        </div>
        <input type="submit" value="Sign in" name="submit" class="btn">
    </form>
</section>
<!-- admin login form section ends -->

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

</body>

</html>

