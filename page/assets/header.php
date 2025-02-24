<?php
session_start();

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
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style/header.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <a href="<?php echo BASE_URL; ?>/page/home" class="logo">E-Commerce</a>
            <ul class="nav-links">
                <li><a href="<?php echo BASE_URL; ?>/page/home">Home</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="<?php echo BASE_URL; ?>page/vente/vente">Vente</a></li>
                    <li><a href="<?php echo BASE_URL; ?>page/panier/panier">Panier</a></li>
                    <li><a href="<?php echo BASE_URL; ?>page/account/compte">Mon Compte</a></li>
                    <li><a href="<?php echo BASE_URL; ?>page/userList/listUsers">Utilisateurs</a></li>
                    <?php if ($_SESSION['role'] == 1) { ?>
                        <li><a href="<?php echo BASE_URL; ?>page/admin/admin">Dashbord</a></li>
                    <?php } ?>
                    <li><a href="<?php echo BASE_URL; ?>page/authentification/logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="<?php echo BASE_URL; ?>page/userList/listUsers">Utilisateurs</a></li>
                    <li><a href="<?php echo BASE_URL; ?>page/authentification/inscription">Inscription</a></li>
                    <li><a href="<?php echo BASE_URL; ?>page/authentification/login">Connexion</a></li>
                <?php endif; ?>
            </ul>

            <!-- Affichage du nom et de la photo de profil -->
            <?php if ($isLoggedIn): ?>
                <a href="<?php echo BASE_URL; ?>page/account/compte" class="clickable-div">
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
