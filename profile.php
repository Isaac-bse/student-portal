<?php
require 'includes/auth.php';
require 'config/database.php';

$stmt = $conn->prepare("SELECT username, email, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container">

    <div class="card">

        <h1>My Profile</h1>

        <table>

            <tr>
                <th>Username</th>
                <td><?= htmlspecialchars($user['username']) ?></td>
            </tr>

            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($user['email']) ?></td>
            </tr>

            <tr>
                <th>Member Since</th>
                <td><?= htmlspecialchars($user['created_at']) ?></td>
            </tr>

        </table>

        <br>

        <a href="update_profile.php">
            <button>Edit Profile</button>
        </a>

    </div>

</div>

<?php
include 'includes/footer.php';
?>