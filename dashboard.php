<?php
require 'includes/auth.php';
require 'config/database.php';

$userCount = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();

$messageCount = $conn->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container">

    <h1>Welcome,
        <?= htmlspecialchars($_SESSION['username']) ?> 👋
    </h1>

    <br>

    <div class="dashboard-grid">

        <div class="dashboard-card">

            <h2><?= $userCount ?></h2>

            <p>Registered Users</p>

        </div>

        <div class="dashboard-card">

            <h2><?= $messageCount ?></h2>

            <p>Messages Received</p>

        </div>

        <div class="dashboard-card">

            <h2>5</h2>

            <p>System Pages</p>

        </div>

        <div class="dashboard-card">

            <h2>100%</h2>

            <p>Secure Login</p>

        </div>

    </div>

    <br>

    <div class="card">

        <h2>Quick Overview</h2>

        <br>

        <h1>Welcome back, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
        
        <p>
        You are successfully logged into the Student Resource Portal.
        Use the navigation menu above to access all system features.
        </p>

    </div>

</div>

<?php
include 'includes/footer.php';
?>