<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

// Fonction de chargement sécurisée des fichiers JSON
function loadJSON($file) {
    if (!file_exists($file)) {
        echo '<p style="color: red;">Erreur : Fichier ' . htmlspecialchars($file) . ' introuvable.</p>';
        return [];
    }
    
    $data = file_get_contents($file);
    if ($data === false) {
        echo '<p style="color: red;">Erreur : Impossible de lire le fichier ' . htmlspecialchars($file) . '.</p>';
        return [];
    }
    
    $json = json_decode($data, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo '<p style="color: red;">Erreur JSON : ' . json_last_error_msg() . '</p>';
        return [];
    }
    
    return is_array($json) ? $json : [];
}

$trips = loadJSON(__DIR__ . '/../data/trips.json');
$reservations = loadJSON(__DIR__ . '/../data/reservations.json');
$reservation_message = "";

// Debugging: Vérifier si les voyages sont bien chargés
if (empty($trips)) {
    echo '<p style="color: red;">Erreur : Aucun voyage chargé depuis trips.json</p>';
    echo '<pre>'; print_r($trips); echo '</pre>';
    exit;
}

// Traitement de la réservation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['trip_id'])) {
    $tripId = (string) $_POST['trip_id']; // Convertir en chaîne
    $userId = $_SESSION['user']['login'];

    // Vérifier si le trip existe
    if (isset($trips[$tripId])) {
        // Vérifier si l'utilisateur a déjà réservé ce voyage
        $alreadyReserved = array_filter($reservations, fn($res) => $res['user'] == $userId && $res['trip_id'] === $tripId);
        
        if (!$alreadyReserved) {
            $reservations[] = [
                'user' => $userId,
                'trip_id' => $tripId,
                'date' => date('Y-m-d H:i:s'),
            ];
            file_put_contents(__DIR__ . '/../data/reservations.json', json_encode($reservations, JSON_PRETTY_PRINT));
            $reservation_message = "<p class='success'>Réservation réussie !</p>";
        } else {
            $reservation_message = "<p class='error'>Vous avez déjà réservé ce voyage.</p>";
        }
    } else {
        $reservation_message = "<p class='error'>Voyage introuvable.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver - My Trips</title>
    <link href="my_trips.css" rel="stylesheet">
</head>
<body>
  <nav>
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

  <div class="container">
    <?php echo $reservation_message; ?>
    
    <section class="trip-list">
        <?php if (count($trips) > 0): ?>
            <?php foreach ($trips as $index => $trip): ?>
                <div class="trip-card">
                    <h3><?php echo htmlspecialchars($trip['title']); ?></h3>
                    <p>Prix: <?php echo htmlspecialchars($trip['price']); ?>€</p>
                    <p>Dates: <?php echo htmlspecialchars($trip['dates']['start']); ?> - <?php echo htmlspecialchars($trip['dates']['end']); ?></p>
                    <form method="POST">
                        <input type="hidden" name="trip_id" value="<?php echo (string) $index; ?>">
                        <button type="submit" class="btn-primary">Réserver</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: red;">Erreur : Aucun voyage disponible.</p>
        <?php endif; ?>
    </section>
  </div>

  <footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
  </footer>
</body>
</html>
