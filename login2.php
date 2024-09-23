<?php
require_once 'Auth.php';
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Both fields are required.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "username atau password salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ultra Modern Login</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap');

        :root {
            --primary-color: #4f46e5;
            --secondary-color: #06b6d4;
            --accent-color: #f97316;
            --background-color: #0f172a;
            --text-color: #f1f5f9;
            --error-color: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: 'Montserrat', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            overflow: hidden;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            perspective: 1000px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
            transform-style: preserve-3d;
            transition: all 0.5s ease;
        }

        .login-container:hover {
            transform: translateZ(20px) rotateX(5deg) rotateY(5deg);
        }

        .login-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            color: var(--secondary-color);
            text-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
        }

        .error-message {
            background-color: rgba(239, 68, 68, 0.2);
            color: var(--error-color);
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: none;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--text-color);
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 15px rgba(79, 70, 229, 0.5);
        }

        .form-control:focus + label,
        .form-control:not(:placeholder-shown) + label {
            transform: translateY(-30px) translateX(-10px) scale(0.8);
            color: var(--secondary-color);
        }

        label {
            position: absolute;
            top: 50%;
            left: 20px;
            transform: translateY(-50%);
            font-size: 16px;
            color: rgba(241, 245, 249, 0.7);
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(79, 70, 229, 0.6);
        }

        .btn-login::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
            transition: all 0.3s ease;
        }

        .btn-login:hover::after {
            left: 100%;
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            font-size: 15px;
            color: rgba(241, 245, 249, 0.7);
        }

        .register-link a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            text-shadow: 0 0 10px rgba(249, 115, 22, 0.5);
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .cube {
            position: absolute;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            animation: cube 15s linear infinite;
            transform-style: preserve-3d;
        }

        .cube:nth-child(1) {
            top: 10%;
            left: 45%;
            animation-delay: 0s;
        }

        .cube:nth-child(2) {
            top: 70%;
            left: 25%;
            animation-delay: -2s;
        }

        .cube:nth-child(3) {
            top: 40%;
            left: 80%;
            animation-delay: -4s;
        }

        @keyframes cube {
            0% {
                transform: rotateX(0deg) rotateY(0deg) translateZ(0);
            }
            100% {
                transform: rotateX(360deg) rotateY(360deg) translateZ(0);
            }
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="cube"></div>
        <div class="cube"></div>
        <div class="cube"></div>
    </div>
    <div class="container">
        <div class="login-container">
            <h2 class="login-title">LOGIN</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="post">
                <div class="form-group">
                    <input type="text" class="form-control" id="email" name="email" placeholder=" " required>
                    <label for="email">Username</label>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder=" " required>
                    <label for="password">Password</label>
                </div>
                <button type="submit" class="btn-login">Login</button>
            </form>
            
            <p class="register-link">Belum punya akun? <a href="register2.php">Daftar</a></p>
        </div>
    </div>

    <script>
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.classList.add('focused');
            });
            input.addEventListener('blur', function() {
                if (this.value === '') {
                    this.parentNode.classList.remove('focused');
                }
            });
        });

        document.addEventListener('mousemove', function(e) {
            const loginContainer = document.querySelector('.login-container');
            const xAxis = (window.innerWidth / 2 - e.pageX) / 25;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 25;
            loginContainer.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
        });
    </script>
</body>
</html>