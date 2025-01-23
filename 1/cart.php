<?php
session_start();

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "erestraunt";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle quantity update (increase or decrease)
if (isset($_GET['update'])) {
    $item_id = $_GET['update'];
    $action = $_GET['action']; // 'increase' or 'decrease'

    // Fetch the current quantity
    $sql = "SELECT quantity FROM cart_items WHERE id = $item_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $current_quantity = $row['quantity'];

    // Update the quantity based on the action
    if ($action == 'increase') {
        $new_quantity = $current_quantity + 1;
    } elseif ($action == 'decrease' && $current_quantity > 1) {
        $new_quantity = $current_quantity - 1;
    } else {
        $new_quantity = 0; // Automatically delete the item if quantity is 0
    }

    // Update quantity or delete item if quantity is 0
    if ($new_quantity > 0) {
        $update_sql = "UPDATE cart_items SET quantity = $new_quantity WHERE id = $item_id";
        $conn->query($update_sql);
    } else {
        $delete_sql = "DELETE FROM cart_items WHERE id = $item_id";
        $conn->query($delete_sql);
    }

    header("Location: cart.php");
    exit();
}

// Handle item deletion (on delete button click)
if (isset($_GET['delete'])) {
    $item_id = $_GET['delete'];

    // Delete the item from the cart
    $delete_sql = "DELETE FROM cart_items WHERE id = $item_id";
    
    if ($conn->query($delete_sql) === TRUE) {
        $_SESSION['message'] = "Item deleted successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $conn->error;
    }

    header("Location: cart.php");
    exit();
}

// Fetch all items in the cart
$sql = "SELECT * FROM cart_items";
$result = $conn->query($sql);

$total_price = 0;
$cart_items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_price += $row['price'] * $row['quantity']; // Calculate total price based on quantity
    }
} else {
    $_SESSION['message'] = "Your cart is empty!";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - JKS Foods</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
    <div class="logo">
        <img src="..\images\logo.jpg" alt="Logo" class="logo-img">
    </div>
    <div class="nav-links">
        <a href="..\home\1.html">Home</a>
        <a href="..\Menu\1.html">Our Food</a>
        <a href="..\AboutUS\1.html">About Us</a>
        <a href="..\FAQ's\1.html">FAQ</a>
        <a href="..\our food\1.html">Order Now</a>
    </div>
    <div class="profile-btn-container">
        <button class="profile-btn">
            <a href="cart.php"><img src="..\profile\profile.png" alt="Profile Icon"></a>
        </button>
    </div>
    <div class="CART">
        <button class="cart-btn">
            <a href="1.php">Menu Page</a>
        </button>
    </div>
</div>

<section class="cart-section">
    <h1>Your Cart</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message">
            <?php echo $_SESSION['message']; ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <div class="cart-items">
        <?php if (!empty($cart_items)): ?>
            <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['item_name']; ?>" class="cart-item-img">
                    <h4><?php echo $item['item_name']; ?></h4>
                    <p>Price: $<?php echo $item['price']; ?></p>
                    <p>Quantity: 
                        <a href="cart.php?update=<?php echo $item['id']; ?>&action=decrease" class="update-btn">-</a>
                        <?php echo $item['quantity']; ?>
                        <a href="cart.php?update=<?php echo $item['id']; ?>&action=increase" class="update-btn">+</a>
                    </p>
                    <p>Subtotal: $<?php echo $item['price'] * $item['quantity']; ?></p>
                    <a href="cart.php?delete=<?php echo $item['id']; ?>" class="delete-btn">Delete</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Your cart is empty!</p>
        <?php endif; ?>
    </div>

    <div class="total-price">
        <h3>Total: $<?php echo $total_price; ?></h3>
    </div>
</section>

<footer>
<div class="footer-content">
        <div class="footer-section about">
            <h3>Who Are We?</h3>
            <p>Launched in Mumbai, JKS has grown from a home project to one of the largest food aggregators in the world, enabling our vision of better food for more people.</p>
        </div>
        <div class="footer-section links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#food">Our Food</a>    </li>
                <li><a href="#faq">FAQ</a></li>
                <li><a href="#order">Order Now</a></li>
            </ul>
        </div>
        <div class="footer-section contact">
            <h3>Get In Touch</h3>
            <p>Email: office@jks.com</p>
            <p>Phone: (+91) 892 808 5056</p>
        </div>
    </div>
</footer>

</body>
</html>