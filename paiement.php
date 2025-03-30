<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

// Vérification du trip_id envoyé depuis reserver.php
if (!isset($_POST['trip_id']) && !isset($_GET['trip_id'])) {
    exit("Erreur : Aucun voyage sélectionné.");
}

$tripId = $_POST['trip_id'] ?? $_GET['trip_id'];

// Chargement du fichier trips.json
$file_path = __DIR__ . '/trips.json';
$trips = json_decode(file_get_contents($file_path), true);

// Trouver les informations du voyage
$selectedTrip = null;
foreach ($trips as $trip) {
    if ($trip['id'] == $tripId) {
        $selectedTrip = $trip;
        break;
    }
}

if (!$selectedTrip) {
    exit("Erreur : Voyage introuvable.");
}

// Traitement du paiement uniquement après soumission du formulaire bancaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['card_number'], $_POST['card_owner'], $_POST['expiry_date'], $_POST['cvv'])) {

    // Logique réelle de vérification du paiement (à remplacer par une vraie vérification bancaire)
    $paymentSuccessful = true; // Simulation

    if ($paymentSuccessful) {
        // Enregistrement sécurisé dans transactions.json
        $transaction = [
            'user_id' => $_SESSION['user']['login'],
            'trip_id' => $selectedTrip['id'],
            'payment_date' => date('Y-m-d H:i:s'),
            'montant' => $selectedTrip['prix']
        ];

        $transactions_path = __DIR__ . '/transactions.json';
        $transactions = file_exists($transactions_path) ? json_decode(file_get_contents($transactions_path), true) : [];
        $transactions[] = $transaction;

        file_put_contents($transactions_path, json_encode($transactions, JSON_PRETTY_PRINT));

        echo "<p class='success'>Paiement réussi et réservation enregistrée.</p>";
        exit; // Important : évite que le formulaire ne réapparaisse après succès
    } else {
        echo "<p class='error'>Erreur lors du paiement. Veuillez réessayer.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Paiement - My Trips</title>
    <link rel="stylesheet" href="my_trips.css">
</head>
<body>
<nav>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="présentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <li><a href="mon_profil.php">Mon Profil</a></li>
        <li><a href="deconnexion.php">Se déconnecter</a></li>
    </ul>
</nav>

<header>
    <h1>Récapitulatif du Voyage</h1>
</header>

<section class="trip-summary">
    <h2><?= htmlspecialchars($selectedTrip['titre']) ?></h2>
    <p><strong>Durée :</strong> <?= htmlspecialchars($selectedTrip['duree']) ?> jours</p>
    <p><strong>Prix :</strong> <?= htmlspecialchars($selectedTrip['prix']) ?> €</p>
</section>

<h2>Formulaire de Paiement</h2>
<form action="paiement.php" method="POST">
    <h3>Coordonnées Bancaires</h3>

    <label for="card_number">Numéro de carte (16 chiffres)</label>
    <input type="text" name="card_number" id="card_number" pattern="\d{16}" required placeholder="1234567812345678">

    <label for="card_owner">Nom et prénom du propriétaire de la carte</label>
    <input type="text" name="card_owner" id="card_owner" required placeholder="Jean Dupont">

    <label for="expiry_date">Date d'expiration (MM/AA)</label>
    <input type="text" name="expiry_date" id="expiry_date" pattern="\d{2}/\d{2}" required placeholder="MM/AA">

    <label for="cvv">Valeur de contrôle (CVV - 3 chiffres)</label>
    <input type="text" name="cvv" id="cvv" pattern="\d{3}" required placeholder="123">

    <input type="hidden" name="trip_id" value="<?= htmlspecialchars($selectedTrip['id']) ?>">

    <button type="submit" class="btn-primary">Payer</button>
</form>

<footer>
    <p>&copy; 2025 My Trips. Tous droits réservés.</p>
</footer>

</body>
</html>
