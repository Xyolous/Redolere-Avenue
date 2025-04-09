<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $cpass = $_POST['confirm_password'];

    if ($pass != $cpass) {
        echo "<script>alert('Passwords do not match!');</script>";
    } elseif (strlen($pass) < 8) {
        echo "<script>alert('Password must be at least 8 characters!');</script>";
    } else {
        $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, user_type) VALUES (?, ?, ?, ?, 'Customer')");
        $stmt->bind_param("ssss", $fname, $lname, $email, $hash_pass);
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!'); window.location='index.html';</script>";
        } else {
            echo "<script>alert('Email already used!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Redolere Avenue</title>
</head>

<body class="register-page">
    <div class="form-container">
        <h2>Create Account</h2>
        <form method="POST">
            <input type="text" name="first_name" placeholder="First Name" autocomplete="off" required><br>
            <input type="text" name="last_name" placeholder="Last Name" autocomplete="off" required><br>
            <input type="email" name="email" placeholder="Email" autocomplete="off" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
            <button type="submit">Create Account</button>
        </form>
        <p>Already have an account? <a href="index.html">Login here</a></p>
    </div>
</body>

</html>