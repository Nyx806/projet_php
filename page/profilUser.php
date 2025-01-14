<?php 
include 'config.php';
include 'header.php';

// Vérifie si l'ID utilisateur est passé dans l'URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID utilisateur manquant ou invalide.');
}

$user_id = $_GET['id']; // Récupère l'ID utilisateur depuis l'URL

// Récupérer les articles du panier de cet utilisateur
$sql = "SELECT DISTINCT article.article_id, article.name, article.prix 
        FROM article 
        INNER JOIN cart ON article.article_id = cart.article_ID 
        WHERE cart.user_id = :user_id";

$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les créations de cet utilisateur
$article_sql = "SELECT DISTINCT article.article_id, article.name, article.prix, article.lienImg
                FROM article 
                WHERE article.auteur_ID = :user_id";

$stmt = $pdo->prepare($article_sql);
$stmt->execute(['user_id' => $user_id]);
$creations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les informations de l'utilisateur
$user_sql = "SELECT username, email, photoProfil FROM user WHERE user_id = :user_id";
$stmt = $pdo->prepare($user_sql);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Utilisateur introuvable');
}

$username = $user['username'];
$email = $user['email'];
$profilePicture = !empty($user['photoProfil']) 
    ? 'data:image/jpeg;base64,' . base64_encode($user['photoProfil']) 
    : 'default-avatar.png'; // Image de profil par défaut
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?php echo htmlspecialchars($username); ?></title>
    <link rel="stylesheet" href="../style/compte.css">
</head>

<body>
    <main>
        <div class="account-container">

            <!-- Informations personnelles -->
            <section class="user-info">
                <h2>Informations personnelles</h2>
                <div class="user-info">
                    <img src="<?php echo $profilePicture; ?>" alt="Photo de profil" class="profile-pic">
                    <div class="user-details">
                        <span class="username"><?php echo htmlspecialchars($username); ?></span>
                        <p class="email"><?php echo htmlspecialchars($email); ?></p>
                    </div>
                </div>
            </section>

            <!-- Ses Articles -->
            <section class="my-articles">
                <h2>Ses Articles</h2>
                <div class="articles-list">
                    <?php foreach($creations as $creation): ?>
                        <?php 
                            $articlePicture = !empty($creation['lienImg']) 
                                ? 'data:image/jpeg;base64,' . base64_encode($creation['lienImg'])
                                : 'default-avatar.png'; 
                        ?>
                    <div class="article">
                        <img src="<?php echo $articlePicture; ?>" alt="Article 1">
                        <h3><?php echo htmlspecialchars($creation['name']); ?></h3>
                        <p>Prix: <?php echo htmlspecialchars($creation['prix']); ?>€</p>
                        <a href="modifArticle.php?id=<?= htmlspecialchars($creation['article_id']) ?>">Modifier</a>
                        <a href="delete_from_compte.php?id=<?= htmlspecialchars($creation['article_id']) ?>">Supprimer</a>
                    </div>
                    <?php endforeach ?>
                </div>
            </section>
        </div>
    </main>
</body>

<?php include 'footer.php'; ?>

</html>
