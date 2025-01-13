<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Article à Vendre</title>
    <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="../style/vente.css">
</head>

<?php
include 'header.php';
include 'config.php';

try {
    if (isset($_POST['nom'], $_POST['description'], $_POST['prix'], $_POST['stock'], $_FILES['image'])) {
        $nom = $_POST['nom'];
        $description = $_POST['description'];
        $prix = $_POST['prix'];
        $stock = $_POST['stock'];
        $article_picture = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $article_picture = file_get_contents($_FILES['image']['tmp_name']);
        }

        $sql = "INSERT INTO article (name, description, prix, date, auteur_ID, lienImg) 
                VALUES (:name, :description, :prix, :date, :auteur_ID, :lienImg)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            'name' => $nom,
            'description' => $description,
            'prix' => $prix,
            'date' => date('Y-m-d H:i:s'),
            'auteur_ID' => $_SESSION['user_id'],
            'lienImg' => $article_picture
        ]);

        if ($result) {
            $article_id = $pdo->lastInsertId();

            $stock_sql = "INSERT INTO stock (article_id, nb_Stock ) VALUES (:article_id, :stock)";
            $stock_stmt = $pdo->prepare($stock_sql);
            $stock_result = $stock_stmt->execute(['article_id' => $article_id,'stock' => $stock]);

            if ($stock_result) {
                header('Location: home.php'); // Décommentez après test
                exit;
            } else {
                echo 'Erreur lors de l\'ajout du stock : ';
                print_r($stock_stmt->errorInfo());
            }
        } else {
            echo 'Erreur lors de l\'ajout de l\'article : ';
            print_r($stmt->errorInfo());
        }
    }
} catch (PDOException $e) {
    echo 'Erreur de base de données : ' . $e->getMessage();
} catch (Exception $e) {
    echo 'Erreur générale : ' . $e->getMessage();
}
?>


<body>
    <!-- Formulaire de création d'article -->
    <main class="form-container">
        <form action="#" method="POST" enctype="multipart/form-data" class="form-vente">
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
