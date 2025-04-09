<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header("Location: index.html");
    exit();
}

// Release Order (Set status to 'In Transit')
if (isset($_GET['release'])) {
    $order_id = $_GET['release'];
    // Update the order status to 'In Transit'
    $conn->query("UPDATE orders SET status = 'In Transit' WHERE order_id = $order_id");

    // Notify admin and redirect to the same page
    echo "<script>alert('Order status updated to In Transit'); window.location='admin_OList.php';</script>";
    exit();
}

// Fetch all orders (including customer orders and their status)
$orders = $conn->query("SELECT o.*, p.perfume_name, p.perfume_price, u.first_name, u.last_name FROM orders o 
    JOIN perfumes p ON o.perfume_id = p.perfume_id
    JOIN users u ON o.user_id = u.user_id");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Orders - Redolere Avenue</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav>
        <div class="site_title">
            <p>Redolere Avenue</p>
        </div>
        <div class="buttons">
            <a href="user_OPage.php">Products</a>
            <a href="user_Cart.php">My Cart</a>
            <a href="user_OList.php">My Orders</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>
    <h2>Customer Orders</h2>
    <a href="admin_Home.php">← Back to Home</a>

    <?php if ($orders->num_rows > 0): ?>
        <table>
            <tr>
                <th>Customer</th>
                <th>Perfume</th>
                <th>Price (₱)</th>
                <th>Quantity</th>
                <th>Total (₱)</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($order = $orders->fetch_assoc()): ?>
                <tr>
                    <td><?= $order['first_name'] . " " . $order['last_name'] ?></td>
                    <td><?= $order['perfume_name'] ?></td>
                    <td><?= number_format($order['perfume_price'], 2) ?></td>
                    <td><?= $order['quantity'] ?></td>
                    <td><?= number_format($order['total_price'], 2) ?></td>
                    <td>
                        <span class="status <?php
                                            if ($order['status'] == 'Pending') {
                                                echo 'pending';
                                            } elseif ($order['status'] == 'In Transit') {
                                                echo 'in-transit';
                                            } else {
                                                echo 'completed';
                                            }
                                            ?>"><?= $order['status'] ?></span>
                    </td>
                    <td>
                        <?php if ($order['status'] == 'Pending'): ?>
                            <a href="admin_OList.php?release=<?= $order['order_id'] ?>" class="release-btn">Release</a>
                        <?php elseif ($order['status'] == 'In Transit'): ?>
                            <button class="release-btn" disabled>Released</button>
                        <?php else: ?>
                            <button class="release-btn" disabled>Completed</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center; font-size: 1.2em;">No Orders Yet</p>
    <?php endif; ?>

</body>

</html>