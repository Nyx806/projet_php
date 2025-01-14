<?php
session_start();
include 'config.php'; // Connexion à la base de données



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

// Vérifier si l'article existe
if (!$article) {
    echo "Erreur: L'article n'existe pas.";
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['title'];
    $description = $_POST['content'];

    // Mise à jour de l'article dans la base de données
    $update_sql = "UPDATE article SET name = :name, description = :description WHERE article_id = :id";
    $update_stmt = $pdo->prepare($update_sql);
    $update_stmt->execute([
        'name' => $name,
        'description' => $description,
        'id' => $article_id
    ]);

    // Rediriger vers la page d'administration après la mise à jour
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'Article</title>
    <link rel="stylesheet" href="../style/edit_post.css">
</head>
<body>
    <div class="admin-container">
        <h1>Modifier l'Article</h1>

        <form action="edit_post.php?id=<?= $article['article_id'] ?>" method="POST">
            <label for="title">Titre de l'article :</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($article['name']) ?>" required>

            <label for="content">Contenu de l'article :</label>
            <textarea id="content" name="content" rows="6" required><?= htmlspecialchars($article['description']) ?></textarea>

            <button type="submit">Mettre à jour l'article</button>
        </form>

        <a href="index.php" class="back-link">Retour au tableau de bord</a>
    </div>
</body>
</html>
