<?php
require_once "../config/database.php";


$error_email = "";
$error_password = "";

if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  if (empty($email)) {
    $error_email = "Email is required";
  }

  if (empty($password)) {
    $error_password = "Password is required";
  }

  if (empty($error_email) && empty($error_password)) {
    $sql = "SELECT password_hash FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);

      if (password_verify($password, $row['password_hash'])) {
        header("Location:/QodexV2/enseignant/dashboard.php");
        exit;
      } else {
        $error_password = "Wrong password";
      }
    } else {
      $error_email = "Email not found";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-sky-100 flex items-center justify-center min-h-screen">

  <div class="bg-white flex rounded-2xl shadow-lg max-w-4xl w-full overflow-hidden">

    <div class="w-1/2 bg-sky-800 text-white p-12 flex flex-col justify-center">
      <h2 class="text-3xl font-bold mb-4">Hello, Friend!</h2>
      <p class="mb-6">Don't have an account? Register now and start your journey with us</p>
      <a href="register.php" class="border border-white px-6 py-2 rounded-full hover:bg-white hover:text-sky-800 transition-all font-semibold text-center">
        Register
      </a>
    </div>

    <div class="w-1/2 p-12">
      <h2 class="text-3xl font-bold text-sky-800 mb-6">Login</h2>
      <p class="text-sm text-gray-500 mb-4 text-center">Use your email to login</p>

      <form method="POST" action="login.php" class="space-y-4">

        <div>
          <?php if (!empty($error_email)): ?>
            <p class="text-red-500 text-sm mb-1"><?php echo $error_email; ?></p>
          <?php endif; ?>
          <input
            type="email"
            name="email"
            placeholder="Email Address"
            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
        </div>

        <div>
          <?php if (!empty($error_password)): ?>
            <p class="text-red-500 text-sm mb-1"><?php echo $error_password; ?></p>
          <?php endif; ?>
          <input
            type="password"
            name="password"
            placeholder="Password"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
        </div>

        <button
          type="submit"
          name="submit"
          class="w-full bg-sky-800 text-white py-2 rounded-lg font-semibold hover:bg-white hover:text-sky-800 border border-sky-800 transition-all">
          Login
        </button>

      </form>

    </div>
  </div>

</body>

</html>