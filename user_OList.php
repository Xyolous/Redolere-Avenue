<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Customer') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// mark order as "Received"
if (isset($_POST['received'])) {
    $order_id = $_POST['received'];

    // update the user's order status to 'Completed'
    $conn->query("UPDATE orders SET status = 'Completed' WHERE order_id = $order_id AND user_id = $user_id");

    // notify the user and redirect
    echo "<script>alert('Order marked as received!'); window.location='user_OList.php';</script>";
    exit();
}

// Fetch orders and group them by status with respective sorting
$orders_pending = $conn->query("SELECT o.*, p.perfume_name, p.perfume_price, p.perfume_scent_profile FROM orders o 
    JOIN perfumes p ON o.perfume_id = p.perfume_id 
    WHERE o.user_id = $user_id AND o.status = 'Pending' ORDER BY o.ordered_at DESC");

$orders_in_transit = $conn->query("SELECT o.*, p.perfume_name, p.perfume_price, p.perfume_scent_profile FROM orders o 
    JOIN perfumes p ON o.perfume_id = p.perfume_id 
    WHERE o.user_id = $user_id AND o.status = 'In Transit' ORDER BY o.ordered_at DESC");

$orders_completed = $conn->query("SELECT o.*, p.perfume_name, p.perfume_price, p.perfume_scent_profile FROM orders o 
    JOIN perfumes p ON o.perfume_id = p.perfume_id 
    WHERE o.user_id = $user_id AND o.status = 'Completed' ORDER BY o.completed_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Redolere Avenue</title>
    <link rel="stylesheet" href="style.css">
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

    <h2>✨ My Orders ✨</h2>
    <div class="perfume-container">
        <!-- Pending Orders -->
        <h3>Pending Orders</h3>
        <?php if ($orders_pending->num_rows > 0): ?>
            <?php while ($order = $orders_pending->fetch_assoc()): ?>
                <div class="order_item_container">
                    <div class="perfume_details">
                        <h3><?= $order['perfume_name'] ?></h3>
                        <p><strong>Price:</strong> ₱<?= number_format($order['perfume_price'], 2) ?></p>
                        <p><strong>Quantity:</strong> <?= $order['quantity'] ?></p>
                        <p><strong>Total Price:</strong> ₱<?= number_format($order['total_price'], 2) ?></p>
                        <p><strong>Scent Profile:</strong><?= $order['perfume_scent_profile'] ?></p>
                        <p><strong>Status:</strong>
                            <span class="status Pending"><?= $order['status'] ?></span>
                        </p>
                        <!-- Display the ordered_at date -->
                        <p><strong>Ordered At:</strong> <?= date('F j, Y, g:i a', strtotime($order['ordered_at'])) ?></p>

                        <div class="order-action">
                            <button class="received-btn" hidden>Completed</button>
                        </div>
                    </div>
                    <div class="perfume_image">
                        <img class="image" src="images/<?= $order['perfume_name'] ?>.jpg" alt="Perfume Image">
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No Pending Orders</p>
        <?php endif; ?>

        <!-- In Transit Orders -->
        <h3>In Transit Orders</h3>
        <?php if ($orders_in_transit->num_rows > 0): ?>
            <?php while ($order = $orders_in_transit->fetch_assoc()): ?>
                <div class="order_item_container">
                    <div class="perfume_details">
                        <h3><?= $order['perfume_name'] ?></h3>
                        <p><strong>Price:</strong> ₱<?= number_format($order['perfume_price'], 2) ?></p>
                        <p><strong>Quantity:</strong> <?= $order['quantity'] ?></p>
                        <p><strong>Total Price:</strong> ₱<?= number_format($order['total_price'], 2) ?></p>
                        <p><strong>Scent Profile:</strong><?= $order['perfume_scent_profile'] ?></p>
                        <p><strong>Status:</strong>
                            <span class="status in-transit"><?= $order['status'] ?></span>
                        </p>
                        <!-- Display the ordered_at date -->
                        <p><strong>Ordered At:</strong> <?= date('F j, Y, g:i a', strtotime($order['ordered_at'])) ?></p>

                        <div class="order-action">
                            <!-- Form for marking order as completed -->
                            <form action="user_OList.php" method="POST" onsubmit="return confirm('Are you sure you want to mark this order as completed?');">
                                <input type="hidden" name="received" value="<?= $order['order_id'] ?>">
                                <button class="received-btn" type="submit">Completed</button>
                            </form>
                        </div>
                    </div>
                    <div class="perfume_image">
                        <img class="image" src="images/<?= $order['perfume_name'] ?>.jpg" alt="Perfume Image">
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No Orders In Transit</p>
        <?php endif; ?>

        <!-- Completed Orders -->
        <h3>Completed Orders</h3>
        <?php if ($orders_completed->num_rows > 0): ?>
            <?php while ($order = $orders_completed->fetch_assoc()): ?>
                <div class="order_item_container">
                    <div class="perfume_details">
                        <h3><?= $order['perfume_name'] ?></h3>
                        <p><strong>Price:</strong> ₱<?= number_format($order['perfume_price'], 2) ?></p>
                        <p><strong>Quantity:</strong> <?= $order['quantity'] ?></p>
                        <p><strong>Total Price:</strong> ₱<?= number_format($order['total_price'], 2) ?></p>
                        <p><strong>Scent Profile:</strong><?= $order['perfume_scent_profile'] ?></p>
                        <p><strong>Status:</strong>
                            <span class="status-completed"><?= $order['status'] ?></span>
                        </p>
                        <!-- Display the ordered_at date -->
                        <p><strong>Ordered At:</strong> <?= date('F j, Y, g:i a', strtotime($order['ordered_at'])) ?></p>
                        <!-- Display the completed_at date -->
                        <p><strong>Completed At:</strong> <?= date('F j, Y, g:i a', strtotime($order['completed_at'])) ?></p>

                        <div class="order-action">
                            <button class="received-btn" hidden>Completed</button>
                        </div>
                    </div>
                    <div class="perfume_image">
                        <img class="image" src="images/<?= $order['perfume_name'] ?>.jpg" alt="Perfume Image">
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No Completed Orders</p>
        <?php endif; ?>

    </div>

</body>

</html>