<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Facture</title>
    <link rel="stylesheet" href="../style/confirmation.css">
</head>

<?php include 'header.php' ?>

<body>
    <main class="form-container">
        <form action="create_invoice.php" method="POST" class="invoice-form">

            <!-- Date de Transaction -->
            <div class="input-group">
                <label for="transac_date">Date de la Transaction</label>
                <input type="date" id="transac_date" name="transac_date" required>
            </div>

            <!-- Montant -->
            <div class="input-group">
                <label for="montant">Montant (€)</label>
                <input type="number" id="montant" name="montant" placeholder="Ex : 150.75" step="0.01" min="0" required>
            </div>

            <!-- Adresse de Facturation -->
            <div class="input-group">
                <label for="adresse_facture">Adresse de Facturation</label>
                <input type="text" id="adresse_facture" name="adresse_facture" placeholder="Adresse complète" required>
            </div>

            <!-- Ville de Facturation -->
            <div class="input-group">
                <label for="ville_facturation">Ville de Facturation</label>
                <input type="text" id="ville_facturation" name="ville_facturation" placeholder="Nom de la ville" required>
            </div>

            <!-- Code Postal -->
            <div class="input-group">
                <label for="code_postal">Code Postal</label>
                <input type="text" id="code_postal" name="code_postal" placeholder="Ex : 75001" required>
            </div>

            <!-- Bouton de soumission -->
            <button type="submit" class="btn">Créer la Facture</button>
        </form>
    </main>
</body>

<?php include 'footer.php' ?>

</html>
