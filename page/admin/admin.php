<?php
session_start();
include '../config.php'; // Fichier de connexion à la base de données

if ($_SESSION['role'] !== 1) {
    header('Location:' . BASE_URL . 'home'); // Rediriger si l'utilisateur n'est pas administrateur
    exit();
}

// Récupérer les articles
$posts_sql = "SELECT * FROM article";
$posts_stmt = $pdo->query($posts_sql);
$articles = $posts_stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les utilisateurs
$users_sql = "SELECT * FROM user";
$users_stmt = $pdo->query($users_sql);
$users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panneau d'administration</title>
    <link rel="stylesheet" href="../../style/dashbord.css">
</head>
<body>
    <!-- Bouton Home -->
    <a href="<?php echo BASE_URL; ?>/page/home" class="home-button">Home</a>

    <div class="admin-container">
        <h1>Dashboard Administrateur</h1>

        <div class="section">
            <h2>Articles</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Auteur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                    <?php 
                    $stock_sql = "SELECT nb_Stock FROM stock WHERE article_ID = :article_ID";
                    $stock_stmt = $pdo->prepare($stock_sql);
                    $stock_stmt->execute(['article_ID' => $article['article_id']]);
                    $stock = $stock_stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <tr>
                        <td><?= $article['article_id'] ?></td>
                        <td><?= htmlspecialchars($article['name']) ?></td>
                        <td><?= htmlspecialchars($article['description']) ?></td>
                        <td><?= number_format($article['prix'], 2) ?> €</td>
                        <td><?= $stock['nb_Stock'] ?></td>
                        <td><?= $article['auteur_ID'] ?></td>
                        <td>
                            <a href="edit_post?id=<?= $article['article_id'] ?>">Modifier</a> |
                            <a href="delete_article_admin?id=<?= $article['article_id'] ?>">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2>Utilisateurs</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>photoProfil</th>
                        <th>Nom d'utilisateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['user_ID'] ?></td>
                        <td>
                        <?php if (!empty($article['lienImg'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($user['photoProfil']); ?>" alt="<?php echo htmlspecialchars($article['name']); ?>">
                        <?php else: ?>
                            <img src="default-avatar.png" alt="Image par défaut">
                        <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= $user['role'] ?></td>
                        <td>
                            <a href="edit_user?id=<?= $user['user_ID'] ?>">Modifier</a> |
                            <a href="delete_user_admin?id=<?= $user['user_ID'] ?>">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
