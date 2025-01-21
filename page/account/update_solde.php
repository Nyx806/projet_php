<?php
include '../config.php';
session_start();


// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit;
}

// Récupérer le montant ajouté
if (isset($_POST['add_amount'])) {
    $addAmount = floatval($_POST['add_amount']); // Convertir en float pour éviter les erreurs

    if ($addAmount > 0) {
        // Récupérer l'ID de l'utilisateur depuis la session
        $userId = $_SESSION['user_id'];

        try {

            
                // Mettre à jour le solde dans la base de données
                $sql = "UPDATE user SET solde = solde + :addAmount WHERE user_ID = :userId";
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute(['addAmount'=> $addAmount, 'userId'=> $userId]);
                if ($result) {
                    // Récupérer le nouveau solde depuis la base de données
                    $stmt = $pdo->prepare("SELECT solde FROM user WHERE user_ID = :userId");
                    $stmt->execute(['userId' => $userId]);
                    $newSolde = $stmt->fetchColumn();

                    // Mettre à jour la session avec le nouveau solde
                    $_SESSION['solde'] = $newSolde;
                    header('Location: compte');

                }
            
        } catch (Exception $e) {
            error_log(message: "Erreur lors de la mise à jour du solde : " . $e->getMessage());
           
        }
    }
    exit;
}
?>
