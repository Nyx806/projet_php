<?php 
include 'config.php';
session_start();
// Vérifier si l'ID est passé dans l'URL
if( isset($_GET['id'])){
    $increment = 1;
    $article_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    try{
        $sql = "INSERT INTO cart (user_id, article_ID) VALUES (:user_id, :article_id)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute(['user_id' => $user_id,'article_id'=> $article_id]);
        if($result){

            $stock_sql = "UPDATE stock SET nb_Stock = nb_Stock - :increment WHERE article_ID = :article_id";
            $stmt = $pdo->prepare($stock_sql);
            $result = $stmt->execute(['increment' => $increment,'article_id'=> $article_id]);
            $previousPage = $_SERVER['HTTP_REFERER'] ?? 'home.php';

            header("Location: $previousPage");
        }
    }catch (PDOException $e) {
    // Capture l'exception PDO et affiche le message d'erreur
    $message = 'Erreur de base de données : ' . $e->getMessage();
    } catch (Exception $e) {
        // Capture toute autre exception et affiche le message d'erreur
        $message = 'Erreur générale : ' . $e->getMessage();
    }
}

?> 