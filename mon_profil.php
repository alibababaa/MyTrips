<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

// Charger les voyages de l'utilisateur (transactions + trips).
function loadUserTrips($userId) {
    $transactionsFile = __DIR__ . '/../data/transactions.json';
    $tripsFile        = __DIR__ . '/../data/trips.json';

    if (!file_exists($transactionsFile) || !file_exists($tripsFile)) {
        return [];
    }
    $transactions = json_decode(file_get_contents($transactionsFile), true) ?? [];
    $trips       = json_decode(file_get_contents($tripsFile), true) ?? [];

    $userTrips = [];
    foreach ($transactions as $transaction) {
        if (isset($transaction['user_id']) && $transaction['user_id'] === $userId) {
            foreach ($trips as $trip) {
                if (isset($trip['id']) && $trip['id'] == $transaction['trip_id']) {
                    $userTrips[] = $trip;
                    break;
                }
            }
        }
    }
    return $userTrips;
}

// On suppose que $_SESSION['user'] = ['login'=>..., 'prenom'=>..., 'role'=>...]
$userId  = $_SESSION['user']['login'];
$prenom  = $_SESSION['user']['prenom'];  // exemple
$trips   = loadUserTrips($userId);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Mon Profil - My Trips</title>
    <link href="my_trips.css" rel="stylesheet"/>
</head>
<body>
    <nav>
      <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="presentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <li><a class="active" href="mon_profil.php">Mon Profil</a></li>
        <li><a href="deconnexion.php">Se déconnecter</a></li>
      </ul>
    </nav>
    
    <header>
        <h1>Bienvenue, <?php echo htmlspecialchars($prenom); ?></h1>
    </header>

    <section class="user-trips">
        <h2>Mes Voyages Payés</h2>
        <?php if (empty($trips)): ?>
            <p>Vous n'avez pas encore réservé de voyage.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($trips as $trip): ?>
                    <li>
                        <?php
                        // adapter selon votre structure JSON
                        $titre       = $trip['titre']       ?? ($trip['title'] ?? 'Titre inconnu');
                        $date_debut  = $trip['date_debut']  ?? ($trip['dates']['start'] ?? 'N/A');
                        $date_fin    = $trip['date_fin']    ?? ($trip['dates']['end']   ?? 'N/A');
                        $tripId      = $trip['id']          ?? 0;
                        ?>
                        <a href="details_voyage.php?trip_id=<?php echo $tripId; ?>">
                            <?php echo htmlspecialchars($titre); ?>
                            (du <?php echo htmlspecialchars($date_debut); ?>
                            au <?php echo htmlspecialchars($date_fin); ?>)
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

    <footer>
        <p>&copy; 2025 My Trips. Tous droits réservés.</p>
    </footer>
</body>
</html>
