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


if (isset($_GET['id'])){
    $article_id = $_GET['id'];
    
    // Récupérer l'articles
    $sql = "SELECT name , description, prix, date, lienImg    FROM article WHERE article_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $article_id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    $stock_sql = "SELECT nb_Stock FROM stock WHERE article_ID = :article_ID";
    $stock_stmt = $pdo->prepare($stock_sql);
    $stock_stmt->execute(['article_ID' => $article_id]);
    $stock = $stock_stmt->fetch(PDO::FETCH_ASSOC);
    
    try {
    
        
        if (isset($_POST['nom'], $_POST['description'], $_POST['prix'], $_POST['stock'])) {
            $nom = $_POST['nom'];
            $description = $_POST['description'];
            $prix = $_POST['prix'];
            $stock = $_POST['stock'];
            $article_picture = null;
        
            // Vérifie si une image est fournie
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $article_picture = file_get_contents($_FILES['image']['tmp_name']);
            }
        
            // Détermine la requête SQL selon si l'image est fournie ou non
            if ($article_picture) {
                $sql = "UPDATE article 
                        SET name = :name, description = :description, prix = :prix, date = :date, lienImg = :lienImg
                        WHERE article_id = :article_id";
                $params = [
                    'name' => $nom,
                    'description' => $description,
                    'prix' => $prix,
                    'date' => date('Y-m-d H:i:s'),
                    'lienImg' => $article_picture,
                    'article_id' => $article_id,
                ];
            } else {
                $sql = "UPDATE article 
                        SET name = :name, description = :description, prix = :prix, date = :date
                        WHERE article_id = :article_id";
                $params = [
                    'name' => $nom,
                    'description' => $description,
                    'prix' => $prix,
                    'date' => date('Y-m-d H:i:s'),
                    'article_id' => $article_id,
                ];
            }
        
            // Exécute la requête de mise à jour de l'article
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute($params);
        
            if ($result) {
                // Met à jour le stock associé à l'article
                $stock_sql = "UPDATE stock 
                              SET nb_Stock = :stock 
                              WHERE article_id = :article_id";
                $stock_stmt = $pdo->prepare($stock_sql);
                $stock_result = $stock_stmt->execute([
                    'stock' => $stock,
                    'article_id' => $article_id,
                ]);
        
                if ($stock_result) {
                    echo 'Article et stock mis à jour avec succès !';
                    header('Location: home.php'); // Redirige vers la page d'accueil après mise à jour
                    exit;
                } else {
                    echo 'Erreur lors de la mise à jour du stock : ';
                    print_r($stock_stmt->errorInfo());
                }
            } else {
                echo 'Erreur lors de la mise à jour de l\'article : ';
                print_r($stmt->errorInfo());
            }
        }   
        
    } catch (PDOException $e) {
        echo 'Erreur de base de données : ' . $e->getMessage();
    } catch (Exception $e) {
        echo 'Erreur générale : ' . $e->getMessage();
    }
}
?>


<body>
    <!-- Formulaire de création d'article -->
    <main class="form-container">
        <form action="#" method="POST" enctype="multipart/form-data" class="form-vente">
            <!-- Nom de l'article -->
            <div class="input-group">
               
                <label for="nom">Nom de l'article</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($article['name']); ?>" required>
            </div>

            <!-- Description de l'article -->
            <div class="input-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($article['description']); ?></textarea>

            </div>

            <!-- Prix -->
            <div class="input-group">
                <label for="prix">Prix (€)</label>
                <input type="number" id="prix" name="prix" value="<?php echo htmlspecialchars($article['prix']); ?>" step="0.01" min="0" required>
            </div>

            <!-- Quantité en stock -->
            <div class="input-group">
                <label for="stock">Quantité en Stock</label>
                <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($stock['nb_Stock']); ?>" min="1" required>
            </div>

            <!-- Image -->
            <div class="input-group">
                <label for="image">Image de l'article</label>
                <input type="file" id="image" name="image"  accept="image/*">
            </div>

            <!-- Bouton de soumission -->
            <button type="submit" class="btn">Ajouter l'Article</button>
        </form>
    </main>
</body>
<?php include 'footer.php' ?>
</html>
