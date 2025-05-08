<?php
session_start();

$resultats = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destination = $_POST['destination'] ?? '';
    $date_depart = $_POST['date'] ?? '';
    $options     = $_POST['options'] ?? '';

    // Ce bloc est à remplacer par une vraie logique de recherche dans trips.json
    if (!empty($destination)) {
        $resultats[] = "Voyage à $destination prévu à partir du $date_depart avec option : $options.";
    } else {
        $resultats[] = "Veuillez préciser une destination.";
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
    <li><a href="presentation.php">Présentation</a></li>
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
    <select id="destination" name="destination" required>
      <option value="">-- Choisissez une destination --</option>
      <option value="Ouidah">Ouidah</option>
      <option value="Porto-Novo">Porto-Novo</option>
      <option value="Abomey">Abomey</option>
      <option value="Ganvié">Ganvié</option>
      <option value="Grand-Popo">Grand-Popo</option>
      <option value="Parakou">Parakou</option>
      <option value="Natitingou">Natitingou</option>
      <option value="Dassa">Dassa</option>
      <option value="Tanguiéta">Tanguiéta</option>
      <option value="Possotomé">Possotomé</option>
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
      <ul>
        <?php foreach ($resultats as $resultat): ?>
          <li><?= htmlspecialchars($resultat) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>Aucun résultat trouvé.</p>
    <?php endif; ?>
  </section>
<?php endif; ?>

<!-- Footer -->
<footer>
  <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>

</body>
</html>
