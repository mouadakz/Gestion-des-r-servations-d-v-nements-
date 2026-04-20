<?php
session_start();
require 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("
    SELECT events.title, events.date_event, events.location, events.price
    FROM reservations
    JOIN events ON events.id = reservations.event_id
    WHERE reservations.user_id = ?
    ORDER BY events.date_event ASC
");
$stmt->execute([$_SESSION['user_id']]);
$data = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes réservations</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Mes réservations</h1>

<p>Bonjour, <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?> !</p> 

<a href="index.php">← Retour aux événements</a> |
<a href="logout.php">Déconnexion</a>

<?php if(empty($data)): ?>
    <p>Vous n'avez aucune réservation pour le moment.</p>
<?php else: ?>
    <?php foreach($data as $d): ?>
        <div class="card">
            <p><strong><?= htmlspecialchars($d['title']) ?></strong></p>     
            <p> <?= htmlspecialchars($d['date_event']) ?></p>
            <p> <?= htmlspecialchars($d['location']) ?></p>                  
            <p> <?= htmlspecialchars($d['price']) ?> DH</p>            
        </div>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>