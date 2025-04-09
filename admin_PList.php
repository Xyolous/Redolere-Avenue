<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'Admin') {
    header("Location: index.html");
    exit();
}

// INSERT
if (isset($_POST['add'])) {
    $name = $_POST['perfume_name'];
    $brand = $_POST['perfume_brand'];
    $price = $_POST['perfume_price'];
    $stock = $_POST['perfume_stock'];
    $profile = $_POST['perfume_profile'];

    $stmt = $conn->prepare("INSERT INTO perfumes (perfume_name, perfume_brand, perfume_price, perfume_stock, perfume_scent_profile) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdis", $name, $brand, $price, $stock, $profile);
    $stmt->execute();
}

// UPDATE
if (isset($_POST['update'])) {
    $id = $_POST['perfume_id'];
    $name = $_POST['perfume_name'];
    $brand = $_POST['perfume_brand'];
    $price = $_POST['perfume_price'];
    $stock = $_POST['perfume_stock'];
    $profile = $_POST['perfume_profile'];

    $stmt = $conn->prepare("UPDATE perfumes SET perfume_name=?, perfume_brand=?, perfume_price=?, perfume_stock=?, perfume_scent_profile=? 
                            WHERE perfume_id=?");
    $stmt->bind_param("ssdisi", $name, $brand, $price, $stock, $profile, $id);
    $stmt->execute();
}

// DELETE
if (isset($_POST['delete'])) {
    $id = $_POST['perfume_id'];
    $stmt = $conn->prepare("DELETE FROM perfumes WHERE perfume_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// FETCH ALL
$result = $conn->query("SELECT * FROM perfumes");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Product List</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h2>Product List</h2>
    <a href="admin_Home.php">← Back to Dashboard</a>
    <table>
        <tr>
            <th>Perfume ID</th>
            <th>Name</th>
            <th>Brand</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Scent Profile</th>
            <th>Insert Date</th>
            <th>Update Date</th>
            <th>Action</th>
        </tr>
        <?php if ($result->num_rows > 0): while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <form method="POST">
                        <td><?= $row['perfume_id']; ?><input type="hidden" name="perfume_id" value="<?= $row['perfume_id']; ?>"></td>

                        <!-- Display/Editable Mode -->
                        <?php if (isset($_POST['edit']) && $_POST['edit'] == $row['perfume_id']): ?>
                            <!-- Editable Mode -->
                            <td><input type="text" name="perfume_name" value="<?= $row['perfume_name']; ?>" required></td>
                            <td><input type="text" name="perfume_brand" value="<?= $row['perfume_brand']; ?>" required></td>
                            <td><input type="number" step="0.01" name="perfume_price" value="<?= $row['perfume_price']; ?>" required></td>
                            <td><input type="number" name="perfume_stock" value="<?= $row['perfume_stock']; ?>" required></td>
                            <td><textarea name="perfume_profile" required><?= $row['perfume_scent_profile']; ?></textarea></td>
                        <?php else: ?>
                            <!-- Display Mode -->
                            <td><?= $row['perfume_name']; ?></td>
                            <td><?= $row['perfume_brand']; ?></td>
                            <td><?= $row['perfume_price']; ?></td>
                            <td><?= $row['perfume_stock']; ?></td>
                            <td class="perfume-profile"><?= $row['perfume_scent_profile']; ?></td>
                        <?php endif; ?>

                        <td><?= $row['created_at']; ?></td>
                        <td><?= $row['updated_at']; ?></td>
                        <td>
                            <?php if (isset($_POST['edit']) && $_POST['edit'] == $row['perfume_id']): ?>
                                <button type="submit" name="update">Update</button>
                            <?php else: ?>
                                <button type="submit" name="edit" value="<?= $row['perfume_id']; ?>">Edit</button>
                            <?php endif; ?>
                            <button type="submit" name="delete" onclick="return confirm('Are you sure?')">Delete</button>
                        </td>
                    </form>
                </tr>
            <?php endwhile;
        else: ?>
            <tr>
                <td colspan="9" style="text-align:center;">No Records</td>
            </tr>
        <?php endif; ?>

        <!-- Insert part -->
        <tr>
            <form method="POST">
                <td>New</td>
                <td><input type="text" name="perfume_name" placeholder="Perfume Name: ..." required></td>
                <td><input type="text" name="perfume_brand" placeholder="Brand: ..." required></td>
                <td><input type="number" step="0.01" name="perfume_price" required></td>
                <td><input type="number" name="perfume_stock" required></td>
                <td><input type="text" name="perfume_profile" placeholder="Scent Profile: ..." required></td>
                <td colspan="2">--</td>
                <td><button type="submit" name="add">Add Perfume</button></td>
            </form>
        </tr>
    </table>
</body>

</html>