<?php
// ✅ Affichage des erreurs pour t’aider en développement
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$usersFile = __DIR__ . '/users.json';
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (file_exists($usersFile)) {
        $jsonData = file_get_contents($usersFile);
        $users = json_decode($jsonData, true);

        if ($users === null) {
            $erreur = "Erreur de lecture JSON : " . json_last_error_msg();
        } else {
            foreach ($users as $user) {
                if ($user['login'] === $email && password_verify($password, $user['password'])) {
                    
                    $_SESSION['user'] = $user;

                    if ($user['role'] === 'admin') {
                        header("Location: admin.php");
                    } else {
                        header("Location: accueil.php");
                    }
                    exit();
                }
            }

            $erreur = "Identifiants incorrects.";
        }
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
    <link id="theme-stylesheet" rel="stylesheet" href="my_trips.css">
    <script src="theme.js" defer></script>
    <script src="password-toggle.js" defer></script>
</head>
<body class="page-connexion">

<nav>
    <div class="logo"><img alt="My Trips Logo" src="logo_my_trips.png"></div>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="présentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <li><a class="active" href="connexion.php">Se connecter</a></li>
        <li><a href="inscription.php">S'inscrire</a></li>
        <li><a class="btn-primary" href="reserver.php">Réserver</a></li>
        <li>
            <button id="themeToggle" class="btn-primary"
                    style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">
                🌓
            </button>
        </li>
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
<div class="password-container">
    <input type="password" name="password" id="password" required>
    <span onclick="togglePassword('password')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">👁️</span>
</div>


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
