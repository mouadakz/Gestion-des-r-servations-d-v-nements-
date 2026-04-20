<?php
session_start();
require 'config.php';
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){ 
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if($check->fetch()){
        $error = "Cet email est déjà utilisé.";
    } else {
        $pdo->prepare("INSERT INTO users(name, email, password) VALUES(?, ?, ?)")
            ->execute([$name, $email, $pass]);

        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Inscription</h1>

<?php if($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST">
    <input name="name"     placeholder="Nom"            required>
    <input name="email"    placeholder="Email" type="email" required> 
    <input name="password" placeholder="Mot de passe" type="password" required>
    <button type="submit">S'inscrire</button>
</form>

<p>Déjà un compte ? <a href="login.php">Se connecter</a></p>

</body>
</html>