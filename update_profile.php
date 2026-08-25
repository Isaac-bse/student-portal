<?php
require 'includes/auth.php';
require 'config/database.php';

$errors = [];
$success = "";

$stmt = $conn->prepare("SELECT username,email FROM users WHERE id=?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$username = $user['username'];
$email = $user['email'];

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);

    if(empty($username)){
        $errors[]="Username is required.";
    }

    if(empty($email)){
        $errors[]="Email is required.";
    }

    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errors[]="Invalid email address.";
    }

    if(empty($errors)){

        $check=$conn->prepare("
        SELECT id FROM users
        WHERE email=?
        AND id!=?
        ");

        $check->execute([
            $email,
            $_SESSION['user_id']
        ]);

        if($check->rowCount()>0){

            $errors[]="Email already exists.";

        }else{

            $update=$conn->prepare("
            UPDATE users
            SET username=?, email=?
            WHERE id=?
            ");

            $update->execute([
                $username,
                $email,
                $_SESSION['user_id']
            ]);

            $_SESSION['username']=$username;

            $success="Profile updated successfully.";

        }

    }

}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container">

<div class="card">

<h1>Edit Profile</h1>

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
name="username"
value="<?= htmlspecialchars($username) ?>"
>

<input
type="email"
name="email"
value="<?= htmlspecialchars($email) ?>"
>

<button type="submit">

Save Changes

</button>

</form>

</div>

</div>

<?php
include 'includes/footer.php';
?>