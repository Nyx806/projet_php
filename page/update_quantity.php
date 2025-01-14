<?php
session_start();
include 'config.php';

// Vérifier si la requête est en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les valeurs soumises par le formulaire
    $article_id = intval($_POST['article_id']);
    $quantity = intval($_POST['quantity']);
    $user_id = $_SESSION['user_id']; // ID de l'utilisateur dans la session

    // Vérifier la quantité
    if ($quantity > 0) {
        // Calculer la différence de quantité
        $current_quantity_sql = "SELECT COUNT(*) AS article_count FROM cart WHERE user_id = :user_id AND article_ID = :article_id";
        $stmt = $pdo->prepare($current_quantity_sql);
        $stmt->execute(['user_id' => $user_id, 'article_id' => $article_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $current_quantity = $result['article_count'];

        $quantity_difference = $quantity - $current_quantity;

        // Si la quantité est positive, ajouter des lignes
        if ($quantity_difference > 0) {
            // Insérer de nouvelles lignes pour la quantité supplémentaire
            for ($i = 0; $i < $quantity_difference; $i++) {
                $insert_sql = "INSERT INTO cart (user_id, article_ID) VALUES (:user_id, :article_id)";
                $stmt = $pdo->prepare($insert_sql);
                $stmt->execute(['user_id' => $user_id, 'article_id' => $article_id]);
            }
        }
        // Si la quantité est négative, supprimer des lignes
        elseif ($quantity_difference < 0) {
            // Supprimer les lignes excédentaires
            $delete_sql = "DELETE FROM cart WHERE user_id = :user_id AND article_ID = :article_id LIMIT :quantity_difference";
            $stmt = $pdo->prepare($delete_sql);
            $stmt->execute(['user_id' => $user_id, 'article_id' => $article_id, 'quantity_difference' => -$quantity_difference]);
        }
    } else {
        // Si la quantité est zéro ou moins, on supprime l'article du panier
        $delete_sql = "DELETE FROM cart WHERE user_id = :user_id AND article_ID = :article_id";
        $stmt = $pdo->prepare($delete_sql);
        $stmt->execute(['user_id' => $user_id, 'article_id' => $article_id]);
    }

    // Rediriger vers la page du panier après la mise à jour
    header('Location: panier.php');
    exit();
}
?>
