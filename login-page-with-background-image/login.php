<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Page</title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
<form action="login.php" method="POST">
  <div class="container">
    <img id="logo" src="../images/logo.jpg" alt="Logo">
    <div class="form-field">
      <input type="text" name="user" placeholder="Email / Username" required>
    </div>
    <div class="form-field">
      <input type="password" name="pass" placeholder="Password" required>
    </div>
    <div class="form-field">
      <button class="btn" type="submit" name="submit">Submit</button>
      <button class="btn" type="button" onclick="window.location.href='login2.php';">Sign up</button>
    </div>
    <div id="error-message">
    <?php
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Start session
    session_start();

    if (isset($_POST["submit"])) {
        // Get input values
        $Username = $_POST["user"];
        $Password = $_POST["pass"];

        // Database connection
        $mycon = mysqli_connect("localhost", "root", "", "erestraunt") or die("Connection failed: " . mysqli_connect_error());

        // Prepare and execute query to check user
        $sql = "SELECT user_id, username, password FROM users WHERE username = ?"; // Include user_id in the SELECT statement
        $stmt = $mycon->prepare($sql);
        $stmt->bind_param("s", $Username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($Password === $row["password"]) {
                // Set session and redirect to home page
                $_SESSION["user_id"] = $row["user_id"]; // Store user_id in session

                // Fetch the corresponding name
                $userid = $row["user_id"]; // Get the user_id
                $name_sql = "SELECT Name FROM users WHERE user_id = ?"; // Use user_id for fetching name
                $name_stmt = $mycon->prepare($name_sql);
                $name_stmt->bind_param("i", $userid); // Assuming user_id is an integer
                $name_stmt->execute();
                $name_result = $name_stmt->get_result();
                
                if ($name_result->num_rows > 0) {
                    $name_row = $name_result->fetch_assoc();
                    $_SESSION['name'] = $name_row['Name']; // Store name in session
                }

                header("Location: ../home/1.html");
                exit();
            } else {
                echo "Incorrect password. <br> Please try again.";
            }
        } else {
            echo "Username does not exist. <br> Would you like to <a href='login2.php'>create a new account</a>?";
        }

        // Close the statement and connection
        $stmt->close();
        $mycon->close();
    }
    ?>
    </div>
  </div>
</form>

<!-- Admin Login Button -->
<div class="admin-login-btn">
  <a href="login3.php"><button class="btn" type="button">Admin Login</button></a>
</div>

</body>
</html>