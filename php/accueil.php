<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Accueil - My Trips</title>
  <link href="my_trips.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600&amp;display=swap" rel="stylesheet"/>
</head>
 
<body>
  <nav>
    <div class="logo">
      <img alt="My Trips Logo" src="logo_my_trips.png"/>
    </div>
    <ul>
      <li>
        <a class="active" href="accueil.php">Accueil</a>
      </li>
      <li>
        <a href="présentation.php">Présentation</a>
      </li>
      <li>
        <a href="rechercher.php">Rechercher</a>
      </li>
      <li>
        <a href="mon_profil.php">Mon Profil</a>
      </li>
      <?php if (isset($_SESSION['user'])): ?>
        <li>
          <a href="mon_profil.php">Mon Profil</a>
        </li>
        <li>
          <a href="deconnexion.php">Se déconnecter</a>
        </li>
      <?php else: ?>
        <li>
          <a href="inscription.php">S'inscrire</a>
        </li>
        <li>
          <a href="connexion.php">Se connecter</a>
        </li>
      <?php endif; ?>
     
      <li>
        <a class="btn-primary" href="reserver.php">Réserver</a>
      </li>
    </ul>
  </nav>

  <header class="banner">
    <div class="banner-content">
      <h1>Explorez le Bénin autrement</h1>
      <p>Des voyages authentiques et sur mesure !</p>
      <button class="btn-secondary">Voir les destinations</button>
    </div>
  </header>

  <section class="destinations" id="destinations" style="display:flex;">
    <div class="destination-card">
      <img alt="Porto-Novo" src="https://www.les-covoyageurs.com/ressources/images-lieux/450-visiter-porto-novo-benin.jpg"/>
      <h3>Porto-Novo</h3>
    </div>
    <div class="destination-card">
      <img alt="Ouidah" src="https://geoconfluences.ens-lyon.fr/images/image-a-la-une/img-rieucau/doc-3-la-porte-du-non-retour.jpg"/>
      <h3>Ouidah</h3>
    </div>
    <div class="destination-card">
      <img alt="Abomey" src="https://upload.wikimedia.org/wikipedia/commons/d/dd/Abomey-Königspalast2.jpg"/>
      <h3>Abomey</h3>
    </div>
    <div class="destination-card">
      <img alt="Ganvié" src="https://www.build-green.fr/wp-content/uploads/2023/06/village-flottant-ganvie-benin_03.jpg"/>
      <h3>Ganvié</h3>
    </div>
    <div class="destination-card">
      <img alt="Grand Popo" src="https://www.gouv.bj/upload/thumbnails/articles//0548016001606546234.png"/>
      <h3>Grand Popo</h3>
    </div>
    <div class="destination-card">
      <img alt="Parakou" src="https://upload.wikimedia.org/wikipedia/commons/2/21/La_colombe_18.jpg"/>
      <h3>Parakou</h3>
    </div>
    <div class="destination-card">
      <img alt="Natitingou" src="https://www.gouv.bj/upload/thumbnails/articles//0952356001605489551.jpeg"/>
      <h3>Natitingou</h3>
    </div>
    <div class="destination-card">
      <img alt="Dassa" src="https://expatstraveltogether.com/wp-content/uploads/2023/09/DIG16_view-hills-city-dassazoume-benin-450w-1301045479-scaled-1.jpg"/>
      <h3>Dassa</h3>
    </div>
    <div class="destination-card">
      <img alt="Tanguiéta" src="https://cenozo.org/wp-content/uploads/2021/03/Entree-du-Parc-National-de-la-Pendjari-a-Batia.jpg"/>
      <h3>Tanguiéta</h3>
    </div>
    <div class="destination-card">
      <img alt="Possotomé" src="https://www.ecobenin.org/wp-content/uploads/Possotome_cocotier_plage_chez_prefet_pilotis_lac_aheme_ecotourisme_ecobenin_benin.jpg"/>
      <h3>Possotomé</h3>
    </div>
  </section>

  <footer>
    <p>
      © 2025 My Trips. Tous droits réservés.
    </p>
  </footer>
</body>
</html>
