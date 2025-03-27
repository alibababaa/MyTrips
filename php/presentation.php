<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Présentation - My Trips</title>
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
    <li><a class="active" href="presentation.php">Présentation</a></li>
    <li><a href="rechercher.php">Rechercher</a></li>
    <li><a href="mon_profil.php">Mon Profil</a></li>
    <li><a href="inscription.php">S'inscrire</a></li>
    <li><a href="connexion.php">Se connecter</a></li>
    <li><a class="btn-primary" href="reserver.php">Réserver</a></li>
  </ul>
</nav>
  
<!-- Banner -->
<header class="banner">
  <div class="banner-content">
    <h1>Découvrez notre mission</h1>
    <p>Vivez des expériences uniques au Bénin avec My Trips !</p>
  </div>
</header>
  
<!-- Présentation -->
<section class="presentation">
  <h2>À propos de My Trips</h2>
  <p>My Trips est une plateforme dédiée aux passionnés de voyages ...</p>
  <!-- Reste du texte de présentation identique -->
  <p><strong>Équipe :</strong> Yann AGBOTA, Ali-Ulas YILDIZ, Ilyes FELLAH</p>
</section>
  
<!-- Recherche rapide -->
<section class="search-quick">
  <h2>Rechercher rapidement un voyage</h2>
  <form method="POST" action="rechercher.php" style="display:flex;flex-direction:column;align-items:center;">
    <input name="destination" placeholder="Entrez votre destination..." type="text"/>
    <button class="btn-primary" type="submit">Rechercher</button>
  </form>
</section>
  
<!-- Footer -->
<footer>
  <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>
</body>
</html>
