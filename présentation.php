<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Présentation - My Trips</title>
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
    <li><a class="active" href="presentation.php">Présentation</a></li>
    <li><a href="rechercher.php">Rechercher</a></li>
    <?php if (isset($_SESSION['user'])): ?>
      <li><a href="mon_profil.php">Mon Profil</a></li>
      <li><a href="deconnexion.php">Se déconnecter</a></li>
    <?php else: ?>
      <li><a href="inscription.php">S'inscrire</a></li>
      <li><a href="connexion.php">Se connecter</a></li>
    <?php endif; ?>
    <li><a class="btn-primary" href="reserver.php">Réserver</a></li>
    <li>
      <button id="themeToggle" class="btn-primary" style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">🌓</button>
    </li>
  </ul>
</nav>

<!-- Bannière -->
<header class="banner">
  <div class="banner-content">
    <h1>Découvrez notre mission</h1>
    <p>Vivez des expériences uniques au Bénin avec My Trips !</p>
  </div>
</header>

<!-- Présentation -->
<section class="presentation">
  <h2>À propos de My Trips</h2>
  <p>My Trips est une plateforme dédiée aux passionnés de voyages et aux aventuriers en quête d’expériences authentiques au Bénin. Que vous soyez un voyageur curieux, un amoureux de la culture africaine ou un explorateur en quête de découvertes, nous vous offrons un service sur mesure pour rendre votre séjour inoubliable.</p>

  <p>Pourquoi choisir My Trips ?</p>
  <p>🌍 <strong>Expériences immersives</strong> – Plongez au cœur des traditions béninoises à travers des circuits conçus pour vous faire vivre l’essence même du pays.</p>
  <p>🤝 <strong>Guides locaux certifiés</strong> – Nos partenaires sont des experts du terrain, prêts à vous faire découvrir des lieux emblématiques et des trésors cachés.</p>
  <p>🏡 <strong>Hébergements uniques</strong> – Séjournez dans des logements typiques, du village lacustre de Ganvié aux plages paradisiaques de Grand-Popo.</p>
  <p>🍛 <strong>Gastronomie et artisanat</strong> – Découvrez les saveurs du Bénin et l’artisanat local à travers des ateliers et des rencontres avec les habitants.</p>

  <p>Nos services</p>
  <p>🔎 <strong>Recherche et réservation</strong> – Trouvez facilement des circuits adaptés à vos envies et réservez directement via notre plateforme.</p>
  <p>✈️ <strong>Voyages personnalisés</strong> – Vous avez une idée précise de votre voyage ? Nous créons un itinéraire sur mesure selon vos préférences.</p>
  <p>📍 <strong>Guides et assistance</strong> – Profitez d’un accompagnement avant, pendant et après votre voyage pour une expérience sans stress.</p>

  <p>Rejoignez-nous sur My Trips et laissez-vous séduire par la richesse du Bénin ! 🌿✨</p>
  <p>📌 Votre aventure commence ici !</p>
  <p><strong>Équipe :</strong> Yann AGBOTA, Ali-Ulas YILDIZ, Ilyes FELLAH</p>
</section>

<!-- Recherche rapide -->
<section class="search-quick">
  <h2>Rechercher rapidement un voyage</h2>
  <form method="POST" action="rechercher.php" style="display:flex;flex-direction:column;align-items:center;">
    <input name="destination" placeholder="Entrez votre destination..." type="text" required />
    <button class="btn-primary" type="submit">Rechercher</button>
  </form>
</section>

<!-- Footer -->
<footer>
  <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>

</body>
</html>
