<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Charger la liste des voyages
function loadTrips() {
    $file = __DIR__ . '/../data/trips.json';
    if (!file_exists($file)) {
        return [];
    }
    return json_decode(file_get_contents($file), true);
}

if (!isset($_GET['trip_id'])) {
    die("Aucun voyage sélectionné");
}

$tripId = $_GET['trip_id'];
$trips  = loadTrips();
$tripDetails = null;

foreach ($trips as $t) {
    if (isset($t['id']) && $t['id'] == $tripId) {
        $tripDetails = $t;
        break;
    }
}

if (!$tripDetails) {
    die("Voyage introuvable");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Détails du voyage</title>
    <link href="my_trips.css" rel="stylesheet"/>
</head>
<body>
    <h2><?php echo htmlspecialchars($tripDetails['title'] ?? $tripDetails['titre']); ?></h2>
    <p>Prix : <?php echo htmlspecialchars($tripDetails['price'] ?? $tripDetails['prix']); ?>€</p>
    <!-- Autres informations à afficher -->
    <p><a href="accueil.php">Retour à l'accueil</a></p>
</body>
</html>
