<?php
session_start();

// Database connection
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
    $_SESSION['message'] = "Please log in to add items to your cart.";
    header("Location: ../login-page-with-background-image/login.php"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = $_POST['item'];
    $price = $_POST['price'];
    $image_path = $_POST['image_path'];

    $cart_sql = "SELECT cart_id FROM cart WHERE user_id = ? AND status = 0";
    $cart_stmt = $conn->prepare($cart_sql);
    $cart_stmt->bind_param("i", $user_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();

    if ($cart_result->num_rows > 0) {
        $cart_row = $cart_result->fetch_assoc();
        $cart_id = $cart_row['cart_id'];
    } else {
        // No cart exists, create a new one
        $create_cart_sql = "INSERT INTO cart (user_id, added_at, status) VALUES (?, NOW(), 0)";
        $create_cart_stmt = $conn->prepare($create_cart_sql);
        $create_cart_stmt->bind_param("i", $user_id);
        $create_cart_stmt->execute();
        $cart_id = $conn->insert_id; // Get the newly created cart_id
        $create_cart_stmt->close();
    }

    // Check if the item already exists in the cart_items
    $check_sql = "SELECT food_id FROM cart_items WHERE cart_id = ? AND food_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("is", $cart_id, $item);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // If the item exists, increase the quantity (assuming you have a qty column in cart_items)
        $row = $check_result->fetch_assoc();
        $food_id = $row['food_id'];
        $update_sql = "UPDATE cart_items SET qty = qty + 1 WHERE cart_id = ? AND food_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("is", $cart_id, $food_id);
        $update_stmt->execute();
        $update_stmt->close();
        $_SESSION['message'] = "Item '$item' quantity updated in the cart!";
    } else {
        // If the item does not exist, insert it
        $insert_sql = "INSERT INTO cart_items (cart_id, food_id) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("is", $cart_id, $item);
        $insert_stmt->execute();
        $insert_stmt->close();
        $_SESSION['message'] = "Item '$item' added to the cart!";
    }

    $check_stmt->close();
    header("Location: 1.php");
    exit();
}

// Fetch food items from the menu table
$menu_sql = "SELECT food_id, food_name, price, url FROM menu";
$menu_result = $conn->query($menu_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Menu - JKS Foods</title>
    <link rel="stylesheet" href="1.css">
    <style>
        .menu-item {
            display: block;
            transition: 0.3s ease;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo">
        <img src="..\images\logo.jpg" alt="Logo" class="logo-img">
    </div>
    <div class="nav-links">
        <a href="..\home\1.html">Home</a>
        <a href="..\Menu\1.html">Our Food</a>
        <a href="..\AboutUS\1.html">About Us</a>
        <a href="..\FAQ's\1.html">FAQ</a>
        <a href="..\our food\1.php">Order Now</a>
    </div>
    <div class="profile-btn-container">
        <button class="profile-btn">
        <a href="..\profile\1.php"> <img src="..\profile\profile.png" alt="Profile Icon"></a>
        </button>
    </div>
    <div class="CART">
        <button class="cart-btn">
            <a href="cart.php">View Cart</a>
        </button>
    </div>
</div>

<section class="menu-section">
    <center>
        <input type="text" placeholder="Search Food" id="search-input" onkeyup="filterMenu()">
    </center>
    <h1 class="Heading"></h1>
    <div class="menu-grid" id="menu-grid">
        <?php if ($menu_result->num_rows > 0): ?>
            <?php while ($row = $menu_result->fetch_assoc()): ?>
                <form action="1.php" method="POST" class="menu-item">
                    <div class="menu-item">
                        <img src="<?php echo $row['url']; ?>" alt="<?php echo $row['food_name']; ?>">
                        <h4 class="food-name"><?php echo $row['food_name']; ?></h4>
                        <p>Price: $<?php echo $row['price']; ?></p>
                        <input type="hidden" name="item" value="<?php echo $row['food_id']; ?>">
                        <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                        <input type="hidden" name="image_path" value="<?php echo $row['url']; ?>">
                        <button type="submit">+ Add to Cart</button>
                    </div>
                </form>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No food items available.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Message to user -->
<?php if (isset($_SESSION['message'])): ?>
    <div class="message">
        <?php echo $_SESSION['message']; ?>
        <?php unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>

<footer>
    <div class="footer-content">
        <div class="footer-section about">
            <h3>Who Are We?</h3>
            <p>Launched in Jabalpur, FoodieBay has grown from a home project to one of the largest food aggregators in the world.</p>
        </div>
        <div class="footer-section contact">
            <h3>Get In Touch</h3>
            <p>Email: office@FoodieBay.com</p>
            <p>Phone: (+91) 892 808 5056</p>
        </div>
    </div>
</footer>

<script>
    function filterMenu() {
        const input = document.getElementById("search-input").value.toLowerCase();
        const menuItems = document.querySelectorAll(".menu-item");

        menuItems.forEach(item => {
            const foodName = item.querySelector(".food-name").textContent.toLowerCase();
            if (foodName.includes(input)) {
                item.classList.remove("hidden");
            } else {
                item.classList.add("hidden");
            }
        });
    }
</script>

</body>
</html>
