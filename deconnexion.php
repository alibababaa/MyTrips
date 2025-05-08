<?php
session_start();

$previous_page = $_SERVER['HTTP_REFERER'] ?? 'accueil.php';

if (isset($_SESSION['user'])) {
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Déconnexion - My Trips</title>
    <link id="theme-stylesheet" rel="stylesheet" href="my_trips.css">
    <script src="theme.js" defer></script>
</head>
<body class="page-deconnexion">

<nav>
    <div class="logo"><img alt="My Trips Logo" src="logo_my_trips.png"></div>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="présentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <li><a href="connexion.php">Se connecter</a></li>
        <li><a href="inscription.php">S'inscrire</a></li>
        <li><a class="btn-primary" href="reserver.php">Réserver</a></li>
        <li>
            <button id="themeToggle" class="btn-primary" style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">
                🌓
            </button>
        </li>
    </ul>
</nav>

<header class="banner">
    <div class="banner-content">
        <h1>Déconnexion</h1>
        <p>Vous avez été déconnecté avec succès.</p>
    </div>
</header>

<section class="logout-section" style="text-align: center; margin-top: 2em;">
    <a class="btn-primary" href="<?= htmlspecialchars($previous_page) ?>">← Retour à la page précédente</a>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>

</body>
</html>
