<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit();
}

$userLogin = $_SESSION['user']['login'];
$transactionsFile = __DIR__ . '/transactions.json';
$tripsFile = __DIR__ . '/trips.json';

$transactions = file_exists($transactionsFile) ? json_decode(file_get_contents($transactionsFile), true) : [];
$trips = file_exists($tripsFile) ? json_decode(file_get_contents($tripsFile), true) : [];

function getTripTitle($trips, $tripId) {
    foreach ($trips as $trip) {
        if ($trip['id'] == $tripId) return $trip['titre'];
    }
    return "Voyage ID: $tripId";
}

// Filtrer les transactions utilisateur
$transactionsUser = array_filter($transactions, fn($t) => $t['user_id'] === $userLogin);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique de Paiements</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2 { color: #333; }
        .btn-primary {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 8px 16px;
            cursor: pointer;
            margin-bottom: 1em;
        }
        .btn-danger {
            background-color: #c0392b;
            border: none;
            color: white;
            padding: 5px 10px;
            cursor: pointer;
        }
        input, select {
            padding: 5px;
            margin: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #aaa;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>

<h2>Historique de vos paiements</h2>

<!-- Filtres -->
<div>
    <label>Filtrer par voyage :
        <select id="filtreVoyage" onchange="filtrer()">
            <option value="">Tous</option>
            <?php
            $titresUniques = [];
            foreach ($transactionsUser as $t) {
                $titre = getTripTitle($trips, $t['trip_id']);
                if (!in_array($titre, $titresUniques)) {
                    $titresUniques[] = $titre;
                    echo '<option value="' . htmlspecialchars($titre) . '">' . htmlspecialchars($titre) . '</option>';
                }
            }
            ?>
        </select>
    </label>
    <label>Filtrer par date :
        <input type="date" id="filtreDate" onchange="filtrer()" />
    </label>
    <button class="btn-primary" onclick="exporterPDF()">📄 Exporter en PDF</button>
</div>

<div id="table-container">
<?php if (empty($transactionsUser)): ?>
    <p>Aucune transaction trouvée.</p>
<?php else: ?>
    <table id="tableTransactions">
        <thead>
            <tr>
                <th>Date</th>
                <th>Voyage</th>
                <th>Montant (€)</th>
                <th>Personnes</th>
                <th>Options</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactionsUser as $index => $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['payment_date']) ?></td>
                    <td><?= htmlspecialchars(getTripTitle($trips, $t['trip_id'])) ?></td>
                    <td><?= htmlspecialchars($t['montant'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($t['nb_personnes'] ?? 1) ?></td>
                    <td><?= isset($t['options']) ? htmlspecialchars(implode(", ", $t['options'])) : '-' ?></td>
                    <td>
                        <form method="POST" action="supprimer_transaction.php" onsubmit="return confirm('Confirmer la suppression ?');">
                            <input type="hidden" name="payment_date" value="<?= htmlspecialchars($t['payment_date']) ?>">
                            <input type="hidden" name="trip_id" value="<?= htmlspecialchars($t['trip_id']) ?>">
                            <button class="btn-danger" type="submit">❌</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</div>

<script>
function exporterPDF() {
    const element = document.getElementById('table-container');
    html2pdf().from(element).save('historique_paiements.pdf');
}

function filtrer() {
    const voyage = document.getElementById('filtreVoyage').value.toLowerCase();
    const date = document.getElementById('filtreDate').value;

    const rows = document.querySelectorAll('#tableTransactions tbody tr');
    rows.forEach(row => {
        const voyageText = row.cells[1].textContent.toLowerCase();
        const dateText = row.cells[0].textContent.slice(0, 10); // YYYY-MM-DD
        const matchVoyage = !voyage || voyageText.includes(voyage);
        const matchDate = !date || dateText === date;
        row.style.display = (matchVoyage && matchDate) ? '' : 'none';
    });
}
</script>

</body>
</html>
