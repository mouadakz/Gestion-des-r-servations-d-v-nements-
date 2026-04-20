<?php
session_start();
require 'config.php';

$events = $pdo->query("SELECT * FROM events WHERE date_event >= CURDATE()")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Événements</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Événements</h1>

<?php if(isset($_SESSION['user_id'])): ?>
    <a href="dashboard.php">Mes réservations</a> |
    <a href="logout.php">Logout</a>
<?php else: ?>
    <a href="login.php">Login</a> |
    <a href="signup.php">Signup</a>
<?php endif; ?>

<div class="container">

<?php foreach($events as $e): ?>
    <div class="card">
        <h3><?= htmlspecialchars($e['title']) ?></h3>
        <p><?= htmlspecialchars($e['date_event']) ?></p>
        <p><?= htmlspecialchars($e['location']) ?></p>
        <p><?= htmlspecialchars($e['price']) ?> DH</p>
        <p>Places: <?= (int)$e['nbPlaces'] ?></p>

        <?php if($e['nbPlaces'] == 0): ?>
            <p class="sold">COMPLET</p>

        <?php elseif(!isset($_SESSION['user_id'])): ?>
            <a href="login.php">Connectez-vous pour réserver</a>

        <?php else: ?>
            <form method="POST" action="reserve.php">
                <input type="hidden" name="event_id" value="<?= (int)$e['id'] ?>">
                <button type="submit">Réserver</button>
            </form>
        <?php endif; ?>

    </div>
<?php endforeach; ?>

</div>

</body>
</html> 