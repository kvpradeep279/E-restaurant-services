
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ's - FoodieBay Foods</title>
    <link rel="stylesheet" href="1.css">
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
</div>

<!--Contents-->
<div class="content">
    <?php
    session_start();
    $orderid=$_SESSION['order_id'];
    echo'
    <div class="main">
        <h1>Order Placed Sucessfully</h1>
        <h2>Your Order ID: ' .$orderid. '</h2>
    </div>
   <div class="button">
    <form action="..\home\1.html">
        <button type="submit" >Home</button>
    </form>
   </div>
</div>';
?>
<!-- Footer -->
<footer>
    <div class="footer-content">
        <div class="footer-section about">
            <h3>Who Are We?</h3>
            <p>Launched in Jabalpur,FoodieBay has grown from a home project to one of the largest food aggregators in the world, enabling our vision of better food for more people.</p>
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


