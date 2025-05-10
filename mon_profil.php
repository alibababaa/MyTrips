<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$reservationsPath = __DIR__ . '/transactions.json';
$tripsPath = __DIR__ . '/trips.json';

$reservations = file_exists($reservationsPath) ? json_decode(file_get_contents($reservationsPath), true) : [];
$trips = file_exists($tripsPath) ? json_decode(file_get_contents($tripsPath), true) : [];

$userLogin = $_SESSION['user']['login'] ?? '';
$userTrips = array_filter($reservations, fn($res) => $res['user_id'] === $userLogin);
usort($userTrips, fn($a, $b) => strtotime($b['payment_date']) <=> strtotime($a['payment_date']));

function findTripById($trips, $id) {
    foreach ($trips as $trip) {
        if ($trip['id'] == $id) return $trip;
    }
    return null;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
    <link id="theme-stylesheet" rel="stylesheet" href="my_trips.css">
    <script src="theme.js" defer></script>

<style>
    input.form-field {
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }
    input.form-field:focus {
        background-color: #ffffff;
        box-shadow: 0 0 4px #0a9396;
        outline: none;
    }
</style>

</head>
<body>
<nav>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <li><a href="confirmation.php">Mes Réservations</a></li>
        <li><a href="deconnexion.php">Déconnexion</a></li>
    </ul>
</nav>

<header class="banner">
    <div class="banner-content">
        <h1>Mes Réservations</h1>
    </div>
</header>

<section class="profile-fields" style="max-width: 800px; margin: 2em auto;">
    <h2>Mes informations</h2>
    <form id="profil-form">
        <?php
        $utilisateur = $_SESSION['user'];
        $champs = ['login' => 'Identifiant', 'nom' => 'Nom', 'prenom' => 'Prénom', 'email' => 'Email'];
        foreach ($champs as $champ => $label):
            $valeur = htmlspecialchars($utilisateur[$champ] ?? '');
        ?>
        <div style="margin-bottom: 1em;">
            <label><strong><?= $label ?> :</strong></label><br>
            <input type="text" id="champ_<?= $champ ?>" value="<?= $valeur ?>" disabled class="form-field">
            <button type="button" class="btn-primary" onclick="activerChamp('<?= $champ ?>')">✏️ Modifier</button>
            <button type="button" class="btn-primary" onclick="validerChamp('<?= $champ ?>')" style="display:none;">✅ Valider</button>
            <button type="button" class="btn-primary" onclick="annulerChamp('<?= $champ ?>')" style="display:none;">❌ Annuler</button>
        </div>
        <?php endforeach; ?>
    </form>
</section>

<script>
function activerChamp(champ) {
    const input = document.getElementById('champ_' + champ);
    const buttons = input.parentElement.querySelectorAll('button');
    input.disabled = false;
    input.dataset.initial = input.value;
    buttons[0].style.display = 'none';  // Modifier
    buttons[1].style.display = 'inline'; // Valider
    buttons[2].style.display = 'inline'; // Annuler
}

function validerChamp(champ) {
    const input = document.getElementById('champ_' + champ);
    input.disabled = true;
    const buttons = input.parentElement.querySelectorAll('button');
    buttons[0].style.display = 'inline'; // Modifier
    buttons[1].style.display = 'none';   // Valider
    buttons[2].style.display = 'none';   // Annuler
}

function annulerChamp(champ) {
    const input = document.getElementById('champ_' + champ);
    input.value = input.dataset.initial || input.value;
    input.disabled = true;
    const buttons = input.parentElement.querySelectorAll('button');
    buttons[0].style.display = 'inline'; // Modifier
    buttons[1].style.display = 'none';   // Valider
    buttons[2].style.display = 'none';   // Annuler
}
</script>


<section class="trip-summary" style="max-width: 800px; margin: auto;">
    <?php if (empty($userTrips)): ?>
        <p>Vous n'avez encore effectué aucune réservation.</p>
    <?php else: ?>
        <?php foreach ($userTrips as $res): 
            $trip = findTripById($trips, $res['trip_id']);
            if (!$trip) continue;
        ?>
            <div style="border: 1px solid #ccc; padding: 1em; margin-bottom: 1em;">
                <p><strong>Voyage :</strong> <?= htmlspecialchars($trip['titre']) ?></p>
                <p><strong>Durée :</strong> <?= htmlspecialchars($trip['duree']) ?> jours</p>
                <p><strong>Date de paiement :</strong> <?= htmlspecialchars($res['payment_date']) ?></p>
                <p><strong>Montant payé :</strong> <?= number_format($res['montant'], 2) ?> €</p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<footer>
    <p>&copy; 2025 My Trips. Tous droits réservés.</p>
</footer>
</body>
</html>

    
        
