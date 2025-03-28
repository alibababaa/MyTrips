<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();  // indispensable si on fait un if (isset($_SESSION['user']))
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Accueil - My Trips</title>
  <link href="my_trips.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600&display=swap" rel="stylesheet"/>
</head>

<body>
  <nav>
    <div class="logo">
      <img alt="My Trips Logo" src="logo_my_trips.png"/>
    </div>
    <ul>
      <li><a class="active" href="accueil.php">Accueil</a></li>
      <li><a href="présentation.php">Présentation</a></li>
      <li><a href="rechercher.php">Rechercher</a></li>

      <!-- Correction : on affiche “Mon Profil” ET “Se déconnecter”
           seulement si $_SESSION['user'] est défini, sinon “S'inscrire” et “Se connecter”. -->
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

  <header class="banner">
    <div class="banner-content">
      <h1>Explorez le Bénin autrement</h1>
      <p>Des voyages authentiques et sur mesure !</p>
      <button class="btn-secondary">Voir les destinations</button>
    </div>
  </header>

  <section class="destinations" id="destinations" style="display: flex; flex-wrap: wrap;">
    <?php
    $destinations = [
        ['name' => 'Porto-Novo', 'img' => 'https://www.les-covoyageurs.com/ressources/images-lieux/450-visiter-porto-novo-benin.jpg'],
        ['name' => 'Ouidah', 'img' => 'https://geoconfluences.ens-lyon.fr/images/image-a-la-une/img-rieucau/doc-3-la-porte-du-non-retour.jpg'],
        ['name' => 'Abomey', 'img' => 'https://upload.wikimedia.org/wikipedia/commons/d/dd/Abomey-Königspalast2.jpg'],
        ['name' => 'Ganvié', 'img' => 'https://www.build-green.fr/wp-content/uploads/2023/06/village-flottant-ganvie-benin_03.jpg'],
        ['name' => 'Grand Popo', 'img' => 'https://www.gouv.bj/upload/thumbnails/articles//0548016001606546234.png'],
        ['name' => 'Parakou', 'img' => 'https://upload.wikimedia.org/wikipedia/commons/2/21/La_colombe_18.jpg'],
        ['name' => 'Natitingou', 'img' => 'https://www.gouv.bj/upload/thumbnails/articles//0952356001605489551.jpeg'],
        ['name' => 'Dassa', 'img' => 'https://expatstraveltogether.com/wp-content/uploads/2023/09/DIG16_view-hills-city-dassazoume-benin-450w-1301045479-scaled-1.jpg'],
        ['name' => 'Tanguiéta', 'img' => 'https://cenozo.org/wp-content/uploads/2021/03/Entree-du-Parc-National-de-la-Pendjari-a-Batia.jpg'],
        ['name' => 'Possotomé', 'img' => 'https://www.ecobenin.org/wp-content/uploads/Possotome_cocotier_plage_chez_prefet_pilotis_lac_aheme_ecotourisme_ecobenin_benin.jpg'],
    ];

    foreach ($destinations as $destination) {
        echo '<div class="destination-card">';
        echo '<img alt="' . $destination['name'] . '" src="' . $destination['img'] . '"/>';
        echo '<h3>' . $destination['name'] . '</h3>';
        echo '</div>';
    }
    ?>
  </section>

  <footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
  </footer>
</body>
</html>
