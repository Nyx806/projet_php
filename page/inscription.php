<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Inscription</title>
    <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="../style/inscription.css">

</head>
<body>
    <div class="signup-container">
        <form action="#" method="POST" class="signup-form">
            <h2>Créer un compte</h2>

            <!-- Champ pour le nom complet -->
            <div class="input-group">
                <label for="fullname">Nom complet</label>
                <input type="text" id="fullname" name="fullname" placeholder="Entrez votre nom complet" required>
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

            <!-- Champ pour confirmer le mot de passe -->
            <div class="input-group">
                <label for="confirm-password">Confirmer le mot de passe</label>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirmez votre mot de passe" required>
            </div>

            <!-- Bouton d'inscription -->
            <button type="submit" class="btn">S'inscrire</button>

            <!-- Lien pour se connecter si déjà inscrit -->
            <div class="links">
                <p>Vous avez déjà un compte ? <a href="#">Connectez-vous</a></p>
            </div>
        </form>
    </div>
</body>

</html>
