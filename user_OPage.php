<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Customer') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Add to Cart Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $perfume_id = $_POST['perfume_id'];
    $quantity = $_POST['quantity'];

    // Check perfume_stock
    $check = $conn->prepare("SELECT perfume_stock FROM perfumes WHERE perfume_id = ?");
    $check->bind_param("i", $perfume_id);
    $check->execute();
    $check->bind_result($perfume_stock);
    $check->fetch();
    $check->close();

    if ($perfume_stock >= $quantity && $quantity > 0) {
        // Check if item is already in cart
        $exists = $conn->prepare("SELECT cart_id FROM cart WHERE user_id=? AND perfume_id=?");
        $exists->bind_param("ii", $user_id, $perfume_id);
        $exists->execute();
        $exists->store_result();
        if ($exists->num_rows > 0) {
            // Update quantity
            $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND perfume_id = ?");
            $stmt->bind_param("iii", $quantity, $user_id, $perfume_id);
        } else {
            // Insert new
            $stmt = $conn->prepare("INSERT INTO cart (user_id, perfume_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $user_id, $perfume_id, $quantity);
        }
        $stmt->execute();
        echo "<script>alert('Added to cart!'); window.location='user_OPage.php';</script>";
    } else {
        echo "<script>alert('Invalid quantity or out of perfume_stock!');</script>";
    }
}

// Fetch perfumes
$perfumes = $conn->query("SELECT * FROM perfumes");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Perfumes - Redolere Avenue</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function adjustQty(perfumeId, change, maxStock) {
            const input = document.getElementById('qty_input_' + perfumeId);
            const display = document.getElementById('qty_display_' + perfumeId);
            let current = parseInt(input.value);
            let updated = current + change;

            if (updated < 1) updated = 1;
            if (updated > maxStock) updated = maxStock;

            input.value = updated;
            display.textContent = updated;
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

    <h2>✨ Order Perfumes ✨</h2>
    <div class="perfume-container">
        <?php if ($perfumes->num_rows > 0): ?>
            <?php while ($row = $perfumes->fetch_assoc()): ?>
                <div class="order_item_container">
                    <div class="perfume_details">
                        <h3><?= $row['perfume_name'] ?> - <?= $row['perfume_brand'] ?></h3>
                        <p><strong>Price:</strong> ₱<?= number_format($row['perfume_price'], 2) ?></p>
                        <p><strong>Stock:</strong> <?= $row['perfume_stock'] ?> left in stock</p>
                        <p><strong>Scent Profile:</strong><?= $row['perfume_scent_profile'] ?></p>

                        <form method="POST">
                            <input type="hidden" name="perfume_id" value="<?= $row['perfume_id'] ?>">
                            <div class="quantity-control">
                                <div>
                                    <a href="javascript:void(0);" class="qty-btn" onclick="adjustQty(<?= $row['perfume_id'] ?>, -1, <?= $row['perfume_stock'] ?>)">➖</a>
                                    <span id="qty_display_<?= $row['perfume_id'] ?>">1</span>
                                    <a href="javascript:void(0);" class="qty-btn" onclick="adjustQty(<?= $row['perfume_id'] ?>, 1, <?= $row['perfume_stock'] ?>)">➕</a>
                                </div>
                                <input type="hidden" id="qty_input_<?= $row['perfume_id'] ?>" name="quantity" value="1">
                                <button type="submit" class="add-to-cart-btn" name="add_to_cart" <?= $row['perfume_stock'] <= 0 ? 'disabled' : '' ?>>Add to Cart</button>
                            </div>
                        </form>
                    </div>

                    <div class="perfume_image">
                        <img class="image" src="images\<?= $row['perfume_name'] ?>.jpg" alt="Perfume Image">
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No perfumes available</p>
        <?php endif; ?>
    </div>

</body>

</html>