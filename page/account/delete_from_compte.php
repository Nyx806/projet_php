<?php
session_start();
require '../config.php'; // Connexion à la base de données

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit();
}

$user_id = $_SESSION['user_id'];

// Vérifie si l'ID de l'article est présent dans le formulaire
if (isset($_GET['id'])) {
    $article_id = $_GET['id'];  

    // Supprimer l'article du panier
    $delete_sql = "DELETE FROM article WHERE auteur_ID = :user_id AND article_ID = :article_id";
    $stmt = $pdo->prepare($delete_sql);
    $stmt->execute([
        'user_id' => $user_id,
        'article_id' => $article_id,
    ]);

    // Rediriger l'utilisateur vers la page du panier
    $previousPage = $_SERVER['HTTP_REFERER'] ?? 'home.php';
    header("Location: $previousPage");
    exit();
} else {
    // Si l'article ID n'est pas fourni, redirige vers le panier
    header("Location:  compte.php");
    exit();
}
