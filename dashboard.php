<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }

        #fireworksCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            /* behind content */
            pointer-events: none;
        }
    </style>
</head>

<body class="bg-gray-100 p-8 flex items-center justify-center">

    <canvas id="fireworksCanvas"></canvas>

    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow text-center relative z-10">
        <h1 class="text-3xl font-bold mb-4">🎉 Welcome, <?= htmlspecialchars($_SESSION['username']) ?>! 🎉</h1>
        <p class="mb-4">This is a protected dashboard. Enjoy your stay!</p>
        <a href="logout.php" class="mt-4 inline-block bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</a>
    </div>

    <script>
        // Simple fireworks effect
        const canvas = document.getElementById('fireworksCanvas');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        let fireworks = [];

        function random(min, max) {
            return Math.random() * (max - min) + min;
        }

        class Firework {
            constructor(x, y) {
                this.x = x;
                this.y = y;
                this.particles = [];
                for (let i = 0; i < 30; i++) {
                    this.particles.push({
                        x: this.x,
                        y: this.y,
                        vx: random(-3, 3),
                        vy: random(-3, 3),
                        alpha: 1,
                        color: `hsl(${Math.floor(random(0, 360))}, 100%, 50%)`
                    });
                }
            }
            update() {
                this.particles.forEach(p => {
                    p.x += p.vx;
                    p.y += p.vy;
                    p.vy += 0.05; // gravity
                    p.alpha -= 0.02;
                });
                this.particles = this.particles.filter(p => p.alpha > 0);
            }
            draw() {
                this.particles.forEach(p => {
                    ctx.fillStyle = p.color;
                    ctx.globalAlpha = p.alpha;
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, 3, 0, Math.PI * 2);
                    ctx.fill();
                });
                ctx.globalAlpha = 1;
            }
        }

        function animate() {
            ctx.fillStyle = "rgba(0,0,0,0.1)";
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            if (Math.random() < 0.05) {
                fireworks.push(new Firework(random(100, canvas.width - 100), random(100, canvas.height - 100)));
            }

            fireworks.forEach(fw => {
                fw.update();
                fw.draw();
            });

            fireworks = fireworks.filter(fw => fw.particles.length > 0);

            requestAnimationFrame(animate);
        }

        animate();

        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
    </script>

</body>

</html>