<?php
require_once 'Auth.php';
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit();
}

// Get user information
$stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$content = <<<EOT
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f7fa;
        margin: 0;
        padding: 0;
    }

    .dashboard-container {
        max-width: 800px;
        margin: 50px auto;
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 40px;
    }

    .dashboard-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .dashboard-title {
        color: #333;
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .user-info {
        background-color: #e8f0fe;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
        text-align: center;
    }

    .user-email {
        font-size: 18px;
        color: #4285f4;
        font-weight: 500;
    }

    .logout-btn {
        display: inline-block;
        background-color: #ff4757;
        color: white;
        padding: 12px 24px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(255, 71, 87, 0.3);
    }

    .logout-btn:hover {
        background-color: #ff6b6b;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 71, 87, 0.4);
    }

    .dashboard-content {
        background-color: #f9f9f9;
        border-radius: 10px;
        padding: 30px;
        margin-top: 30px;
    }

    .dashboard-section {
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 24px;
        color: #333;
        margin-bottom: 15px;
        border-bottom: 2px solid #4285f4;
        padding-bottom: 10px;
    }

    .section-content {
        font-size: 16px;
        color: #666;
        line-height: 1.6;
    }
</style>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h2 class="dashboard-title">Selamat Datang di Dashboard</h2>
    </div>

    <div class="user-info">
        <p class="user-email">Anda login sebagai: {$user['email']}</p>
    </div>


    <div style="text-align: center; margin-top: 30px;">
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>
EOT;

renderLayout($content);
?>