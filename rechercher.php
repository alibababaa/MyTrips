<?php
session_start();

$trips_data = file_get_contents('trips.json');
$all_trips = json_decode($trips_data, true);
$resultats = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destination = $_POST['destination'] ?? '';
    $date_depart = $_POST['date'] ?? '';
    $options     = $_POST['options'] ?? '';

    // Filtrage simple par destination
    foreach ($all_trips as $trip) {
    $match = true;

    // Si une destination est fournie, on filtre dessus
    if (!empty($destination) && strcasecmp($trip['titre'], $destination) !== 0) {
        $match = false;
    }

    // Si un filtre date est fourni, on l’ajoute à chaque résultat
    if (!empty($date_depart)) {
        $trip['date'] = $date_depart;
    } else {
        $trip['date'] = date('Y-m-d');
    }

    // Ajout d’options et d’étapes simulées
    $trip['options'] = $options;
    $trip['etapes'] = rand(1, 5);

    if ($match) {
        $resultats[] = $trip;
    }
}

}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Rechercher - My Trips</title>
  <link id="theme-stylesheet" rel="stylesheet" href="my_trips.css">
  <script src="theme.js" defer></script>
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600&display=swap" rel="stylesheet"/>
</head>
<body>

<!-- Navigation -->
<nav>
  <div class="logo"><img alt="My Trips Logo" src="logo_my_trips.png"/></div>
  <ul>
    <li><a href="accueil.php">Accueil</a></li>
    <li><a href="présentation.php">Présentation</a></li>
    <li><a class="active" href="rechercher.php">Rechercher</a></li>
    <?php if (isset($_SESSION['user'])): ?>
      <li><a href="mon_profil.php">Mon Profil</a></li>
      <li><a href="deconnexion.php">Se déconnecter</a></li>
    <?php else: ?>
      <li><a href="inscription.php">S'inscrire</a></li>
      <li><a href="connexion.php">Se connecter</a></li>
    <?php endif; ?>
    <li><a class="btn-primary" href="reserver.php">Réserver</a></li>
    <li><button id="themeToggle" class="btn-primary" style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">🌓</button></li>
  </ul>
</nav>

<!-- Banner -->
<header class="banner">
  <div class="banner-content">
    <h1>Recherchez votre prochaine aventure</h1>
    <p>Trouvez votre voyage idéal au Bénin en quelques clics !</p>
  </div>
</header>

<!-- Section de Recherche -->
<section class="search-section">
  <h2>Filtrez votre recherche</h2>
  <form method="POST">
    <label for="destination">Destination :</label>
    <select id="destination" name="destination" >
      <option value="">-- Choisissez une destination --</option>
      <?php foreach ($all_trips as $trip): ?>
        <option value="<?= htmlspecialchars($trip['titre']) ?>"><?= htmlspecialchars($trip['titre']) ?></option>
      <?php endforeach; ?>
    </select>

    <label for="date">Date de départ :</label>
    <input type="date" name="date" id="date">

    <label for="options">Options :</label>
    <select id="options" name="options">
      <option value="">Aucune option</option>
      <option value="hebergement">Hébergement</option>
      <option value="transport">Transport</option>
      <option value="activites">Activités</option>
    </select>

    <button class="btn-primary" type="submit">Rechercher</button>
  </form>
</section>

<!-- Résultats -->
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
  <section class="search-results">
    <h2>Résultats de la recherche</h2>

    <?php if (!empty($resultats)): ?>

      <!-- Menu de tri -->
      <div style="text-align: right; margin-bottom: 1em;">
        <label for="sort-select"><strong>Trier par :</strong></label>
        <select id="sort-select">
          <option value="date">Date</option>
          <option value="price">Prix</option>
          <option value="duration">Durée</option>
          <option value="steps">Étapes</option>
        </select>
      </div>

      <!-- Cartes -->
      <div class="destinations">
        <?php foreach ($resultats as $trip): ?>
          <div class="trip-card"
               data-date="<?= htmlspecialchars($trip['date']) ?>"
               data-price="<?= htmlspecialchars($trip['prix']) ?>"
               data-duration="<?= htmlspecialchars($trip['duree']) ?>"
               data-steps="<?= htmlspecialchars($trip['etapes']) ?>">
            <img src="<?= htmlspecialchars($trip['image']) ?>" alt="<?= htmlspecialchars($trip['titre']) ?>" style="width:100%; height:180px; object-fit:cover;">
            <h3><?= htmlspecialchars($trip['titre']) ?></h3>
            <p>Départ : <?= htmlspecialchars($trip['date']) ?></p>
            <p>Prix : <?= htmlspecialchars($trip['prix']) ?> €</p>
            <p>Durée : <?= htmlspecialchars($trip['duree']) ?> jours</p>
            <p>Étapes : <?= htmlspecialchars($trip['etapes']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>

    <?php else: ?>
      <p>Aucun résultat trouvé.</p>
    <?php endif; ?>
  </section>
<?php endif; ?>

<!-- Footer -->
<footer>
  <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>

<!-- Scripts -->
<script src="tri.js"></script>
</body>
</html>
