<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Page</title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
  <form action="login2.php" method="post">
    <div class="container">
      <img id="logo" src="../images/logo.jpg" alt="Foodie Bay Logo">
      <div class="form-field">
        <input type="text" placeholder="Name" name="name" required />
      </div>
      <div class="form-field">
        <input type="text" placeholder="User  ID" name="userid" required />
      </div>
      <div class="form-field">
        <input type="password" placeholder="Password" name="password" required />
      </div>
      <div class="form-field">
        <button class="btn" type="submit" name="login">Create Account</button>
      </div>
    </div>
  </form>
  <?php
  session_start();

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Retrieve form data
      $userid = $_POST['userid'];
      $password = $_POST['password'];
      $name = $_POST['name'];

      // Database connection
      $connection = mysqli_connect("localhost", "root", "", "erestraunt");
      if (!$connection) {
          die("Database connection failed: " . mysqli_connect_error());
      }
      // Insert user data
      $sql = "INSERT INTO logininfo ( User_id, Password, Name) VALUES (?, ?, ?)";
      $ps = $connection->prepare($sql);
      $ps->bind_param("sss", $userid, $password, $name);
      $ps->execute();
      $ps->close();
      // Check login credentials
      $sql = "SELECT * FROM logininfo WHERE User_id = ? AND Password = ?";
      $stmt = $connection->prepare($sql);
      $stmt->bind_param("ss", $userid, $password);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows == 1) {
          // Fetch user data
          $row = $result->fetch_assoc();
          $_SESSION['userid'] = $row['User_id'];
          $_SESSION['name'] = $row['Name'];
          header("Location: ../login-page-with-background-image/login.php");
          exit();
      }

      $stmt->close();
      $connection->close();
  }
  ?>
</body>
</html>