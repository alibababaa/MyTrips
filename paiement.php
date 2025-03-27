<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');  
    exit;
}

// Charger les informations du voyage sélectionné
if (!isset($_GET['trip_id'])) {
    die('Aucun voyage sélectionné');
}

// Exemple de fonction loadTrips()
function loadTrips() {
    $file = __DIR__ . '/../data/trips.json';
    if (!file_exists($file)) {
        return [];
    }
    return json_decode(file_get_contents($file), true);
}

$tripId = $_GET['trip_id'];
$trips = loadTrips(); 
$selectedTrip = null;

foreach ($trips as $trip) {
    // Adaptez selon votre structure JSON (title vs titre, price vs prix, etc.)
    // ex. $trip['id'] => l'identifiant du voyage
    if (isset($trip['id']) && $trip['id'] == $tripId) {
        $selectedTrip = $trip;
        break;
    }
}

// Si on n'a pas trouvé le voyage
if (!$selectedTrip) {
    die('Voyage introuvable');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Paiement - My Trips</title>
    <link href="my_trips.css" rel="stylesheet"/>
</head>
<body>
  <nav>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="presentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <li><a href="mon_profil.php">Mon Profil</a></li>
        <li><a href="deconnexion.php">Se déconnecter</a></li>
        <li><a class="btn-primary" href="reserver.php">Réserver</a></li>
    </ul>
  </nav>

  <header>
    <h1>Récapitulatif du Voyage</h1>
  </header>

  <section class="trip-summary">
    <h2><?php echo htmlspecialchars($selectedTrip['title'] ?? $selectedTrip['titre']); ?></h2>
    <p><strong>Dates :</strong> <?php 
      // Affichage exemple
      if (isset($selectedTrip['dates'])) {
         echo htmlspecialchars($selectedTrip['dates']['start'] . " - " . $selectedTrip['dates']['end']);
      }
    ?></p>
    <p><strong>Prix :</strong> <?php echo htmlspecialchars($selectedTrip['price'] ?? $selectedTrip['prix']); ?> €</p>
  </section>

  <h2>Formulaire de Paiement</h2>
  <form action="verification.php" method="POST">
    <h3>Coordonnées Bancaires</h3>
    <label for="card_number">Numéro de carte (16 chiffres)</label>
    <input type="text" name="card_number" id="card_number" pattern="\d{16}" required placeholder="1234567812345678"/>

    <label for="card_owner">Nom et prénom du propriétaire de la carte</label>
    <input type="text" name="card_owner" id="card_owner" required placeholder="Jean Dupont"/>

    <label for="expiry_date">Date d'expiration (MM/AA)</label>
    <input type="text" name="expiry_date" id="expiry_date" pattern="\d{2}/\d{2}" required placeholder="MM/AA"/>

    <label for="cvv">Valeur de contrôle (CVV - 3 chiffres)</label>
    <input type="text" name="cvv" id="cvv" pattern="\d{3}" required placeholder="123"/>

    <input type="hidden" name="trip_id" value="<?php echo $tripId; ?>"/>

    <button type="submit" class="btn-primary">Payer</button>
  </form>

  <footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
  </footer>
</body>
</html>
