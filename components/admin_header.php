<?php

// Check if the "View Sales" link is clicked
if (isset($_GET['action']) && $_GET['action'] == 'view_sales') {
    header("Location: sales.php");
    exit(); // Ensure that no other code is executed after the redirect
}
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<header class="header">
    <section class="flex">
        <a href="dashboard.php" class="logo">HeadChef | <span>Dashboard</span></a>
        <nav class="navbar">
        <a href="products.php">Add A New Menu</a>
            <a href="placed_orders.php">Customer Orders</a>
            <a href="completed_orders.php">Total Orders</a>
            <a href="cancelled_orders.php">Cancel Orders</a>
            <a href="admin_login.php" target="_blank">Management</a><!-- Add a query parameter to indicate the action -->
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
