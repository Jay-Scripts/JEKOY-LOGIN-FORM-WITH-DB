<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Logging Out...</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="text-center">
        <div class="mb-4 text-lg font-semibold">Logging you out...</div>
        <div class="loader border-t-4 border-blue-500 rounded-full w-12 h-12 animate-spin mx-auto"></div>
    </div>

    <style>
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3b82f6;
            /* Tailwind blue-500 */
            border-radius: 50%;
            width: 48px;
            height: 48px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <script>
        // Redirect after 2 seconds
        setTimeout(() => {
            window.location.href = 'login.php';
        }, 2000);
    </script>

</body>

</html>