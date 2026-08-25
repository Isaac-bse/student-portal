<?php
session_start();
require 'config/database.php';

$username = "";
$email = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    // Validation
    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Check duplicate email
    if (empty($errors)) {

        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            $errors[] = "Email already exists.";
        }
    }

    // Insert user
    if (empty($errors)) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insert = $conn->prepare("
            INSERT INTO users(username,email,password)
            VALUES(?,?,?)
        ");

        if ($insert->execute([$username,$email,$hashedPassword])) {

            header("Location: login.php?registered=1");
            exit();

        } else {

            $errors[] = "Registration failed.";

        }

    }

}

include 'includes/header.php';
?>

<div class="center">

    <div class="card">

        <h1>Create Account</h1>

        <?php
        if(!empty($errors))
        {
            foreach($errors as $error)
            {
                echo "<p class='error'>".htmlspecialchars($error)."</p>";
            }
        }
        ?>

        <form method="POST">

            <input
                type="text"
                name="username"
                placeholder="Username"
                value="<?= htmlspecialchars($username) ?>"
            >

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

            <input
                type="password"
                name="confirm_password"
                placeholder="Confirm Password"
            >

            <button type="submit">
                Register
            </button>

        </form>

        <br>

        <p>
            Already have an account?
            <a href="login.php">Login</a>
        </p>

    </div>

</div>

<?php
include 'includes/footer.php';
?>