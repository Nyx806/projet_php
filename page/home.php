<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil - Articles en vente</title>
    <link rel="stylesheet" href="../style/home.css">
    <link rel="stylesheet" href="../style/popup.css"> <!-- Lien vers le fichier popup.css -->
</head>

<?php
include 'header.php';
include 'config.php';

// Récupérer les articles
$sql = "SELECT article_id, name , description, prix, date, lienImg FROM article ORDER BY date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <main class="articles-container">
    <?php foreach ($articles as $article): ?>
        <div class="article">
            <?php
            // Vérifier si l'article a une image
            $articlePicture = !empty($article['lienImg']) 
                ? 'data:image/jpeg;base64,' . base64_encode($article['lienImg']) // Convertit le BLOB en base64
                : 'default-avatar.png'; // Image par défaut si pas d'image

            // Récupérer le stock de cet article spécifique
            $stock_sql = "SELECT nb_Stock FROM stock WHERE article_ID = :article_ID";
            $stock_stmt = $pdo->prepare($stock_sql);
            $stock_stmt->execute(['article_ID' => $article['article_id']]);
            $stock = $stock_stmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <img src="<?php echo $articlePicture; ?>" alt="<?php echo htmlspecialchars($article['name']); ?>">
            <h2><?php echo htmlspecialchars($article['name']); ?></h2>
            <p>Prix : <strong><?php echo htmlspecialchars($article['prix']); ?>€</strong></p>
            <p>Stock : <strong><?php echo htmlspecialchars($stock['nb_Stock'] ?? 0); ?></strong></p>
            <p class="date"><?php echo htmlspecialchars($article['date']); ?></p>
            <button class="buy-button" data-article-id="<?php echo $article['article_id']; ?>">Acheter</button>
            <button><a href="detail.php?id=<?php echo $article['article_id']; ?>">Détails</a></button>
        </div>
    <?php endforeach; ?>
    </main>

    <!-- Fenêtre Popup -->
    <div id="popup" class="popup">
        <p>Article ajouté au panier</p>
    </div>

    <div id="popupOverlay" class="popup-overlay"></div>

    <script>
        // Récupérer les éléments de la popup
        const popup = document.getElementById('popup');
        const overlay = document.getElementById('popupOverlay');
        const buyButtons = document.querySelectorAll('.buy-button');

        let articleIdToBuy = null;

        // Afficher la popup lorsqu'on clique sur "Acheter"
        buyButtons.forEach(button => {
            button.addEventListener('click', function() {
                articleIdToBuy = this.getAttribute('data-article-id');
                popup.style.display = 'block';
                overlay.style.display = 'block';

                // Fermer la popup après 1 seconde
                setTimeout(() => {
                    popup.style.display = 'none';
                    overlay.style.display = 'none';
                    // Rediriger vers la page d'achat
                    window.location.href = 'update_panier.php?id=' + articleIdToBuy;
                }, 1000); // Ferme après 1 seconde
            });
        });

        // Fermer la popup si l'utilisateur clique en dehors de celle-ci
        overlay.addEventListener('click', function() {
            popup.style.display = 'none';
            overlay.style.display = 'none';
        });
    </script>
</body>

<?php include 'footer.php' ?>
</html>
