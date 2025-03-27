<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');  // Redirige l'utilisateur vers la page de connexion s'il n'est pas connecté
    exit;
}

// Charger les voyages (depuis un fichier JSON ou base de données)
function loadTrips() {
    $file = __DIR__ . '/../data/trips.json'; // Ajustez le chemin si nécessaire
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

    // Exemple de sauvegarde dans un fichier JSON
    $resFile = __DIR__ . '/../data/reservations.json';
    $reservations = file_exists($resFile) ? json_decode(file_get_contents($resFile), true) : [];
    $reservations[] = $reservation;
    file_put_contents($resFile, json_encode($reservations, JSON_PRETTY_PRINT));

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
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="presentation.php">Présentation</a></li>
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
    <?php
    // Ici, $trips devrait contenir des données au format :
    // [
    //   ["id"=>1, "titre"=>"Voyage A", "prix"=>..., "duree"=>..., "image"=>"..."],
    //   ...
    // ]
    // Adaptez le code d'affichage en fonction de la structure réelle.
    foreach ($trips as $index => $trip) {
        // Ajustez les clés si besoin (titre => title, prix => price, etc.)
        $titre = $trip['titre'] ?? $trip['title'] ?? "Sans Titre";
        $prix  = $trip['prix'] ?? $trip['price'] ?? "N/A";
        $duree = $trip['duree'] ?? 10;
        $img   = $trip['image'] ?? "https://via.placeholder.com/300x200";
        $id    = $trip['id'] ?? $index;
        ?>
        <div class="trip-card">
            <img alt="<?php echo htmlspecialchars($titre); ?>" src="<?php echo htmlspecialchars($img); ?>"/>
            <h3><?php echo htmlspecialchars($titre); ?></h3>
            <p>Prix: <?php echo htmlspecialchars($prix); ?>€</p>
            <p>Durée: <?php echo htmlspecialchars($duree); ?> jours</p>
            <form method="POST">
                <input type="hidden" name="trip_id" value="<?php echo $id; ?>">
                <button type="submit" class="btn-primary">Réserver</button>
            </form>
        </div>
        <?php
    }
    ?>
  </section>

  <footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
  </footer>
</body>
</html>
