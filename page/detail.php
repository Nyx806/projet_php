<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail du Produit</title>
    <link rel="stylesheet" href="../style/detail.css">
</head>

<?php 
include 'header.php';
include 'config.php';

// Vérifier si l'ID est passé dans l'URL
if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    // Récupérer les détails de l'article avec cet ID
    $sql = "SELECT * FROM article WHERE article_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $article_id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'article existe
    if (!$article) {
        echo "Article non trouvé.";
        exit;
    }
} else {
    echo "ID d'article manquant.";
    exit;
}
?>

<body>
    <main>
        <div class="product-detail-container">
            <!-- Image du produit -->
            <div class="product-image">
                <?php if (!empty($article['lienImg'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($article['lienImg']); ?>" alt="<?php echo htmlspecialchars($article['name']); ?>">
                <?php else: ?>
                    <img src="default-avatar.png" alt="Image par défaut">
                <?php endif; ?>
            </div>

            <!-- Détails du produit -->
            <div class="product-info">
                <h2><?php echo htmlspecialchars($article['name']); ?></h2>
                <p class="price">Prix: <?php echo htmlspecialchars($article['prix']); ?>€</p>
                <p class="description">
                    <?php echo nl2br(htmlspecialchars($article['description'])); ?>
                </p>
                <p class="availability">Disponibilité: En stock</p>

                <!-- Bouton ajouter au panier -->
                <form action="ajouter_au_panier.php" method="POST">
                    <label for="quantity">Quantité :</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="10">
                    <button type="submit">Ajouter au panier</button>
                </form>
            </div>
        </div>
    </main>
</body>

<?php include 'footer.php' ?>

</html>
