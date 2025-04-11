<?php
session_start(); // Start the session

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "sbtbsphp";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Register user
if (isset($_POST['register'])) {
    $customer_name = $_POST['customer_name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $customer_phone = $_POST['customer_phone'];

    // Check if password and confirm password match
    if ($password === $confirm_password) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the statement
        $stmt = $conn->prepare("INSERT INTO customers (customer_name, password, customer_phone) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $customer_name, $hashedPassword, $customer_phone);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Passwords do not match');</script>";
    }
}

// Login user
if (isset($_POST['login'])) {
  $customer_phone = $_POST['customer_phone'];
  $password = $_POST['password'];

  // Prepare the statement to select user data
  $stmt = $conn->prepare("SELECT * FROM customers WHERE customer_phone = ?");
  $stmt->bind_param("s", $customer_phone);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      // Fetch user data
      $row = $result->fetch_assoc();

      // Verify the password
      if (password_verify($password, $row['password'])) {
          // If password matches
          $_SESSION['customer_name'] = $row['customer_name']; // Start session
          header("Location: home.php");
          exit;
      } else {
          // Password mismatch
          echo "<script>alert('Invalid password');</script>";
      }
  } else {
      // No user found
      echo "<script>alert('User not found');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <link href="assets/styles/styles_login.css" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
    /* Additional CSS for responsiveness */
    .wrapper {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
    }
    .input-box {
      position: relative;
      margin-bottom: 20px;
    }
    .input-box input {
      width: 100%;
      height: 50px;
      padding: 10px 20px;
      border-radius: 25px;
      border: 2px solid rgba(0, 0, 0, 0.1);
    }
    .btn {
      width: 100%;
      height: 50px;
    }

    *{
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body{
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: url('Bg5.jpeg') no-repeat;
      background-size: cover;
      background-position: center;
    }

    .wrapper{
      width: 90%;
      max-width: 420px;
      background: transparent;
      border: 2px solid rgba(255, 255, 255, 0.233);
      backdrop-filter: blur(9px);
      color: #000000;
      border-radius: 12px;
      padding: 30px 40px;
    }

    .wrapper h1{
      font-size: 36px;
      text-align: center;
    }

    .wrapper .input-box{
      position: relative;
      width: 100%;
      height: 50px;
      margin: 30px 0;
    }

    .register-link{
      margin-top: 5px;
      display: flex;
      justify-content: space-between;
    }

    .remember-forgot{
      margin-bottom: 5px;
      display: flex;
      justify-content: space-between;
    }

    .input-box input{
      width: 100%;
      height: 100%;
      background: transparent;
      border: none;
      outline: none;
      border: 2px solid rgba(255, 255, 255, .2);
      border-radius: 40px;
      font-size: 16px;
      color: #000000;
      padding: 20px 45px 20px 20px;
    }

    .input-box input::placeholder{
      color: #fff;
    }

    .input-box i{
      position: absolute;
      right: 20px;
      top: 30%;
      transform: translate(-50%);
      font-size: 20px;
    }

    .wrapper .btn{
      width: 100%;
      height: 45px;
      background: #fff;
      border: none;
      outline: none;
      border-radius: 40px;
      box-shadow: 0 0 10px rgba(0, 0, 0, .1);
      cursor: pointer;
      font-size: 16px;
      color: #333;
      font-weight: 600;
    }

    /* Responsive Media Queries */
    @media screen and (max-width: 768px) {
      .wrapper {
        width: 80%;
        max-width: 350px;
      }
    }

    @media screen and (max-width: 576px) {
      .wrapper {
        width: 90%;
        max-width: 300px;
        padding: 20px 30px;
      }

      .wrapper h1{
        font-size: 30px;
      }

      .input-box input{
        font-size: 14px;
      }

      .input-box i {
        font-size: 18px;
      }

      .wrapper .btn {
        height: 40px;
        font-size: 14px;
      }
    }

  </style>
</head>
<body>
  <div class="wrapper">
    <!-- Login Form -->
    <form id="loginForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="off">
      <h1>Login</h1>
      <div class="input-box">
        <input type="text" name="customer_phone" placeholder="Phone Number:" maxlength="10" required autocomplete="off">
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="Password" maxlength="20" required autocomplete="off">
        <i class='bx bxs-lock-alt'></i>
      </div>
      <button type="submit" name="login" class="btn">Login</button>
      <div class="register-link">
        Don't have an account? <a href="#" id="showRegister">Register</a>
      </div>
    </form>

    <!-- Register Form -->
    <form id="registerForm" method="post" autocomplete="off" action="" style="display: none;">
      <h1>Register</h1>
      <div class="input-box">
        <input type="text" name="customer_name" placeholder="Username" maxlength="50" required autocomplete="off">
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <input type="text" name="customer_phone" placeholder="Phone number:" maxlength="10" required autocomplete="off">
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="Password" maxlength="30" required autocomplete="off">
        <i class='bx bxs-lock-alt'></i>
      </div>
      <div class="input-box">
        <input type="password" name="confirm_password" placeholder="Confirm Password" maxlength="30" required autocomplete="off">
        <i class='bx bxs-lock-alt'></i>
      </div>
      <button type="submit" name="register" class="btn">Register</button>
      <div class="register-link">
        <p>Already have an account? <a href="#" id="showLogin">Login</a></p>
      </div>
    </form>
  </div>

  <script>
    document.getElementById('showRegister').addEventListener('click', function() {
      document.getElementById('loginForm').style.display = 'none';
      document.getElementById('registerForm').style.display = 'block';
    });

    document.getElementById('showLogin').addEventListener('click', function() {
      document.getElementById('registerForm').style.display = 'none';
      document.getElementById('loginForm').style.display = 'block';
    });
  </script>
</body>
</html>