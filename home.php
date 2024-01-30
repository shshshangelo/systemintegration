<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
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
   <title>Home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<button id="scrollToTopBtn" aria-label="Scroll to Top">&#9650;</button>

<section class="hero">

   <div class="swiper hero-slider">

      <div class="swiper-wrapper">

         <div class="swiper-slide slide">
            <div class="content">
            <span>"Melted perfection on a crispy crust. Your pizza, your way, every slice tells a story."</span>
               <h3>Parisenne Sky</h3>
               <a href="menu.php" class="btn">Order Now</a>
            </div>
            <div class="image">
               <img src="images/home-img-1.png" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>"Bite into bliss. Our burgers are crafted with love, stacked with flavor, and served just the way you like."</span>
               <h3>Cluck Deluxe</h3>
               <a href="menu.php" class="btn">Order Now</a>
            </div>
            <div class="image">
               <img src="images/home-img-2.png" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>"Sip, Smile, Repeat. Dive into a world of refreshing goodness with our vibrant and delicious smoothies."</span>
               <h3>Berry Delight</h3>
               <a href="menu.php" class="btn">Order Now</a>
            </div>
            <div class="image">
               <img src="images/home-img-3.png" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>"Baked to perfection, it transforms into a slice of comfort that satisfies cravings and fuels gatherings."</span>
               <h3>Pizza Reine</h3>
               <a href="menu.php" class="btn">Order Now</a>
            </div>
            <div class="image">
               <img src="images/home-img-4.png" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>"Cupcakes are delightful, miniature treats that bring joy with every bite."</span>
               <h3>Cocoa Muffin</h3>
               <a href="menu.php" class="btn">Order Now</a>
            </div>
            <div class="image">
               <img src="images/home-img-5.png" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>"Bursting with natural sweetness and a hint of tanginess, it's a popular choice for a revitalizing start to the day or a refreshing pick-me-up. "</span>
               <h3>Orange Juice</h3>
               <a href="menu.php" class="btn">Order Now</a>
            </div>
            <div class="image">
               <img src="images/home-img-6.png" alt="">
            </div>
         </div>

      </div>

      <div class="swiper-pagination"></div>

   </div>


</section>

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
    font-size: 1rem; /* Adjust the content font size as needed */
}

.swal2-confirm {
    font-size: 100rem; /* Adjust the "OK" button font size as needed */
    padding: 10px 20px; /* Adjust the padding as needed */
}

</style>

<section class="category">

   <h1 class="title">Food Categories</h1>

   <div class="box-container">

      <a href="category.php?category=starter packs" class="box">
         <img src="images/cat-1.png" alt="">
         <h3>Starter Packs</h3>
      </a>

      <a href="category.php?category=main dishes" class="box">
         <img src="images/cat-2.png" alt="">
         <h3>Main Dishes</h3>
      </a>

      <a href="category.php?category=desserts" class="box">
         <img src="images/cat-4.png" alt="">
         <h3>Desserts</h3>
      </a>
	  
      <a href="category.php?category=drinks" class="box">
         <img src="images/cat-3.png" alt="">
         <h3>Drinks</h3>
      </a>

   </div>
</section>

<style>
.products .box {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease-in-out;
}

.products .box:hover {
    transform: scale(1.05); /* Adjust the scaling factor as needed */
}
</style>

<section class="products">
    <h1 class="title">Latest Menu</h1>
    <div class="box-container">
        <?php
        $select_products = $conn->prepare("SELECT * FROM `products` ORDER BY id DESC LIMIT 12");
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
                        <div class="price" id="price<?= $fetch_products['id']; ?>"><span>₱</span><?= $fetch_products['price']; ?></div>
                        <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
                    </div>
                </form>
        <?php
            }
        } else {
            echo '<p class="empty">No new menu added yet.</p>';
        }
        ?>
    </div>
    <div class="more-btn">
      <a href="menu.php" class="btn">View All Menu</a>
   </div>
   
</section>

<!-- custom js file link  -->
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>

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

    // Add event listener for the "Add to Cart" button
    var addToCartButtons = document.querySelectorAll('.fa-shopping-cart');

    addToCartButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            // Check if the user is logged in (you may need to adjust this condition based on your authentication logic)
            if ('<?= $user_id ?>' === '') {
                // User is not logged in, show SweetAlert message
                Swal.fire({
                    icon: 'warning',
                    title: 'Ops!',
                    text: 'Please log in or create an account to add items to your cart.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                    heightAuto: false, // Set to false to make the prompt bigger
                    weightAuto: false,
                }).then((result) => {
                    // Redirect to register.php after clicking OK
                    if (result.isConfirmed) {
                        window.location.href = 'register.php';
                    }
                });

                // Prevent the form submission
                event.preventDefault();
            }
        });
    });
});
</script>

<!-- Add this JavaScript at the end of your HTML body or in a separate script file -->
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

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/script.js"></script>

</script>

<div class="loader">
   <img src="images/loader.gif" alt="">

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

</div>

</body>
</html>