<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier</title>
    <link rel="stylesheet" href="../../style/panier.css">
</head>

<?php
include '../config.php';
include '../assets/header.php';

if (isset($_SESSION['error_message'])) {
    // Afficher le message d'erreur
    echo '<div style="color: red; font-weight: bold;">' . htmlspecialchars($_SESSION['error_message']) . '</div>';

    // Supprimer le message après l'affichage
    unset($_SESSION['error_message']);
}

// Récupérer l'ID utilisateur
$user_id = $_SESSION['user_id'];

// initialiser le total du panier
$total_cart = 0;

// Requête SQL pour récupérer les articles du panier de l'utilisateur
$sql = "SELECT DISTINCT article.article_id, article.name, article.prix 
        FROM article 
        INNER JOIN cart ON article.article_id = cart.article_ID 
        WHERE cart.user_id = :user_id";

$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif



?>

<body>
    <!-- Section panier -->
    <main class="panier-container">
        <table class="panier-table">
            <thead>
                <tr>
                    <th>Nom de l'article</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($articles)): ?>
                    <?php foreach ($articles as $article): ?>
                        <tr>

                        <?php 
                        // Récupérer la quantité spécifique pour cet article

                        
                        $article_id = $article['article_id']; // ID de l'article
                        $quantity_sql = "SELECT COUNT(*) AS article_count FROM cart WHERE cart.user_id = :user_id AND cart.article_ID = :article_id";
                        $stmt = $pdo->prepare($quantity_sql);
                        $stmt->execute(['user_id' => $user_id, 'article_id' => $article_id]);
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);

                        // Si aucun article trouvé, retourne 0
                        $article_count = $result['article_count'] ?? 0; 

                        // Calculer le total pour cet article
                        $total_price = $article_count * $article['prix'];
                        $total_cart = $total_cart + $total_price;
                        ?>

                            <td><?php echo htmlspecialchars($article['name']); ?></td>
                            <td><?php echo htmlspecialchars($article['prix']); ?>€</td>
                            <td>
                                <!-- Sélecteur de quantité -->
                                <form action="update_quantity.php" method="POST">
                                    <input type="number" name="quantity" value="<?php echo $article_count; ?>" min="1" required>
                                    <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                                    <button type="submit">Ajouter</button>
                                </form>
                            </td>
                            <td class="total"><?= $total_price?>€</td>
                            <td>
                                <form action="delete_article.php" method="POST">
                                    <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                                    <button type="submit" class="remove-btn">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Votre panier est vide.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Total du panier -->
        <div class="total-panier">
            <p>Total : <span id="total"><?= $total_cart?>€</span></p>
            <p>Solde disponible : <span id="solde"><?php echo $_SESSION['solde'] ?>€</span></p>

            <!-- Bouton de passage de commande -->
            <button id="passer-commande" class="btn"><a href="confirm/confirmation?total=<?=$total_cart ?>">Passer la Commande</a></button>
        </div>
    </main>
</body>

<?php include '../assets/footer.php'; ?>


</html>
