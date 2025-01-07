<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier</title>
    <link rel="stylesheet" href="../style/panier.css">
</head>

<?php
include 'header.php';
include 'config.php';

// Récupérer l'ID utilisateur
$user_id = $_SESSION['user_id'];

// Requête SQL pour récupérer les articles du panier de l'utilisateur
$sql = "SELECT article.article_id, article.name, article.prix 
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
                            <td><?php echo htmlspecialchars($article['name']); ?></td>
                            <td><?php echo htmlspecialchars($article['prix']); ?>€</td>
                            <td>
                                <input type="number" value="1" min="1" class="qty-input" data-price="<?php echo $article['prix']; ?>">
                            </td>
                            <td class="total"><?php echo htmlspecialchars($article['prix']); ?>€</td>
                            <td>
                                <form action="remove_article.php" method="POST">
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
            <p>Total : <span id="total">0.00€</span></p>
            <p>Solde disponible : <span id="solde">100.00€</span></p>

            <!-- Bouton de passage de commande -->
            <button id="passer-commande" class="btn">Passer la Commande</button>
        </div>
    </main>
</body>

<?php include 'footer.php'; ?>

<!-- Script pour calculer dynamiquement le total -->
<script>
    const qtyInputs = document.querySelectorAll('.qty-input');
    const totalElement = document.getElementById('total');

    function updateTotal() {
        let total = 0;
        qtyInputs.forEach(input => {
            const price = parseFloat(input.getAttribute('data-price'));
            const quantity = parseInt(input.value);
            total += price * quantity;
        });
        totalElement.textContent = total.toFixed(2) + '€';
    }

    // Met à jour le total lorsqu'une quantité change
    qtyInputs.forEach(input => {
        input.addEventListener('input', updateTotal);
    });

    // Calcul initial du total
    updateTotal();
</script>

</html>
