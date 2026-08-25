<?php
require 'includes/auth.php';
require 'config/database.php';

$userStmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$userStmt->execute([$_SESSION['user_id']]);
$currentUser = $userStmt->fetch(PDO::FETCH_ASSOC);

$success = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email.";
    }

    if (empty($errors)) {

        $stmt = $conn->prepare("
            INSERT INTO contact_messages(name,email,subject,message)
            VALUES(?,?,?,?)
        ");

        $stmt->execute([$name,$email,$subject,$message]);

        $success = "Message submitted successfully.";

    }

}

include 'includes/header.php';

include 'includes/navbar.php';
?>

<div class="container">

<div class="card">

<h1>Contact Us</h1>

<?php

foreach($errors as $error){
    echo "<p class='error'>".htmlspecialchars($error)."</p>";
}

if($success){
    echo "<p class='success'>$success</p>";
}

?>

<form method="POST">

<input
type="text"
name="name"
value="<?= htmlspecialchars($currentUser['username']) ?>"
readonly>

<input
type="email"
name="email"
value="<?= htmlspecialchars($currentUser['email']) ?>"
readonly>

<input type="text" name="subject" placeholder="Subject">

<textarea name="message" rows="6" placeholder="Message"></textarea>

<button type="submit">Send Message</button>

</form>


</div>

</div>

<?php
include 'includes/footer.php';
?>