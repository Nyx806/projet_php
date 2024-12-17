<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion</title>
    <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="../style/login.css">
</head>
<body>

    <!-- Bouton Home en haut à gauche -->
    <div class="navbar">
        <a href="home.php" class="home-btn">Home</a>
    </div>

    <div class="login-container">
        <form action="#" method="POST" class="login-form">
            <h2>Connexion</h2>
            <!-- Champ pour le nom d'utilisateur -->
            <div class="input-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" placeholder="Entrez votre nom d'utilisateur" required>
            </div>

            <!-- Champ pour le mot de passe -->
            <div class="input-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
            </div>

            <!-- Bouton de connexion -->
            <button type="submit" class="btn">Se connecter</button>

            <!-- Lien pour s'inscrire ou récupérer un mot de passe -->
            <div class="links">
                <a href="">Mot de passe oublié ?</a>
                <a href="inscription.php">Créer un compte</a>
            </div>
        </form>
    </div>

</body>
</html>
