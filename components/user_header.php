
<header class="header">
        <section class="flex">
            <a href="home.php" class="logo">FlavorFussion Eats</a>
            <nav class="navbar">
            <a href="about.php">About Us</a>
            <a href="messages.php">Feedbacks</a>
            <a href="menu.php">Menu Lists</a>


                <?php
                // Check if the user is logged in
                if (isset($_SESSION['user_id'])) {
                    // If logged in, show the "Orders" link
                    echo '<a href="orders.php">Orders</a>';
                    echo '<a href="cancelled_orders.php">Cancelled </a>';
                    echo '<a href="history.php">History</a>';
                    echo '<a href="contact.php">To Rate </a>';


                }

                ?>
            </nav>


<style>
/* Add this CSS to your stylesheet or in a style tag within the head of your HTML */
.header .navbar {
    display: flex;
    justify-content: space-between;
}

.header .navbar a[href="search.php"] {
    order: 2; /* Adjust the order to ensure it's the last item */
}

</style>

  <div class="icons">
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
         <a href="search.php"><i class="fas fa-search"></i></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
<p class="name"><?= $fetch_profile['fname'] . ' ' . $fetch_profile['mname'] . ' ' . $fetch_profile['lname']; ?></p>
         <div class="flex">
            <a href="profile.php" class="btn">My Profile</a>
            <a href="components/user_logout.php" class="delete-btn">Sign out</a>
         </div>
         <?php
            }else{
         ?>
            <p class="name">Get started.</p>
            <a href="register.php" class="btn">Sign up</a>  
            <a href="login.php" class="btn">Log in</a>            
          
         <?php
          }
         ?>
      </div>

   </section>

</header>
