<?php
session_start();
include 'config.php'; // Fichier de connexion à la base de données

if ($_SESSION['role'] !== 1) {
    header('Location: home.php'); // Rediriger si l'utilisateur n'est pas administrateur
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
    <link rel="stylesheet" href="../style/dashbord.css">
</head>
<body>
    <div class="admin-container">
        <h1>Dashbord Administrateur</h1>

        <div class="section">
            <h2>Articles</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                    <tr>
                        <td><?= $article['article_id'] ?></td>
                        <td><?= $article['name'] ?></td>
                        <td><?= $article['auteur_ID'] ?></td>
                        <td>
                            <a href="edit_post.php?id=<?= $article['article_id'] ?>">Modifier</a> |
                            <a href="delete_article_admin.php?id=<?= $article['article_id'] ?>">Supprimer</a>
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
                        <th>Nom d'utilisateur</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['user_ID'] ?></td>
                        <td><?= $user['username'] ?></td>
                        <td><?= $user['role'] ?></td>
                        <td>
                            <a href="edit_user.php?id=<?= $user['user_ID'] ?>">Modifier</a> |
                            <a href="delete_user.php?id=<?= $user['user_ID'] ?>">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
