<?php
session_start();
require 'config.php'; // Connexion à la base de données

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['role']) ==1) {
    header("Location: home.php");
    exit();
}

// Vérifie si l'ID de l'article est présent dans le formulaire
if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    // Supprimer l'article du panier
    $delete_sql = "DELETE FROM article WHERE article_ID = :article_id ";
    $stmt = $pdo->prepare($delete_sql);
    $stmt->execute([
        'article_id' => $article_id,
    ]);

    // Rediriger l'utilisateur vers la page du panier
    $previousPage = $_SERVER['HTTP_REFERER'] ?? 'admin.php';
    header("Location: $previousPage");
    exit();
} 