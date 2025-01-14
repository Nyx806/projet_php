<?php
session_start();
include 'config.php';

// Vérifie si l'utilisateur est connecté
$isLoggedIn = isset($_SESSION['user_id']);

// Récupère les informations de l'utilisateur si connecté
if ($isLoggedIn) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT username, photoProfil FROM user WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Définit les variables pour le nom et la photo
    $username = $user['username'];
    $profilePicture = $user['photoProfil'] 
        ? 'data:image/jpeg;base64,' . base64_encode($user['photoProfil']) // Convertit le BLOB en base64 pour l'affichage
        : 'default-avatar.png'; // Image par défaut si pas de photo
} else {
    $username = null;
    $profilePicture = null;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link rel="stylesheet" href="../style/header.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <a href="home.php" class="logo">E-Commerce</a>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="vente.php">Vente</a></li>
                    <li><a href="panier.php">Panier</a></li>
                    <li><a href="listUsers.php">Utilisateurs</a></li>
                    <li><a href="compte.php">Mon Compte</a></li>
                    <li><a href="logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="inscription.php">Inscription</a></li>
                    <li><a href="login.php">Connexion</a></li>
                <?php endif; ?>
            </ul>

            <!-- Affichage du nom et de la photo de profil -->
            <?php if ($isLoggedIn): ?>
                <a href="compte.php" class="clickable-div">
                    <div class="user-info-nav">
                        <img src="<?php echo $profilePicture; ?>" alt="Photo de profil" class="profile-pic">
                        <span class="username"><?php echo htmlspecialchars($username); ?></span>    
                    </div>
                </a>
            <?php endif; ?>
        </div>
    </nav>
</body>
</html>
