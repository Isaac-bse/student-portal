<?php
session_start();
require 'config/database.php';

$email = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Validation
    if (empty($email) || empty($password)) {
        $errors[] = "Please enter both email and password.";
    }

    if (empty($errors)) {

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() == 1) {

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header("Location: dashboard.php");
                exit();

            } else {

                $errors[] = "Incorrect password.";

            }

        } else {

            $errors[] = "Account not found.";

        }

    }

}

include 'includes/header.php';
?>

<div class="center">

    <div class="card">

        <h1>Login</h1>

        <?php
        if(isset($_GET['registered']))
        {
            echo "<p class='success'>Registration successful. Please login.</p>";
        }

        foreach($errors as $error)
        {
            echo "<p class='error'>".htmlspecialchars($error)."</p>";
        }
        ?>

        <form method="POST">

            <input
                type="email"
                name="email"
                placeholder="Email"
                value="<?= htmlspecialchars($email) ?>"
            >

            <input
                type="password"
                name="password"
                placeholder="Password"
            >

            <button type="submit">
                Login
            </button>

        </form>

        <br>

        <p>
            Don't have an account?
            <a href="register.php">Register</a>
        </p>

    </div>

</div>

<?php
include 'includes/footer.php';
?>