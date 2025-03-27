<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');  // Si non connecté, rediriger vers la page de connexion
    exit;
}

// Ex. de fonction de chargement des voyages pour l'utilisateur
function loadUserTrips($userId) {
    // À vous d'adapter selon votre logique :
    // Ex. si vous stockez dans un JSON, vous pouvez filtrer par userId
    // Pour l'instant, on renvoie un tableau d'exemple :
    return [
        [
            'id' => 1,
            'titre' => 'Voyage de test',
            'date_debut' => '2025-06-01',
            'date_fin'   => '2025-06-10'
        ],
        // ...
    ];
}

$userId = $_SESSION['user']['login'];  // Identifiant de l'utilisateur connecté
$trips = loadUserTrips($userId);
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
      <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="presentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <li><a class="active" href="mon_profil.php">Mon Profil</a></li>
        <li><a href="deconnexion.php">Se déconnecter</a></li>
      </ul>
    </nav>

    <header>
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['user']['prenom']); ?></h1>
    </header>

    <section class="user-trips">
        <h2>Mes Voyages Payés</h2>
        <?php if (empty($trips)): ?>
            <p>Vous n'avez pas encore réservé de voyage.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($trips as $trip): ?>
                    <li>
                        <a href="trip_details.php?trip_id=<?php echo $trip['id']; ?>">
                            <?php echo htmlspecialchars($trip['titre']); ?>
                            (<?php echo htmlspecialchars($trip['date_debut']); ?> - <?php echo htmlspecialchars($trip['date_fin']); ?>)
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

    <footer>
        <p>© 2025 My Trips. Tous droits réservés.</p>
    </footer>
</body>
</html>
