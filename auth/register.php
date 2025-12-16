<?php
require_once "../config/database.php";

$error_username = "";
$error_email = "";
$error_password = "";
$error_role = "";

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];


    if (empty($username)) {
        $error_username = "Username is required";
    } elseif (empty($role)) {
        $error_role = "Please select a role";
    } elseif (empty($email)) {
        $error_email = "Email is required";
    } elseif (empty($password)) {
        $error_password = "Password is required";
    } elseif (strlen($password) < 8) {
        $error_password = "Password must be at least 6 characters";
    } elseif (empty($error_username) && empty($error_email) && empty($error_password) && empty($error_role)) {
        $check = mysqli_query($conn, "SELECT id FROM user WHERE email = '$email'");

        if (mysqli_num_rows($check) > 0) {
            $error_email = "Email already exists";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO user (nom, email, password_hash, role)
              VALUES ('$username', '$email', '$hash', '$role')";

            if (mysqli_query($conn, $sql)) {
                header("Location: login.php");
                exit;
            } else {
                $error_email = "Registration failed. Please try again";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-sky-100 flex items-center justify-center min-h-screen">

    <div class="bg-white flex rounded-2xl shadow-lg max-w-4xl w-full overflow-hidden">

        <div class="w-1/2 bg-sky-800 text-white p-12 flex flex-col justify-center">
            <h2 class="text-3xl font-bold mb-4">Welcome Back!</h2>
            <p class="mb-6">Already have an account? Sign in to continue your experience</p>
            <a href="login.php" class="border border-white px-6 py-2 rounded-full hover:bg-white hover:text-sky-800 transition-all font-semibold text-center">
                Sign In
            </a>
        </div>

        <div class="w-1/2 p-12">
            <h2 class="text-3xl font-bold text-sky-800 mb-6">Create Account</h2>
            <p class="text-sm text-gray-500 mb-4 text-center">Use your email for registration</p>

            <form method="POST" action="register.php" class="space-y-4">

                <div>
                    <?php if (!empty($error_username)): ?>
                        <p class="text-red-500 text-sm mb-1"><?php echo $error_username; ?></p>
                    <?php endif; ?>
                    <input
                        type="text"
                        name="username"
                        placeholder="Full Name"
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <?php if (!empty($error_role)): ?>
                        <p class="text-red-500 text-sm mb-1"><?php echo $error_role; ?></p>
                    <?php endif; ?>
                    <select
                        name="role"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="">Choose role...</option>
                        <option value="etudiant" <?php echo (isset($_POST['role']) && $_POST['role'] == 'etudiant') ? 'selected' : ''; ?>>Etudiant</option>
                        <option value="enseignant" <?php echo (isset($_POST['role']) && $_POST['role'] == 'enseignant') ? 'selected' : ''; ?>>Enseignant</option>
                    </select>
                </div>

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
                    Sign Up
                </button>

            </form>

        </div>
    </div>

</body>

</html>