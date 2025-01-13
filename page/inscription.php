<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Inscription</title>
    <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="../style/inscription.css">
</head>

<?php 
include 'config.php';
session_start();
$message = '';

if(isset($_POST['username'],$_POST['password'],$_POST['email'])){

    $username = $_POST['username'];

    // Vérifier si le nom d'utilisateur existe déjà dans la base de données
    $verify_sql = "SELECT COUNT(*) FROM user WHERE username = :username";
    $stmt = $pdo->prepare($verify_sql);
    $stmt->execute(['username' => $username]);
    $count = $stmt->fetchColumn();

    // Si le nom d'utilisateur existe déjà, afficher un message d'erreur
    if ($count > 0) {
        $_SESSION['error_message'] = "ce nom d'utilisateur existe déjà.";

    } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = $_POST['email'];
        $profile_picture = null;
    
        // Vérifie si une photo de profil a été téléchargée
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            // Ouvre le fichier et le lit en tant que données binaires
            $profile_picture = file_get_contents($_FILES['profile_picture']['tmp_name']);
        }
    
        $sql = "INSERT INTO user (username,password,email,photoProfil) VALUE (:username, :password, :email, :profile_picture)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute(['username' => $username, 'password' => $password, 'email' => $email, 'profile_picture' => $profile_picture]);
    
        if ($result) {
            $message = 'Inscription réussie!';
            header('Location: login.php');
        } else {
            $message = 'Erreur lors de l\'inscription.';
        }
    }

}
?>

<body>
    <!-- Bouton Home en haut à gauche -->
    <a href="home.php" class="home-btn">Home</a>

    <div class="signup-container">
        <form action="#" method="POST" class="signup-form" enctype="multipart/form-data">
            <h2>Créer un compte</h2>

            <!-- Champ pour la photo de profil -->
            <div class="input-group">
                <label for="profile_picture">Photo de profil</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
            </div>

            <!-- Champ pour le nom complet -->
            <div class="input-group">
                <label for="username">Nom complet</label>
                <input type="text" id="username" name="username" placeholder="Entrez votre nom complet" required>
                <?php
                if (isset($_SESSION['error_message'])) {
                    // Afficher le message d'erreur
                    echo '<div style="color: red; font-weight: bold;">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
                
                    // Supprimer le message après l'affichage
                    unset($_SESSION['error_message']);
                }
                ?>
            </div>

            <!-- Champ pour l'email -->
            <div class="input-group">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" placeholder="Entrez votre adresse e-mail" required>
            </div>

            <!-- Champ pour le mot de passe -->
            <div class="input-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Créez un mot de passe" required>
            </div>

            <!-- Bouton d'inscription -->
            <button type="submit" class="btn">S'inscrire</button>

            <!-- Lien pour se connecter si déjà inscrit -->
            <div class="links">
                <p>Vous avez déjà un compte ? <a href="login.php">Connectez-vous</a></p>
            </div>
        </form>
    </div>
</body>
</html>
