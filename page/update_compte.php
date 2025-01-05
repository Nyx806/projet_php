<?php 
include 'config.php';
session_start();

$message = '';


// Vérification des champs de formulaire et de l'utilisateur connecté
try {
    if (isset($_POST['username']) && isset($_POST['email']) && ($_POST['username'] != $_SESSION['username'] || $_POST['email'] != $_SESSION['email'])) {
        
        // Récupérer les données du formulaire
        $username = $_POST['username'];
        $email = $_POST['email'];
        
        // Préparer la requête SQL
        $sql = "UPDATE user SET username = :username, email = :email WHERE user_id = :id";
        $stmt = $pdo->prepare($sql);
        
        // Exécuter la requête avec les paramètres
        $result = $stmt->execute(['username' => $username, 'email' => $email, 'id' => $_SESSION['user_id']]);
        
        // Vérifier si la requête a réussi
        if ($result) {
            // Mise à jour du cache de session
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            
            // Message de succès dans la session
            $_SESSION['message'] = 'Changement réussi';
            header('Location: compte.php');
            
        } else {
            $message = 'Erreur lors du changement des informations';
        }
    }

    // Vérification et mise à jour du mot de passe
    if (isset($_POST['oldPassword'], $_POST['password']) && !empty($_POST['password'])) {
        // Récupérer l'utilisateur
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM user WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if (password_verify($_POST['oldPassword'], $user['password'])) {
            $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Mettre à jour le mot de passe
            $sql = "UPDATE user SET password = :newPassword WHERE user_id = :id";
            $stmt = $pdo->prepare($sql);
            
            // Exécuter la requête
            $result = $stmt->execute(['newPassword' => $newPassword, 'id' => $_SESSION['user_id']]);
            if ($result) {
                $_SESSION['message'] = 'Mot de passe modifié avec succès';
                header('Location: compte.php');
                
            } else {
                $message = 'Erreur lors du changement du mot de passe';
            }
        } else {
            $message = 'Mot de passe actuel incorrect';
        }
    }

    if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0){
        $profile_picture = file_get_contents($_FILES['profile_picture']['tmp_name']);

        $sql = "UPDATE user SET photoProfil = :profile_picture WHERE user_id = :id";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute(['profile_picture' => $profile_picture, 'id' =>$_SESSION['user_id']]);
        
        if ($result) {
            $_SESSION['message'] = 'photo modifier avec succès';
            header('Location: compte.php');
            exit;
        } else {
            $message = 'Erreur lors de l\'upload de la photo';
        }
    }


} catch (PDOException $e) {
    // Capture l'exception PDO et affiche le message d'erreur
    $message = 'Erreur de base de données : ' . $e->getMessage();
} catch (Exception $e) {
    // Capture toute autre exception et affiche le message d'erreur
    $message = 'Erreur générale : ' . $e->getMessage();
}

// Affichage du message d'erreur ou de succès
echo $message;
?>
