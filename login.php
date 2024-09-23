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
            $error = "Invalid email or password.";
        }
    }
}

$content = <<<EOT
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        font-family: 'Poppins', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }
    .login-container {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 20px;
        padding: 40px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        margin: 20px;
    }
    .login-title {
        color: #333;
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 30px;
        text-align: center;
    }
    .error-message {
        background-color: #ffebee;
        color: #f44336;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
        text-align: center;
        font-size: 14px;
    }
    .form-group {
        margin-bottom: 25px;
        position: relative;
    }
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: none;
        border-bottom: 2px solid #ddd;
        background-color: transparent;
        font-size: 16px;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        outline: none;
        border-bottom-color: #667eea;
    }
    .form-control:focus + label,
    .form-control:not(:placeholder-shown) + label {
        top: -20px;
        font-size: 12px;
        color: #667eea;
    }
    label {
        position: absolute;
        top: 15px;
        left: 0;
        font-size: 16px;
        color: #999;
        transition: all 0.3s ease;
        pointer-events: none;
    }
    .btn-login {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 50px;
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }
    .register-link {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
        color: #666;
    }
    .register-link a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    .register-link a:hover {
        color: #764ba2;
    }
</style>

<div class="login-container">
    <h2 class="login-title">Login</h2>
    
    <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="post">
        <div class="form-group">
            <input type="text" class="form-control" id="email" name="email" placeholder=" " required>
            <label for="email">User</label>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="password" name="password" placeholder=" " required>
            <label for="password">Password</label>
        </div>
        <button type="submit" class="btn-login">Login</button>
    </form>
    
    <p class="register-link">Belum punya akun? <a href="register.php">Daftar</a></p>
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
</script>
EOT;

renderLayout($content);
?>