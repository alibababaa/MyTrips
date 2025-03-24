<?php
// Exemple de session démarrée pour gérer les utilisateurs
session_start();

// Si le formulaire a été soumis, récupérer les données
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destination = $_POST['destination'];
    $date_depart = $_POST['date'];
    $nombre_personnes = $_POST['personnes'];
    
    // Validation basique
    if (!empty($destination) && !empty($date_depart) && !empty($nombre_personnes)) {
        // Vous pouvez ici ajouter du code pour enregistrer la réservation dans une base de données ou effectuer d'autres actions
        $_SESSION['reservation'] = [
            'destination' => $destination,
            'date_depart' => $date_depart,
            'nombre_personnes' => $nombre_personnes
        ];
        
        // Rediriger vers une page de confirmation (par exemple)
        header("Location: confirmation.php");
        exit;
    } else {
        $message_erreur = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - My Trips</title>
    <link rel="stylesheet" href="my_trips.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600&display=swap" rel="stylesheet">
</head>
<body>
    
    <!-- Navigation -->
    <nav>
        <div class="logo"><img src="logo_my_trips.png" alt="My Trips Logo"></div>
        <ul>
            <li><a href="accueil.php">Accueil</a></li>
            <li><a href="présentation.php">Présentation</a></li>
            <li><a href="rechercher.php">Rechercher</a></li>
            <li><a href="mon_profil.php">Mon Profil</a></li>
            <li><a href="inscription.php">S'inscrire</a></li>
            <li><a href="connexion.php">Se connecter</a></li>
            <li><a href="reserver.php" class="active">Réserver</a></li>
        </ul>
    </nav>
    
    <!-- Banner -->
    <header class="banner">
        <div class="banner-content">
            <h1>Réservez votre voyage</h1>
            <p>Planifiez votre séjour au Bénin en quelques clics.</p>
        </div>
    </header>

    <!-- Formulaire de réservation -->
    <section class="reservation-section signup-section">
        <h2>Réservation</h2>

        <!-- Message d'erreur si des champs sont manquants -->
        <?php if (isset($message_erreur)): ?>
            <div class="error-message">
                <p><?php echo htmlspecialchars($message_erreur); ?></p>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label for="destination">Destination :</label>
            <select id="destination" name="destination" required>
                <option value="">Sélectionnez une destination</option>
                <option value="ouidah">Ouidah</option>
                <option value="porto-novo">Porto-Novo</option>
                <option value="abomey">Abomey</option>
                <option value="ganvie">Ganvié</option>
                <option value="grand-popo">Grand Popo</option>
                <option value="parakou">Parakou</option>
                <option value="natitingou">Natitingou</option>
                <option value="dassa">Dassa</option>
                <option value="tanguieta">Tanguieta</option>
                <option value="possotome">Possotomé</option>
            </select>

            <label for="date">Date de départ :</label>
            <input type="date" id="date" name="date" required>

            <label for="personnes">Nombre de personnes :</label>
            <input type="number" id="personnes" name="personnes" min="1" max="10" required>

            <button type="submit" class="btn-primary">Réserver</button>
        </form>
    </section>
    
    <!-- Footer -->
    <footer>
        <p>&copy; 2025 My Trips. Tous droits réservés.</p>
    </footer>
</body>
</html>
