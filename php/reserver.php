<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

function loadTrips() {
    $file = '../data/trips.json';
    if (!file_exists($file)) {
        return [];
    }
    return json_decode(file_get_contents($file), true);
}

$trips = loadTrips();
$reservation_message = "";

// Traitement de la réservation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['trip_id'])) {
    $tripId = $_POST['trip_id'];
    $userId = $_SESSION['user']['login'];

    $reservation = [
        'user' => $userId,
        'trip_id' => $tripId,
        'date' => date('Y-m-d H:i:s'),
    ];

    $reservations_file = '../data/reservations.json';
    $reservations = file_exists($reservations_file) ? json_decode(file_get_contents($reservations_file), true) : [];
    $reservations[] = $reservation;

    file_put_contents($reservations_file, json_encode($reservations, JSON_PRETTY_PRINT));

    $reservation_message = "<p class='success'>Réservation réussie !</p>";
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
        <?php if (!empty($trips)): ?>
            <?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

function loadTrips() {
    $file = '../data/trips.json';
    if (!file_exists($file)) {
        return [];
    }
    return json_decode(file_get_contents($file), true);
}

$trips = loadTrips();
$reservation_message = "";

// Traitement de la réservation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['trip_id'])) {
    $tripId = $_POST['trip_id'];
    $userId = $_SESSION['user']['login'];

    $reservation = [
        'user' => $userId,
        'trip_id' => $tripId,
        'date' => date('Y-m-d H:i:s'),
    ];

    $reservations_file = '../data/reservations.json';
    $reservations = file_exists($reservations_file) ? json_decode(file_get_contents($reservations_file), true) : [];
    $reservations[] = $reservation;

    file_put_contents($reservations_file, json_encode($reservations, JSON_PRETTY_PRINT));

    $reservation_message = "<p class='success'>Réservation réussie !</p>";
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
        <?php if (!empty($trips)): ?>
            <?php foreach ($trips as $trip): ?>
                <div class="trip-card">
                    <img alt="<?php echo $trip['titre']; ?>" src="<?php echo $trip['image']; ?>">
                    <h3><?php echo $trip['titre']; ?></h3>
                    <p>Prix: <?php echo $trip['prix']; ?>€</p>
                    <p>Durée: <?php echo $trip['duree']; ?> jours</p>
                    <form method="POST">
                        <input type="hidden" name="trip_id" value="<?php echo $trip['id']; ?>">
                        <button type="submit" class="btn-primary">Réserver</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun voyage disponible pour le moment.</p>
        <?php endif; ?>
    </section>
  </div>

  <footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
  </footer>
</body>
</html>

        
