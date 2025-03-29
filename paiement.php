<?php

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}


// Charger les informations du voyage sélectionné
if (!isset($_GET['trip_id'])) {
    die('Aucun voyage sélectionné');
}

$tripId = $_GET['trip_id'];
$trips = loadTrips();  // Fonction de chargement des voyages
$selectedTrip = null;

foreach ($trips as $trip) {
    if ($trip['id'] == $tripId) {
        $selectedTrip = $trip;
        break;
    }
}

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
        <li><a href="présentation.php">Présentation</a></li>
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
    <h2><?php echo htmlspecialchars($selectedTrip['titre']); ?></h2>
    <p><strong>Dates:</strong> <?php echo htmlspecialchars($selectedTrip['date_debut']); ?> - <?php echo htmlspecialchars($selectedTrip['date_fin']); ?></p>
    <p><strong>Prix:</strong> <?php echo htmlspecialchars($selectedTrip['prix']); ?>€</p>
  </section>

  <h2>Formulaire de Paiement</h2>
  <form action="verification_paiement.php" method="POST">
    <h3>Coordonnées Bancaires</h3>
    <label for="card_number">Numéro de carte (16 chiffres)</label>
    <input type="text" name="card_number" id="card_number" pattern="\d{16}" required placeholder="1234 5678 1234 5678"/>

    <label for="card_owner">Nom et prénom du propriétaire de la carte</label>
    <input type="text" name="card_ownPage de paiement. Sur cette page ne doit apparaitre qu’un petit
récapitulatif du voyage (avec à minima, le titre du voyage, les
dates de début et fin, ainsi qu’un formulaire demandant de saisir
des coordonnées bancaires :
• numéro de carte à bancaire à 16 chiffres (4 x 4)
• nom et prénom du propriétaire de la carte
• mois et année d’expiration
• valeur de contrôle à 3 chiffres
Le script en destinataire de ce formulaire (et qui vous sera fourni)
simulera la vérification des coordonnées bancaires.
◦ Si les coordonnées sont valides, alors le script de vérification va
rediriger vers un script à vous, qui va enregistrer la transaction de
paiement (coordonnées bancaires, identifiants de l’utilisateur, et
configuration complète du voyage) de manière à pouvoir garder
une traçabilité des commandes.
◦ Si les coordonnées bancaires sont non valides, le script de
vérification redirigera vers un autre script à vous, qui affichera un
message d’erreur et permettra de revenir sur la page de
paiement et/ou sur la page récapitulative du voyage pour
pouvoir retenter un autre paiement ou bien revenir modifier des
options du voyage (car le prix est trop élevé et même si les
coordonnées bancaires sont fausses, la vérification a échoué
pour cause de manque d’argent sur le compte)er" id="card_owner" required placeholder="Jean Dupont"/>

    <label for="expiry_date">Date d'expiration (MM/AA)</label>
    <input type="text" name="expiry_date" id="expiry_date" pattern="\d{2}/\d{2}" required placeholder="MM/AA"/>

    <label for="cvv">Valeur de contrôle (CVV - 3 chiffres)</label>
    <input type="text" name="cvv" id="cvv" pattern="\d{3}" required placeholder="123"/>

    <input type="hidden" name="trip_id" value="<?php echo $selectedTrip['id']; ?>"/>

    <button type="submit" class="btn-primary">Payer</button>
  </form>

  <footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
  </footer>
</body>
</html>
