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

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "Please log in to view your cart.";
    header("Location: ./login-page-with-background-image/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle quantity update (increase or decrease)
if (isset($_GET['update'])) {
    $food_id = intval($_GET['update']);
    $action = $_GET['action'];

    if ($action === "increase") {
        $update_sql = "UPDATE cart_items SET qty = qty + 1 WHERE food_id = ? AND cart_id IN (SELECT cart_id FROM cart WHERE user_id = ? AND status = 0)";
    } elseif ($action === "decrease") {
        $update_sql = "UPDATE cart_items SET qty = GREATEST(qty - 1, 0) WHERE food_id = ? AND cart_id IN (SELECT cart_id FROM cart WHERE user_id = ? AND status = 0)";
    } else {
        die("Invalid action specified.");
    }

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $food_id, $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Item quantity updated successfully.";
    } else {
        $_SESSION['message'] = "Failed to update item quantity.";
    }

    header("Location: cart.php");
    exit();
}

// Handle item deletion
if (isset($_GET['delete'])) {
    $food_id = intval($_GET['delete']);

    $delete_sql = "DELETE FROM cart_items WHERE food_id = ? AND cart_id IN (SELECT cart_id FROM cart WHERE user_id = ? AND status = 0)";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("ii", $food_id, $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Item removed from cart.";
    } else {
        $_SESSION['message'] = "Failed to remove item from cart.";
    }

    header("Location: cart.php");
    exit();
}


// Handle place order
if (isset($_POST['place_order'])) {
    // Update cart status to mark as processed
    $update_cart_sql = "UPDATE cart SET status = 1 WHERE cart_id IN (SELECT cart_id FROM cart WHERE user_id = ? AND status = 0)";
    $stmt = $conn->prepare($update_cart_sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Fetch the cart_id for the user's cart
        $cart_id_sql = "SELECT cart_id FROM cart WHERE user_id = ? AND status = 1 ORDER BY cart_id DESC LIMIT 1";
        $cart_stmt = $conn->prepare($cart_id_sql);
        $cart_stmt->bind_param("i", $user_id);
        $cart_stmt->execute();
        $result = $cart_stmt->get_result();

        if ($cart = $result->fetch_assoc()) {
            $cart_id = $cart['cart_id'];

            // Insert into the orders table
            $insert_order_sql = "INSERT INTO orders (cart_id, order_status) VALUES (?, 0)";
            $insert_order_stmt = $conn->prepare($insert_order_sql);
            $insert_order_stmt->bind_param("i", $cart_id);

            if ($insert_order_stmt->execute()) {
                // Fetch the new order_id
                $order_id = $conn->insert_id; // Get the last inserted ID
                $_SESSION['order_id'] = $order_id;

                // Redirect to the "Order Placed" page
                header("Location: ../order_placed/1.php");
                exit();
            } else {
                $_SESSION['message'] = "Failed to create the order.";
            }
        } else {
            $_SESSION['message'] = "No active cart found.";
        }
    } else {
        $_SESSION['message'] = "Failed to update cart status.";
    }

    // Redirect back to cart if something fails
    header("Location: cart.php");
    exit();
}


// Fetch all items in the cart
$sql = "SELECT ci.food_id, ci.qty, m.price, m.food_name, m.url AS image_path 
        FROM cart_items ci 
        JOIN cart c ON ci.cart_id = c.cart_id 
        JOIN menu m ON ci.food_id = m.food_id 
        WHERE c.user_id = ? AND c.status = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_price = 0;
$cart_items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_price += $row['price'] * $row['qty'];
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
    <title>Your Cart - FoodieBay Foods</title>
    <link rel="stylesheet" href="1.css">
</head>
<body>
<div class="navbar">
    <div class="logo">
        <img src="../images/logo.jpg" alt="Logo" class="logo-img">
    </div>
    <div class="nav-links">
        <a href="../home/1.html">Home</a>
        <a href="../Menu/1.html">Our Food</a>
        <a href="../AboutUS/1.html">About Us</a>
        <a href="../FAQ's/1.html">FAQ</a>
        <a href="../our food/1.html">Order Now</a>
    </div>
    <div class="profile-btn-container">
        <button class="profile-btn">
        <a href="..\profile\1.php"> <img src="..\profile\profile.png" alt="Profile Icon"></a>
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
                <div class="cart-img">
                    <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['food_name']; ?>" class="cart-item-img">
                    <h4><?php echo $item['food_name']; ?></h4>
                    <p>Price: $<?php echo $item['price']; ?></p>
                    <p>Quantity: 
                        <a href="cart.php?update=<?php echo $item['food_id']; ?>&action=decrease" class="update-btn">-</a>
                        <?php echo $item['qty']; ?>
                        <a href="cart.php?update=<?php echo $item['food_id']; ?>&action=increase" class="update-btn">+</a>
                    </p>
                    <p>Subtotal: $<?php echo $item['price'] * $item['qty']; ?></p>
                    <a href="cart.php?delete=<?php echo $item['food_id']; ?>" class="delete-btn">Delete</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Your cart is empty!</p>
        <?php endif; ?>
    </div>

    <div class="total-price">
        <h3>Total: $<?php echo $total_price; ?></h3>
    </div>

    <div class="place-order">
        <form action="cart.php" method="post">
            <button type="submit" name="place_order" class="place-order-btn">Place Order</button>
        </form>
    </div>
</section>

<footer>
    <div class="footer-content">
        <div class="footer-section about">
            <h3>Who Are We?</h3>
            <p>Launched in Jabalpur, FoodieBay has grown from a home project to one of the largest food aggregators in the world, enabling our vision of better food for more people.</p>
        </div>
        <div class="footer-section links">
            <h3>Quick Links</h3>
            <ul>
            <li><a href="../home/1.html">Home</a></li>
                <li><a href="../Menu/1.html">Our Food</a></li>
                <li><a href="../AboutUS/1.html">About Us</a></li>
                <li><a href="../FAQ's/1.html">FAQ</a></li>
                <li><a href="..\our food\1.php">Order Now</a></li>
            </ul>
        </div>
        <div class="footer-section contact">
            <h3>Get In Touch</h3>
            <p>Email: office@FoodieBay.com</p>
            <p>Phone: (+91) 892 808 5056</p>
        </div>
    </div>
</footer>
</body>
</html>
