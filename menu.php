<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Menu Lists</title>

   <!-- sweetalert message -->
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

<div class="heading">
   <h3>Our Menu</h3>
   <p><a href="home.php">Home</a> <span> / Menu Lists</span></p>
</div>

<button id="scrollToTopBtn" aria-label="Scroll to Top">&#9650;</button>

<style>
/* Add this CSS to your stylesheet or within a <style> tag in your HTML file */
.swal2-popup {
    width: 500px; /* Adjust the width as needed */
    height: 300px; /* Adjust the height as needed */
}

.swal2-title {
    font-size: 4rem; /* Adjust the title font size as needed */
}

.swal2-content {
    font-size: 5rem; /* Adjust the content font size as needed */
}

.swal2-confirm {
    font-size: 100rem; /* Adjust the "OK" button font size as needed */
    padding: 10px 20px; /* Adjust the padding as needed */
}

   /* Add this CSS to your existing stylesheet or within a <style> tag in your HTML file */

.products .box {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease-in-out;
}

.products .box:hover {
    transform: scale(1.05); /* Adjust the scaling factor as needed */
}
</style>

<!-- menu section starts  -->

<section class="products">
   <div class="box-container">

                  <?php
            $select_products = $conn->prepare("SELECT * FROM `products` ORDER BY id ASC LIMIT 50");
            $select_products->execute();

            if ($select_products->rowCount() > 0) {
                while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="flex">
            <div class="price"><span>₱</span><?= $fetch_products['price']; ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2"">
         </div>
      </form>
      <?php
            }
         }else{
            echo '<p class="empty">No new dishes added yet.</p>';
         }
      ?>

   </div>

</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var quantityInputs = document.querySelectorAll('.qty');

    quantityInputs.forEach(function (input) {
        input.addEventListener('input', function () {
            var quantity = this.value;
            var unitPrice = parseFloat(this.closest('.box').querySelector('[name="price"]').value);
            var totalPrice = quantity * unitPrice;
            var priceElement = this.closest('.box').querySelector('.price');
            priceElement.innerHTML = '<span>₱</span>' + totalPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        });
    });
});

</script>

<!-- custom js file link  -->
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="path/to/sweetalert2.min.js"></script>



<!-- Add this JavaScript at the end of your HTML body or in a separate script file -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    var addToCartButtons = document.querySelectorAll('.fa-shopping-cart');

    addToCartButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            console.log('Add to Cart button clicked');
            if ('<?= $user_id ?>' === '') {
    Swal.fire({
        icon: 'warning',
        title: 'You need to log in!',
        text: 'Please log in or create an account to add items to your cart.',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
        heightAuto: true,
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'register.php';
        }
    });

    event.preventDefault();
}

        });
    });
});
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


<div class="loader">
   <img src="images/loader.gif" alt="">
</div>

</body>
</html>