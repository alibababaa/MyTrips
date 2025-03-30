<?php
session_start();

$usersFile = 'users.json';
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (file_exists($usersFile)) {
        $users = json_decode(file_get_contents($usersFile), true);

        foreach ($users as $user) {
            if ($user['login'] === $email && $user['password'] === $password) {
                $_SESSION['user'] = $user;

                // 🔁 Redirection selon le rôle
                if (isset($user['role']) && $user['role'] === 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: accueil.php");
                }
                exit();
            }
        }

        $erreur = "Identifiants incorrects.";
    } else {
        $erreur = "Fichier utilisateurs introuvable.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - My Trips</title>
    <link rel="stylesheet" href="my_trips.css">
</head>
<body>

<nav>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="présentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <li><a href="inscription.php">S'inscrire</a></li>
    </ul>
</nav>

<header class="banner">
    <div class="banner-content">
        <h1>Connexion</h1>
        <p>Connectez-vous à votre compte</p>
    </div>
</header>

<section class="login-section">
    <h2>Se connecter</h2>

    <?php if ($erreur): ?>
        <p style="color: red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form action="connexion.php" method="POST">
        <label for="email">Identifiant :</label>
        <input type="text" name="email" id="email" required>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" required>

        <button type="submit" class="btn-primary">Connexion</button>
    </form>

    <div class="forgot-password">
        <a href="#">Mot de passe oublié ?</a>
    </div>

    <div class="register-link">
        <p>Pas encore de compte ? <a href="inscription.php">S'inscrire</a></p>
    </div>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>
</body>
</html>
