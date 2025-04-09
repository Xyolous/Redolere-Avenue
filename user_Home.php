<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Customer') {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home - Redolere Avenue</title>
    <link rel="stylesheet" href="user_Home.css">
</head>

<body>
    <nav>
        <div class="site_title">
            <p>Redolere Avenue</p>
        </div>
        <div class="buttons">
            <a href="user_Home.php">Home</a>
            <a href="user_OPage.php">Products</a>
            <a href="user_Cart.php">My Cart</a>
            <a href="user_OList.php">My Orders</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>


    <div class="carousel-background">
        <img src="images/carousel001.jpg" alt="Perfume 1">
        <img src="images/carousel002.jpg" alt="Perfume 2">
        <img src="images/carousel003.jpg" alt="Perfume 3">
        <img src="images/carousel004.jpg" alt="Perfume 4">
        <img src="images/carousel005.jpg" alt="Perfume 5">
        <img src="images/carousel006.jpg" alt="Perfume 6">
        <img src="images/carousel006.jpg" alt="Perfume 7">
    </div>
    <div class="home_overlay"></div>

    <div class="content">
        <div class="welcome">
            <h1>Welcome, <?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?>!</h1>
            <p>Shop the finest fragrances tailored just for you ✨</p>
        </div>
    </div>
</body>

</html>