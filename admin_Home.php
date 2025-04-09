<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home - Redolere Avenue</title>

</head>

<body>
    <div class="sidebar">
        <a href="admin_PList.php">Manage Products</a>
        <a href="admin_OList.php">Customer Orders</a>
        <a href="index.php">Logout</a>
    </div>
    <h1>Welcome, <?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?>!</h1>
</body>

</html>