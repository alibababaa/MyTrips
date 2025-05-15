<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

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
</head>
<body class="page-profil">

<nav>
  <div class="logo"><img alt="My Trips Logo" src="logo_my_trips.png"/></div>
  <ul>
    <li><a href="accueil.php">Accueil</a></li>
    <li><a href="présentation.php">Présentation</a></li>
    <li><a href="rechercher.php">Rechercher</a></li>
    <li><a class="active" href="mon_profil.php">Mon Profil</a></li>
    <li><a href="deconnexion.php">Se déconnecter</a></li>
    <li><a class="btn-primary" href="reserver.php">Réserver</a></li>
    <li><button id="themeToggle" class="btn-primary" style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">🌓</button></li>
  </ul>
</nav>

<header class="banner">
  <div class="banner-content">
    <h1>Mon espace personnel</h1>
    <p>Gérez vos informations et vos réservations</p>
  </div>
</header>

<main>
  <section>
    <h2>Mes informations</h2>
    <section class="profile-fields" style="max-width: 800px; margin: 2em auto;">
      <form id="profil-form">
        <?php
        $utilisateur = $_SESSION['user'];
        $champs = ['login' => 'Identifiant', 'name' => 'Nom'];
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

      <!-- Bouton de suppression du compte -->
      <div style="margin-top: 2em; text-align: center;">
        <form action="supprimer_compte.php" method="POST" onsubmit="return confirm('Es-tu sûr de vouloir supprimer ton compte ? Cette action est irréversible.');">
          <button class="btn-primary" style="background-color: #c0392b;">❌ Supprimer mon compte</button>
        </form>
      </div>
    </section>
  </section>

  <section>
    <h2>Mes réservations</h2>
    <section class="trip-summary" style="max-width: 900px; margin: auto;">
      <?php if (empty($userTrips)): ?>
          <p>Vous n'avez encore effectué aucune réservation.</p>
      <?php else: ?>
        <table style="width:100%; border-collapse: collapse;">
          <thead>
            <tr style="background-color: #eee;">
              <th style="padding: 10px; border: 1px solid #ccc;">Date</th>
              <th style="padding: 10px; border: 1px solid #ccc;">Voyage</th>
              <th style="padding: 10px; border: 1px solid #ccc;">Montant (€)</th>
              <th style="padding: 10px; border: 1px solid #ccc;">Personnes</th>
              <th style="padding: 10px; border: 1px solid #ccc;">Options</th>
              <th style="padding: 10px; border: 1px solid #ccc;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($userTrips as $res):
                $trip = findTripById($trips, $res['trip_id']);
                if (!$trip) continue;
                $nbPersonnes = $res['nb_personnes'] ?? 1;
                $options = $res['options'] ?? [];
            ?>
              <tr>
                <td style="padding: 8px; border: 1px solid #ccc;"><?= htmlspecialchars($res['payment_date']) ?></td>
                <td style="padding: 8px; border: 1px solid #ccc;"><?= htmlspecialchars($trip['titre']) ?></td>
                <td style="padding: 8px; border: 1px solid #ccc;"><?= number_format($res['montant'], 2) ?></td>
                <td style="padding: 8px; border: 1px solid #ccc;"><?= htmlspecialchars($nbPersonnes) ?></td>
                <td style="padding: 8px; border: 1px solid #ccc;"><?= empty($options) ? 'Aucune' : implode(', ', array_map('htmlspecialchars', $options)) ?></td>
                <td style="padding: 8px; border: 1px solid #ccc;">
                    <form action="supprimer_reservation.php" method="POST" onsubmit="return confirm('Supprimer cette réservation ?');">
                        <input type="hidden" name="trip_id" value="<?= htmlspecialchars($res['trip_id']) ?>">
                        <input type="hidden" name="payment_date" value="<?= htmlspecialchars($res['payment_date']) ?>">
                        <button type="submit" class="btn-primary" style="background-color: #e74c3c;">❌ Supprimer</button>
                    </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
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

function annulerChamp(champ) {
  const input = document.getElementById('champ_' + champ);
  input.value = input.dataset.initial || input.value;
  input.disabled = true;
  const buttons = input.parentElement.querySelectorAll('button');
  buttons[0].style.display = 'inline';
  buttons[1].style.display = 'none';
  buttons[2].style.display = 'none';
}

function validerChamp(champ) {
  const input = document.getElementById('champ_' + champ);
  const value = input.value;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "modifier_profil.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onload = function () {
    if (xhr.status === 200) {
      const res = JSON.parse(xhr.responseText);
      if (res.success) {
        input.disabled = true;
        const buttons = input.parentElement.querySelectorAll('button');
        buttons[0].style.display = 'inline';
        buttons[1].style.display = 'none';
        buttons[2].style.display = 'none';
      } else {
        alert("Erreur : " + (res.error || "modification échouée."));
      }
    }
  };

  xhr.send(`champ=${champ}&valeur=${encodeURIComponent(value)}`);
}
</script>

</body>
</html>
