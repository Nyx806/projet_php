<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil - Articles en vente</title>
    <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="../style/home.css">

</head>

<?php
include 'header.php';
include 'config.php';

// Récupérer les articles
$sql = "SELECT name , description, prix, date    FROM article ORDER BY date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<body>
    <!-- Conteneur principal des articles -->
    <main class="articles-container">
        <?php foreach ($articles as $article): ?>
        <div class="article">
            <img src="https://via.placeholder.com/300x200" alt="Article 1">
            <h2><?php echo $article['name'] ?></h2>
            <p>Prix : <strong><?php echo $article['prix'] ?>€</strong></p>
            <p class="date"><?php echo $article['date'] ?></p>
            <button>Acheter</button>
            <button><a href="detail.php">Détails</a></button>
        </div>
        <?php endforeach ?>
    </main>
</body>
    <?php include 'footer.php' ?>
</html>
