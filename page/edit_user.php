<?php
session_start();
include 'config.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est administrateur
if ($_SESSION['role'] !== 1) {
    header('Location: home.php'); // Redirection si non admin
    exit();
}

// Vérifier si un ID utilisateur est fourni
if (!isset($_GET['id'])) {
    die("Aucun utilisateur sélectionné.");
}

$user_id = intval($_GET['id']);

// Récupérer les informations de l'utilisateur
$sql = "SELECT * FROM user WHERE user_ID = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur introuvable.");
}

// Mettre à jour les informations de l'utilisateur après soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = intval($_POST['role']);
    $password = $_POST['password'];

    if (empty($username) || empty($email)) {
        $error = "Le nom d'utilisateur et l'adresse e-mail ne peuvent pas être vides.";
    } else {
        $update_sql = "UPDATE user SET username = :username, email = :email, role = :role WHERE user_ID = :user_id";
        $params = [
            'username' => $username,
            'email' => $email,
            'role' => $role,
            'user_id' => $user_id,
        ];

        // Mettre à jour le mot de passe uniquement s'il est fourni
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $update_sql = "UPDATE user SET username = :username, email = :email, role = :role, password = :password WHERE user_ID = :user_id";
            $params['password'] = $hashed_password;
        }

        $stmt = $pdo->prepare($update_sql);
        $stmt->execute($params);
        $success = "Les informations de l'utilisateur ont été mises à jour avec succès.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
    <link rel="stylesheet" href="../style/edit_user.css">
</head>
<body>
    <div class="edit-user-container">
        <h1>Modifier Utilisateur</h1>

        <?php if (isset($error)): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="success-message"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="role">Rôle</label>
                <select name="role" id="role">
                    <option value="0" <?= $user['role'] == 0 ? 'selected' : '' ?>>Utilisateur</option>
                    <option value="1" <?= $user['role'] == 1 ? 'selected' : '' ?>>Administrateur</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Nouveau mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Laisser vide pour ne pas modifier">
            </div>

            <div class="form-actions">
                <button type="submit">Mettre à jour</button>
                <a href="admin.php" class="cancel-button">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>
