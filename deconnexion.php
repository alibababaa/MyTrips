<?php
session_start();

// Récupérer l'URL de la page précédente
$previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';

// Vérifier si l'utilisateur est connecté avant de tenter une déconnexion
if (isset($_SESSION['user'])) {
    // Détruire toutes les variables de session
    $_SESSION = array();

    // Supprimer le cookie de session si nécessaire
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Détruire la session
    session_destroy();
}

// Rediriger vers la page précédente
header("Location: $previous_page");
exit();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déconnexion - My Trips</title>
    <link href="my_trips.css" rel="stylesheet">
</head>
<body>

<!-- Navigation -->
<nav>
    <div class="logo"><img alt="My Trips Logo" src="logo_my_trips.png"></div>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="présentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        
        <?php if (isset($_SESSION['user'])): ?>
            <li><a href="mon_profil.php">Mon Profil</a></li>
            <li><a href="deconnexion.php">Se déconnecter</a></li>
        <?php else: ?>
            <li><a href="inscription.php">S'inscrire</a></li>
            <li><a href="connexion.php">Se connecter</a></li>
        <?php endif; ?>
        
        <li><a class="btn-primary" href="reserver.php">Réserver</a></li>
    </ul>
</nav>

<!-- Message de déconnexion -->
<section class="logout-section">
    <h2>Déconnexion</h2>
    <p>Vous avez été déconnecté avec succès.</p>
    <a class="btn-primary" href="<?php echo htmlspecialchars($previous_page); ?>">Retour à la page précédente</a>
</section>

<!-- Footer -->
<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>

</body>
</html>
