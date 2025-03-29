<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');  // Redirige l'utilisateur vers la page de connexion s'il n'est pas connecté.
    exit;
}

// Charger les voyages (fichier JSON ou base de données)
function loadTrips() {
    $file = '../data/trips.json';
    if (!file_exists($file)) {
        return [];
    }
    return json_decode(file_get_contents($file), true);
}

$trips = loadTrips();

// Traitement de la réservation (lorsqu'un utilisateur sélectionne un voyage)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['trip_id'])) {
    $tripId = $_POST['trip_id'];
    $userId = $_SESSION['user']['login']; // Utilisateur connecté

    // Enregistrer la réservation (à ajouter à un fichier ou une base de données)
    $reservation = [
        'user' => $userId,
        'trip_id' => $tripId,
        'date' => date('Y-m-d H:i:s'),
    ];

    // Sauvegarde dans un fichier JSON ou une base de données
    $reservations = json_decode(file_get_contents('../data/reservations.json'), true) ?? [];
    $reservations[] = $reservation;
    file_put_contents('../data/reservations.json', json_encode($reservations, JSON_PRETTY_PRINT));

    echo "<p>Réservation réussie !</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Réserver - My Trips</title>
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
        <li><a class="btn-primary" href="reserver.php">Réserver</a></li>
    </ul>
  </nav>

  <header>
    <h1>Choisissez votre voyage</h1>
  </header>

  <section class="trip-list">
    <?php foreach ($trips as $index => $trip): ?>
    <div class="trip-card">
        <h3><?php echo htmlspecialchars($trip['title']); ?></h3>
        <p>Prix: <?php echo htmlspecialchars($trip['price']); ?>€</p>
        <p>Dates: du <?php echo htmlspecialchars($trip['dates']['start']); ?> au <?php echo htmlspecialchars($trip['dates']['end']); ?></p>
        <form method="POST">
            <input type="hidden" name="trip_id" value="<?php echo $index; ?>">
            <button type="submit" class="btn-primary">Réserver</button>
        </form>
    </div>
<?php endforeach; ?>

  </section>

  <footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
  </footer>
</body>
</html>
