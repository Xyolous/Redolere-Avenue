<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Customer') {
    header("Location: index.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Delete cart item (via POST)
if (isset($_POST['delete'])) {
    $id = $_POST['cart_id'];
    $conn->query("DELETE FROM cart WHERE cart_id = $id AND user_id = $user_id");
    echo "<script>alert('Item removed!'); window.location='user_Cart.php';</script>";
}

// ➕➖ Update quantity (via POST)
if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $new_qty = $_POST['new_qty'];
    if ($new_qty > 0) {
        $conn->query("UPDATE cart SET quantity = $new_qty WHERE cart_id = $cart_id AND user_id = $user_id");
    }
    header("Location: user_Cart.php");
    exit();
}

// Place Order
if (isset($_POST['order_all'])) {
    $cartItems = $conn->query("SELECT c.*, p.perfume_price FROM cart c 
        JOIN perfumes p ON c.perfume_id = p.perfume_id 
        WHERE c.user_id = $user_id");

    while ($row = $cartItems->fetch_assoc()) {
        $perfume_id = $row['perfume_id'];
        $quantity = $row['quantity'];
        $price = $row['perfume_price'];
        $total = $price * $quantity;

        // Insert into orders with ordered_at timestamp
        $conn->query("INSERT INTO orders (user_id, perfume_id, quantity, total_price, ordered_at) 
            VALUES ($user_id, $perfume_id, $quantity, $total, NOW())");

        // Decrease perfume_stock
        $conn->query("UPDATE perfumes SET perfume_stock = perfume_stock - $quantity WHERE perfume_id = $perfume_id");
    }

    // Clear cart
    $conn->query("DELETE FROM cart WHERE user_id = $user_id");

    echo "<script>alert('Order placed!'); window.location='user_OList.php';</script>";
    exit();
}


// Fetch cart items
$cart = $conn->query("SELECT c.cart_id, c.quantity, p.perfume_name, p.perfume_brand, p.perfume_price, p.perfume_id, p.perfume_stock, p.perfume_scent_profile 
    FROM cart c JOIN perfumes p ON c.perfume_id = p.perfume_id 
    WHERE c.user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - Redolere Avenue</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function updateQuantity(cartId, newQty) {
            const form = document.getElementById('updateForm');
            form.cart_id.value = cartId;
            form.new_qty.value = newQty;
            form.submit();
        }

        function deleteItem(cartId) {
            const form = document.getElementById('deleteForm');
            form.cart_id.value = cartId;
            form.submit();
        }
    </script>
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

    <h2>🛒 My Cart</h2>
    <div class="perfume-container">
        <?php if ($cart->num_rows > 0): ?>
            <form method="POST" id="updateForm">
                <input type="hidden" name="cart_id">
                <input type="hidden" name="new_qty">
                <input type="hidden" name="update_qty">
            </form>

            <form method="POST" id="deleteForm">
                <input type="hidden" name="cart_id">
                <input type="hidden" name="delete">
            </form>
            <div class="order_item_container">
                <div class="perfume_details">
                    <form method="POST">
                        <?php while ($item = $cart->fetch_assoc()): ?>

                            <h3><?= $item['perfume_name'] ?> - <?= $item['perfume_brand'] ?></h3>
                            <p><strong>Price:</strong> ₱<?= number_format($item['perfume_price'], 2) ?></p>
                            <p><strong>Stock:</strong> <?= $item['perfume_stock'] ?> available</p>
                            <p><strong>Total:</strong> ₱<?= number_format($item['perfume_price'] * $item['quantity'], 2) ?></p>
                            <p><strong>Scent Profile:</strong><?= $item['perfume_scent_profile'] ?></p>

                            <div class="quantity-control">
                                <div>
                                    <a href="javascript:void(0);" class="qty-btn"
                                        onclick="updateQuantity(<?= $item['cart_id'] ?>, <?= $item['quantity'] - 1 ?>)"
                                        <?= $item['quantity'] <= 1 ? 'style="pointer-events:none;opacity:0.5;"' : '' ?>>➖</a>
                                    <?= $item['quantity'] ?>
                                    <a href="javascript:void(0);" class="qty-btn"
                                        onclick="updateQuantity(<?= $item['cart_id'] ?>, <?= $item['quantity'] + 1 ?>)"
                                        <?= $item['quantity'] >= $item['perfume_stock'] ?>>➕</a>
                                </div>

                                <button type="button" class="cancel-btn" onclick="if (confirm('Cancel this item?')) deleteItem(<?= $item['cart_id'] ?>)">
                                    Cancel
                                </button>
                            </div>
                </div>
                <div class="perfume_image">
                    <img class="image" src="images\<?= $item['perfume_name'] ?>.jpg" alt="Perfume Image">
                </div>
            </div>
        <?php endwhile; ?>

        <div>
            <button type="submit" name="order_all" onclick="return confirm('Place this order?')">Order All</button>
        </div>
        </form>
    <?php else: ?>
        <p>No Orders Yet</p>
    <?php endif; ?>
    </div>

</body>

</html>