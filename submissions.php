<?php
require 'includes/auth.php';
require 'config/database.php';

$search = "";

if(isset($_GET['search'])){

    $search = trim($_GET['search']);

    $stmt = $conn->prepare("
        SELECT * FROM contact_messages
        WHERE 
        name LIKE ?
        OR email LIKE ?
        OR subject LIKE ?
        ORDER BY date_sent DESC
    ");

    $keyword = "%".$search."%";

    $stmt->execute([
        $keyword,
        $keyword,
        $keyword
    ]);

}else{

    $stmt = $conn->query("
        SELECT * FROM contact_messages
        ORDER BY date_sent DESC
    ");

}


$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container">

<div class="card">

<h1>Submitted Messages</h1>

<form method="GET">

<input 
type="text"
name="search"
placeholder="Search messages..."
value="<?= htmlspecialchars($search) ?>"
>

<button type="submit">
Search
</button>

</form>

<br>

<table>

<tr>

<th>Name</th>

<th>Email</th>

<th>Subject</th>

<th>Message</th>

<th>Date</th>

</tr>

<?php foreach($messages as $message): ?>

<tr>

<td><?= htmlspecialchars($message['name']) ?></td>

<td><?= htmlspecialchars($message['email']) ?></td>

<td><?= htmlspecialchars($message['subject']) ?></td>

<td><?= htmlspecialchars($message['message']) ?></td>

<td><?= htmlspecialchars($message['date_sent']) ?></td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

<?php
include 'includes/footer.php';
?>