<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mon Profil - My Trips</title>
  <link id="theme-stylesheet" rel="stylesheet" href="my_trips.css">
  <script src="theme.js" defer></script>
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600&display=swap" rel="stylesheet"/>
</head>
<body class="page-accueil">
  <nav>
    <div class="logo">
      <img alt="My Trips Logo" src="logo_my_trips.png"/>
    </div>
    <ul>
      <li><a href="accueil.php">Accueil</a></li>
      <li><a href="présentation.php">Présentation</a></li>
      <li><a href="rechercher.php">Rechercher</a></li>
      <?php if (isset($_SESSION['user'])): ?>
        <li><a class="active" href="mon_profil.php">Mon Profil</a></li>
        <li><a href="deconnexion.php">Se déconnecter</a></li>
      <?php else: ?>
        <li><a href="inscription.php">S'inscrire</a></li>
        <li><a href="connexion.php">Se connecter</a></li>
      <?php endif; ?>
      <li><a class="btn-primary" href="reserver.php">Réserver</a></li>
      <li><button id="themeToggle" class="btn-primary" style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">🌓</button></li>
    </ul>
  </nav>

  <header class="banner">
    <div class="banner-content">
      <h1>Mon espace personnel</h1>
      <p>Gérez vos informations et réservations</p>
    </div>
  </header>

  <main>
    <section>
      <h2>Mes informations</h2>
      <section class="profile-fields" style="max-width: 800px; margin: 2em auto;">
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
    </section>

    <section>
      <h2>Mes réservations</h2>
      <section class="trip-summary" style="max-width: 800px; margin: auto;">
        <?php if (empty($userTrips)): ?>
            <p>Vous n'avez encore effectué aucune réservation.</p>
        <?php else: ?>
            <?php foreach ($userTrips as $res): 
                $trip = findTripById($trips, $res['trip_id']);
                if (!$trip) continue;

                $nbPersonnes = $res['nb_personnes'] ?? 1;
                $options = $res['options'] ?? [];
            ?>
                <div style="border: 1px solid #ccc; padding: 1em; margin-bottom: 1em;">
                    <p><strong>Voyage :</strong> <?= htmlspecialchars($trip['titre']) ?></p>
                    <p><strong>Durée :</strong> <?= htmlspecialchars($trip['duree']) ?> jours</p>
                    <p><strong>Date de paiement :</strong> <?= htmlspecialchars($res['payment_date']) ?></p>
                    <p><strong>Nombre de personnes :</strong> <?= htmlspecialchars($nbPersonnes) ?></p>
                    <p><strong>Options choisies :</strong>
                        <?= empty($options) ? 'Aucune' : implode(', ', array_map('htmlspecialchars', $options)) ?>
                    </p>
                    <p><strong>Montant payé :</strong> <?= number_format($res['montant'], 2) ?> €</p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
      </section>
    </section>
  </main>

  <footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
  </footer>

  <script>
    function activerChamp(champ) {
      const input = document.getElementById('champ_' + champ);
      const buttons = input.parentElement.querySelectorAll('button');
      input.disabled = false;
      input.dataset.initial = input.value;
      buttons[0].style.display = 'none';
      buttons[1].style.display = 'inline';
      buttons[2].style.display = 'inline';
    }

    function validerChamp(champ) {
      const input = document.getElementById('champ_' + champ);
      input.disabled = true;
      const buttons = input.parentElement.querySelectorAll('button');
      buttons[0].style.display = 'inline';
      buttons[1].style.display = 'none';
      buttons[2].style.display = 'none';
    }

    function annulerChamp(champ) {
      const input = document.getElementById('champ_' + champ);
      input.value = input.dataset.initial || input.value;
      input.disabled = true;
      const buttons = input.parentElement.querySelectorAll('button');
      buttons[0].style.display = 'inline';
      buttons[1].style.display = 'none';
      buttons[2].style.display = 'none';
    }
  </script>
</body>
</html>
