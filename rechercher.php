<?php
session_start();

// Si le formulaire a été soumis, récupérer les données et effectuer la recherche
$resultats = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destination = $_POST['destination'] ?? '';
    $date_depart = $_POST['date'] ?? '';
    $options     = $_POST['options'] ?? '';

    // Exemple de résultats fictifs
    $resultats[] = "Voyage à Ouidah du 01/05/2025 au 07/05/2025 avec hébergement et transport.";
    $resultats[] = "Circuit à Porto-Novo du 15/06/2025 au 20/06/2025 avec activités culturelles.";
    $resultats[] = "Séjour à Ganvié avec hébergement et activités nature.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Rechercher - My Trips</title>
  <link href="my_trips.css" rel="stylesheet"/>
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

    <!-- Correction : On remplace “profil.php” par un menu conditionnel -->
    <?php if (isset($_SESSION['user'])): ?>
      <li><a href="mon_profil.php">Mon Profil</a></li>
      <li><a href="deconnexion.php">Se déconnecter</a></li>
    <?php else: ?>
      <li><a href="inscription.php">S'inscrire</a></li>
      <li><a href="connexion.php">Se connecter</a></li>
    <?php endif; ?>

    <li><a class="btn-primary" href="reserver.php">Réserver</a></li>
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
    <select id="destination" name="destination">
      <option value="">Sélectionnez une destination</option>
      <option value="ouidah">Ouidah</option>
      <option value="porto-novo">Porto-Novo</option>
      <option value="abomey">Abomey</option>
      <option value="ganvie">Ganvie</option>
      <option value="grand-popo">Grand-Popo</option>
      <option value="parakou">Parakou</option>
      <option value="natitingou">Natitingou</option>
      <option value="dassa">Dassa</option>
      <option value="tanguieta">Tanguieta</option>
      <option value="possotome">Possotome</option>
    </select>
    <label for="date">Date de départ :</label>
    <input id="date" type="date" name="date"/>
    <label for="options">Options :</label>
    <select id="options" name="options">
      <option value="">Toutes les options</option>
      <option value="hebergement">Hébergement</option>
      <option value="transport">Transport</option>
      <option value="activites">Activités</option>
    </select>
    <button class="btn-primary" type="submit">Rechercher</button>
  </form>
</section>

<!-- Résultats de la recherche -->
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
  <?php if (!empty($resultats)): ?>
    <section class="search-results">
      <h2>Résultats de la recherche</h2>
      <ul>
        <?php foreach ($resultats as $resultat): ?>
          <li><?php echo htmlspecialchars($resultat); ?></li>
        <?php endforeach; ?>
      </ul>
    </section>
  <?php else: ?>
    <section class="search-results">
      <h2>Aucun résultat trouvé pour votre recherche.</h2>
    </section>
  <?php endif; ?>
<?php endif; ?>

<!-- Footer -->
<footer>
  <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>
</body>
</html>
