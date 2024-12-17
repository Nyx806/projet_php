<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Article à Vendre</title>
    <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="../style/vente.css">
</head>

<?php include 'header.php' ?>

<body>
    <!-- Formulaire de création d'article -->
    <main class="form-container">
        <form action="traitement_vente.php" method="POST" enctype="multipart/form-data" class="form-vente">
            <!-- Nom de l'article -->
            <div class="input-group">
                <label for="nom">Nom de l'article</label>
                <input type="text" id="nom" name="nom" placeholder="Ex : Chaise en bois" required>
            </div>

            <!-- Description de l'article -->
            <div class="input-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Description de l'article" required></textarea>
            </div>

            <!-- Catégorie -->
            <div class="input-group">
                <label for="categorie">Catégorie</label>
                <select id="categorie" name="categorie" required>
                    <option value="">-- Sélectionnez une catégorie --</option>
                    <option value="meubles">Meubles</option>
                    <option value="vetements">Vêtements</option>
                    <option value="electronique">Électronique</option>
                    <option value="accessoires">Accessoires</option>
                </select>
            </div>

            <!-- Prix -->
            <div class="input-group">
                <label for="prix">Prix (€)</label>
                <input type="number" id="prix" name="prix" placeholder="Ex : 49.99" step="0.01" min="0" required>
            </div>

            <!-- Quantité en stock -->
            <div class="input-group">
                <label for="stock">Quantité en Stock</label>
                <input type="number" id="stock" name="stock" placeholder="Ex : 10" min="1" required>
            </div>

            <!-- Image -->
            <div class="input-group">
                <label for="image">Image de l'article</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>

            <!-- Bouton de soumission -->
            <button type="submit" class="btn">Ajouter l'Article</button>
        </form>
    </main>
</body>
<?php include 'footer.php' ?>
</html>
