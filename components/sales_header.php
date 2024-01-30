<?php

?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<header class="header">

   <section class="flex">

      <a href="sales.php" class="logo">Management | <span>Dashboard</span></a>

      <nav class="navbar">
       <a href="pending.php">Pending Orders</a>
       <a href="complete.php">Completed Orders</a>
       <a href="cancel.php">Cancelled Orders</a>
       <a href="messages.php">Feedbacks</a>
       <a href="users_accounts.php">Users</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile['name']; ?></p>
         <div class="flex-btn"></div>
            <a href="#" class="delete-btn" onclick="confirmLogout()">logout</a>

<!-- Include SweetAlert library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Add custom styles for SweetAlert modal */
    .swal2-popup {
        font-size: 1.6rem; /* Adjust the font size as needed */
    }
</style>

<script>
function confirmLogout() {
    Swal.fire({
        title: "Logout",
        text: "Are you sure you want to logout from this website?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, logout!",
        customClass: {
            popup: 'custom-swal-popup', // Add a custom class for more specific styling
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // If the user clicks "Yes, logout!", redirect to the logout script
            window.location.href = "../components/admin_logout.php";
        }
    });
}
</script>

</header>