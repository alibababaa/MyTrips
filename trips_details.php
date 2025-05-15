<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

function loadTrips() {
    $file = __DIR__ . '/trips.json';
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true);
}

if (!isset($_GET['trip_id'])) {
    die("Aucun voyage sélectionné.");
}

$tripId = $_GET['trip_id'];
$trips = loadTrips();
$tripDetails = null;

foreach ($trips as $trip) {
    if (isset($trip['id']) && $trip['id'] == $tripId) {
        $tripDetails = $trip;
        break;
    }
}

if (!$tripDetails) {
    die("Voyage introuvable.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Détails du voyage - My Trips</title>
    <link id="theme-stylesheet" rel="stylesheet" href="my_trips.css">
    <script src="theme.js" defer></script>
</head>
<body>

<nav>
    <ul>
        <li><a href="accueil.php">Accueil</a></li>
        <li><a href="présentation.php">Présentation</a></li>
        <li><a href="rechercher.php">Rechercher</a></li>
        <li><a href="mon_profil.php">Mon Profil</a></li>
        <li><a href="deconnexion.php">Se déconnecter</a></li>
        <li><button id="themeToggle" class="btn-primary" style="background-color: transparent; color: #ffd700; border: 2px solid #ffd700;">🌓</button></li>
    </ul>
</nav>

<header class="banner">
    <div class="banner-content">
        <h1>Détails du voyage</h1>
        <p>Découvrez toutes les informations sur ce circuit</p>
    </div>
</header>

<section class="trip-summary" style="max-width: 800px; margin: 2em auto; text-align: center;">
    <h2><?= htmlspecialchars($tripDetails['titre']) ?></h2>

    <?php if (!empty($tripDetails['image'])): ?>
        <img src="<?= htmlspecialchars($tripDetails['image']) ?>" alt="Image du voyage" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 10px; margin-bottom: 1em;">
    <?php endif; ?>

    <p><strong>Prix de base :</strong> <?= htmlspecialchars($tripDetails['prix']) ?> €</p>
    <p><strong>Durée :</strong> <?= htmlspecialchars($tripDetails['duree']) ?> jours</p>

    <?php if (!empty($tripDetails['etapes']) && is_array($tripDetails['etapes'])): ?>
        <p><strong>Étapes :</strong> <?= htmlspecialchars(implode(', ', $tripDetails['etapes'])) ?></p>
    <?php endif; ?>

    <form action="recapitulatif.php" method="POST" id="tripForm">
        <input type="hidden" name="trip_id" value="<?= htmlspecialchars($tripDetails['id']) ?>">
        <input type="hidden" name="prix_estime" id="prix_estime_input">

        <label for="nb_personnes"><strong>Nombre de personnes :</strong></label>
        <input type="number" id="nb_personnes" name="nb_personnes" value="1" min="1" required><br><br>

        <label for="hebergement"><strong>Hébergement :</strong></label>
        <select id="hebergement" name="hebergement" data-type="hebergement" required></select><br><br>

        <label for="repas"><strong>Repas :</strong></label>
        <select id="repas" name="repas" data-type="repas" required></select><br><br>

        <label for="activites"><strong>Activités :</strong></label>
        <select id="activites" name="activites" data-type="activites" required></select><br><br>

        <?php if (!empty($tripDetails['etapes']) && is_array($tripDetails['etapes'])): ?>
            <label><strong>Choisissez vos étapes (chacune +10 €/pers) :</strong></label><br>
            <?php foreach ($tripDetails['etapes'] as $etape): ?>
                <input type="checkbox" name="etapes[]" value="<?= htmlspecialchars($etape) ?>" checked class="etape-checkbox">
                <?= htmlspecialchars($etape) ?> <br>
            <?php endforeach; ?>
        <?php endif; ?>

        <br>
        <label><strong>Options supplémentaires :</strong></label><br>
        <input type="checkbox" name="options[]" value="assurance" class="option-checkbox"> Assurance voyage (+20 €/pers)<br>
        <input type="checkbox" name="options[]" value="bagage" class="option-checkbox"> Bagage en soute (+30 €/pers)<br>
        <input type="checkbox" name="options[]" value="guide" class="option-checkbox"> Guide local (+50 €)<br>
        <input type="checkbox" name="options[]" value="transport" class="option-checkbox"> Transport privé (+100 €)<br>
        <!-- Hébergement premium retiré -->

        <br>
        <p><strong>Prix estimé :</strong> <span id="prix-estime">0 €</span></p>

        <button class="btn-primary" type="submit">Voir le récapitulatif</button>
    </form>

    <p style="margin-top: 1.5em;"><a href="reserver.php">← Retour aux voyages</a></p>
</section>

<footer>
    <p>© 2025 My Trips. Tous droits réservés.</p>
</footer>

<script>
    const optionsData = {
        hebergement: [
            { label: "Hôtel 3★", price: 50 },
            { label: "Hôtel 4★", price: 80 },
            { label: "Auberge", price: 30 },
            { label: "Camping", price: 20 }
        ],
        repas: [
            { label: "Petit déjeuner", price: 10 },
            { label: "Demi-pension", price: 25 },
            { label: "Pension complète", price: 40 }
        ],
        activites: [
            { label: "Plongée", price: 60 },
            { label: "Randonnée", price: 20 },
            { label: "Musée", price: 15 },
            { label: "Croisière", price: 70 }
        ]
    };

    // Prix options supplémentaires fixes (non par personne)
    const optionsFixe = {
        guide: 50,
        transport: 100
    };

    // Prix options supplémentaires par personne
    const optionsParPersonne = {
        assurance: 20,
        bagage: 30
    };

    function updateTotal() {
        const nbPersonnes = parseInt(document.getElementById("nb_personnes").value) || 1;
        let totalParPersonne = <?= (float)$tripDetails['prix'] ?>; // prix de base du voyage

        // Prix des selects
        document.querySelectorAll("select[data-type]").forEach(select => {
            const type = select.dataset.type;
            const selectedValue = select.value;
            const option = optionsData[type].find(opt => opt.label === selectedValue);
            if (option) totalParPersonne += option.price;
        });

        // Prix des étapes cochées (10€/pers chacune)
        document.querySelectorAll(".etape-checkbox").forEach(chk => {
            if (chk.checked) {
                totalParPersonne += 10;
            }
        });

        // Prix options supplémentaires
        let totalFixe = 0;
        let totalParPersSupp = 0;

        document.querySelectorAll(".option-checkbox").forEach(chk => {
            if (chk.checked) {
                const val = chk.value;
                if (optionsFixe[val]) {
                    totalFixe += optionsFixe[val];
                } else if (optionsParPersonne[val]) {
                    totalParPersSupp += optionsParPersonne[val];
                }
            }
        });

        const totalGlobal = (totalParPersonne + totalParPersSupp) * nbPersonnes + totalFixe;

        // Mise à jour affichage
        const totalDisplay = document.getElementById("prix-estime");
        const prixInput = document.getElementById("prix_estime_input");
        if (totalDisplay && prixInput) {
            totalDisplay.textContent = totalGlobal.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' });
            prixInput.value = totalGlobal;
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        // Remplissage des selects dynamiques
        document.querySelectorAll("select[data-type]").forEach(select => {
            const type = select.dataset.type;
            const options = optionsData[type] || [];
            select.innerHTML = "";
            options.forEach(obj => {
                const opt = document.createElement("option");
                opt.value = obj.label;
                opt.textContent = obj.label + " (+ " + obj.price + "€)";
                select.appendChild(opt);
            });

            select.addEventListener("change", updateTotal);
        });

        // Événements pour nombre de personnes, étapes et options supplémentaires
        document.getElementById("nb_personnes").addEventListener("input", updateTotal);
        document.querySelectorAll(".etape-checkbox").forEach(chk => {
            chk.addEventListener("change", updateTotal);
        });
        document.querySelectorAll(".option-checkbox").forEach(chk => {
            chk.addEventListener("change", updateTotal);
        });

        // Calcul initial
        updateTotal();
    });
</script>

</body>
</html>
