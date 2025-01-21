<?php
include '../config.php';
include '../assets/header.php';

try {
    // Récupère tous les utilisateurs
    $sql = "SELECT user_id, username, photoProfil FROM user";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erreur de base de données : ' . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/style/listUsers.css">
</head>
<body>
    <main class="users-list-container">
        <h1>Liste des Utilisateurs</h1>
        <ul class="users-list">
            <?php foreach ($users as $user): ?>
                <li>
                    <a href="profilUser?id=<?php echo $user['user_id']; ?>" class="user-info">
                        <img src="<?php echo $user['photoProfil'] 
                            ? 'data:image/jpeg;base64,' . base64_encode($user['photoProfil']) 
                            : 'default-avatar.png'; ?>" 
                            alt="Photo de <?php echo htmlspecialchars($user['username']); ?>" 
                            class="user-avatar">
                        <span class="user-name"><?php echo htmlspecialchars($user['username']); ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
<?php include '../assets/footer.php'; ?>
</html>