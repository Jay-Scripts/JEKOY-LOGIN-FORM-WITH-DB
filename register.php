<?php
session_start();
include 'db.php'; // your PDO pdoection

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Validation
    if (empty($username)) $errors[] = "Username is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirm) $errors[] = "Passwords do not match.";
    if (!preg_match('/[A-Z]/', $password)) $errors[] = "Password must include at least 1 uppercase letter.";
    if (!preg_match('/[a-z]/', $password)) $errors[] = "Password must include at least 1 lowercase letter.";
    if (!preg_match('/[\W]/', $password)) $errors[] = "Password must include at least 1 special character.";

    if (empty($errors)) {
        // Check if username or email exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Username or email already taken.";
        } else {
            // Insert user
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashed]);
            $success = "Account created! You can login now.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center h-screen">

    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" class="w-full p-2 mb-4 border rounded" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            <input type="email" name="email" placeholder="Email" class="w-full p-2 mb-4 border rounded" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

            <div class="relative mb-4">
                <input type="password" id="password" name="password" placeholder="Password" class="w-full p-2 border rounded" required>
                <button type="button" onclick="togglePassword('password')" class="absolute right-2 top-2 text-gray-500">Show</button>
            </div>

            <div class="relative mb-4">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" class="w-full p-2 border rounded" required>
                <button type="button" onclick="togglePassword('confirm_password')" class="absolute right-2 top-2 text-gray-500">Show</button>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Register</button>
        </form>
        <p class="mt-4 text-center text-sm">Already have an account? <a href="login.php" class="text-blue-500">Login</a></p>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === "password" ? "text" : "password";
        }

        // SweetAlert notifications
        <?php if (!empty($errors)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: "<?= implode('<br>', $errors) ?>",
            });
        <?php elseif (!empty($success)): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= $success ?>',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location = 'login.php';
            });
        <?php endif; ?>
    </script>

</body>

</html>