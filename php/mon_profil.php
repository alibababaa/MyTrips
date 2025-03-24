<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');  // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit;
}

// Charger les informations de l'utilisateur
$user = $_SESSION['user'];

// Charger les réservations de l'utilisateur
function loadReservations() {
    $file = '../data/reservations.json';
    if (!file_exists($file)) {
        return [];
    }
    return json_decode(file_get_contents($file), true);
}

$reservations = loadReservations();

// Filtrer les réservations de l'utilisateur
$userReservations = array_filter($reservations, function($reservation) use ($user) {
    return $reservation['user'] == $user['login'];
});
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Mon Profil - My Trips</title>
    <link href="my_trips.css" rel="stylesheet"/>
</head>
<body>
  <nav>
    <!-- Menu Navigation -->
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="présentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <li><a href="mon_profil.php">Mon Profil</a></li>
        <?php if (isset($_SESSION['user'])): ?>
            <li><a href="deconnexion.php">Se déconnecter</a></li>
        <?php else: ?>
            <li><a href="inscription.php">S'inscrire</a></li>
            <li><a href="connexion.php">Se connecter</a></li>
        <?php endif; ?>
    </ul>
  </nav>

  <header>
    <h1>Bienvenue, <?php echo htmlspecialchars($user['name']); ?>!</h1>
  </header>

  <section class="user-profile">
    <h2>Informations personnelles</h2>
    <p><strong>Nom:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
    <p><strong>Login:</strong> <?php echo htmlspecialchars($user['login']); ?></p>
    <!-- Vous pouvez ajouter des options pour modifier les informations du profil -->

    <h2>Mes Réservations</h2>
    <?php if (count($userReservations) > 0): ?>
        <ul>
        <?php foreach ($userReservations as $reservation): ?>
            <li>
                <p><strong>Voyage ID:</strong> <?php echo $reservation['trip_id']; ?></p>
                <p><strong>Date de réservation:</strong> <?php echo $reservation['date']; ?></p>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucune réservation trouvée.</p>
    <?php endif; ?>
  </section>

  <footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
  </footer>
</body>
</html>
