<?php
session_start();
include 'db.php';

$errors = [];
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $errors[] = "All fields are required.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute([
            'username' => $username,
            'email' => $username
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "Invalid username/email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center h-screen">

    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

        <form method="POST">
            <input type="text" name="username" placeholder="Username or Email" class="w-full p-2 mb-4 border rounded" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            <div class="relative mb-4">
                <input type="password" id="password" name="password" placeholder="Password" class="w-full p-2 border rounded" required>
                <button type="button" onclick="togglePassword('password')" class="absolute right-2 top-2 text-gray-500">Show</button>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Login</button>
        </form>
        <p class="mt-4 text-center text-sm">Don't have an account? <a href="register.php" class="text-blue-500">Register</a></p>
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
            });
        <?php endif; ?>
    </script>

</body>

</html>