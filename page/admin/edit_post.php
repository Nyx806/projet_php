<?php
session_start();
include '../config.php'; // Connexion à la base de données

if ($_SESSION['role'] !== 1) {
    header('Location:'. BASE_URL . '/page/home'); // Rediriger si l'utilisateur n'est pas administrateur
    exit();
}

// Vérifier si l'ID de l'article est passé dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Erreur: ID de l'article manquant.";
    exit();
}

$article_id = $_GET['id'];

// Récupérer les informations de l'article à modifier
$sql = "SELECT * FROM article WHERE article_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $article_id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

$stock_sql = "SELECT nb_Stock FROM stock WHERE article_ID = :article_ID";
$stock_stmt = $pdo->prepare($stock_sql);
$stock_stmt->execute(['article_ID' => $article['article_id']]);
$stock = $stock_stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'article existe
if (!$article) {
    echo "Erreur: L'article n'existe pas.";
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['title'];
    $description = $_POST['content'];
    $prix = $_POST['prix'];
    $quantity = $_POST['stock'];

    // Mise à jour de l'article dans la base de données
    $update_sql = "UPDATE article SET name = :name, description = :description, prix = :prix WHERE article_id = :id";
    $update_stmt = $pdo->prepare($update_sql);
    $update_stmt->execute([
        'name' => $name,
        'description' => $description,
        'prix' => $prix,
        'id' => $article_id
    ]);

    
    $update_stock_sql = "UPDATE stock SET nb_Stock = :nbStock WHERE article_id = :id";
    $update_stock_stmt = $pdo->prepare($update_stock_sql);
    $update_stock_stmt->execute([
        'nbStock' => $quantity,
        'id' => $article_id
    ]);


    // Rediriger vers la page d'administration après la mise à jour
    header('Location: admin');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'Article</title>
    <link rel="stylesheet" href="../../style/edit_post.css">
</head>
<body>
    <div class="admin-container">
        <h1>Modifier l'Article</h1>

        <form action="edit_post.php?id=<?= $article['article_id'] ?>" method="POST">
            <label for="title">Titre de l'article :</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($article['name']) ?>" required>

            <label for="content">Contenu de l'article :</label>
            <textarea id="content" name="content" rows="6" required><?= htmlspecialchars($article['description']) ?></textarea>

            <label for="title">Prix (€) :</label>
            <input type="number" id="prix" name="prix" value="<?= htmlspecialchars($article['prix']) ?>" step="0.01" min="0" required>

            <label for="title">Quantité :</label>
            <input type="number" id="stock" name="stock" value="<?= htmlspecialchars($stock['nb_Stock']) ?>" min="1" required>

            <button type="submit">Mettre à jour l'article</button>
        </form>

        <a href="admin" class="back-link">Retour au tableau de bord</a>
    </div>
</body>
</html>
