<?php
session_start(); 
require 'config.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id']   = $user['id']; 
        $_SESSION['user_name'] = $user['name']; 
        header("Location: index.php");
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Connexion</h1>

<?php if($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST">
    <input name="email"    type="email"    placeholder="Email"         required>  
    <input name="password" type="password" placeholder="Mot de passe"  required>
    <button type="submit">Se connecter</button>
</form>

<p>Pas encore de compte ? <a href="signup.php">S'inscrire</a></p>

</body>
</html>