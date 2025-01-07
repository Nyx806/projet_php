<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="../style/compte.css">
</head>

<?php 
include 'header.php';

$isLoggedIn = isset($_SESSION['user_id']);

// Exemple : récupération du solde depuis la session ou la base de données
$soldeActuel = isset($_SESSION['solde']) ? $_SESSION['solde'] : 0.00; // Valeur par défaut
?>

<body>
    <main>
        <div class="account-container">

            <!-- Informations personnelles -->
            <section class="user-info">
                <h2>Informations personnelles</h2>
                <form action="update_compte.php" method="POST" enctype="multipart/form-data">
                    <div class="user-info">
                        <img src="<?php echo $profilePicture; ?>" alt="Photo de profil" class="profile-pic">                       
                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                        
                        <div class="user-details">
                            <span class="username"><?php echo htmlspecialchars($username); ?></span>
                        </div>
                    </div>
                    <div class="info-field">
                        <label for="username">Nom d'utilisateur :</label>
                        <input type="text" id="username" name="username" value="<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>">
                    </div>
                    <div class="info-field">
                        <label for="email">Email :</label>
                        <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>">
                    </div>
                    <div class="info-field">
                        <label for="password">Mot de passe :</label>
                        <input type="password" id="oldPassword" name="oldPassword" placeholder="Mot de passe actuel">
                    </div>
                    <div class="info-field">
                        <label for="password">Nouveau mot de passe :</label>
                        <input type="password" id="password" name="password" placeholder="Nouveau mot de passe">
                    </div>
                    <button type="submit">Mettre à jour mes informations</button>
                </form>
            </section>

            <!-- Solde du compte -->
            <section class="account-balance">
                <h2>Solde de votre compte</h2>

                <!-- Affichage du solde actuel -->
                <div class="current-balance">
                    <p>Votre solde actuel : <strong><?php echo number_format($soldeActuel, 2, ',', ' '); ?> €</strong></p>
                </div>

                <!-- Formulaire pour ajouter de l'argent -->
                <form action="update_solde.php" method="POST">
                    <div class="add-balance">
                        <label for="add_amount">Ajouter un montant :</label>
                        <div class="input-group">
                            <input type="number" id="add_amount" name="add_amount" step="0.01" min="0" placeholder="0.00" required>
                            <button type="submit">Ajouter</button>
                        </div>
                    </div>
                </form>
            </section>

            <!-- Mes Articles -->
            <section class="my-articles">
                <h2>Mes Articles</h2>
                <div class="articles-list">
                    <!-- Exemple d'article -->
                    <div class="article">
                        <img src="article.jpg" alt="Article 1">
                        <h3>Nom de l'Article</h3>
                        <p>Prix: 29,99€</p>
                        <a href="modifier_article.php?id=1">Modifier</a>
                    </div>
                    <div class="article">
                        <img src="article.jpg" alt="Article 2">
                        <h3>Nom de l'Article</h3>
                        <p>Prix: 19,99€</p>
                        <a href="modifier_article.php?id=2">Modifier</a>
                    </div>
                </div>
            </section>

            <!-- Mes Achats -->
            <section class="my-purchases">
                <h2>Mes Achats</h2>
                <div class="purchases-list">
                    <!-- Exemple d'achat -->
                    <div class="purchase">
                        <h3>Nom de l'Article Acheté</h3>
                        <p>Prix: 29,99€</p>
                    </div>
                    <div class="purchase">
                        <h3>Nom de l'Article Acheté</h3>
                        <p>Prix: 19,99€</p>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>

<?php include 'footer.php' ?>

</html>
