<?php 

include 'config.php';
session_start();

// Vérifier si l'ID est passé dans l'URL
if (isset($_GET['total'])) {
    $user_id = $_SESSION['user_id'];
    $raw_date = trim($_POST['transac_date']);
    $montant = intval($_GET['total']);
    $adresseFacture = $_POST['adresse_facture'];
    $villeFacture = $_POST['ville_facturation'];
    $codePostal = intval($_POST['code_postal']);

    // Conversion de la date
    $date = DateTime::createFromFormat('Y-m-d', $raw_date);
    if (!$date) {
        die("Erreur : La date fournie n'est pas valide.");
    }

    // Formater la date pour PDO
    $formatted_date = $date->format('Y-m-d');

    try {
        $sql = "INSERT INTO invoice (user_ID, transac_date, montant, adresse_facture, ville_facturation, code_postal) 
                VALUES (:user_ID, :transac_date, :montant, :adresse_facture, :ville_facturation, :code_postal)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            'user_ID' => $user_id,
            'transac_date' => $formatted_date, // Date formatée
            'montant' => $montant,
            'adresse_facture' => $adresseFacture,
            'ville_facturation' => $villeFacture,
            'code_postal' => $codePostal
        ]);

        if ($result) {
            $previousPage = $_SERVER['HTTP_REFERER'] ?? 'home.php';
            header("Location: $previousPage");
            exit;
        } else {
            echo "Erreur lors de l'insertion de la facture.";
        }
    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
    } catch (Exception $e) {
        echo "Erreur générale : " . $e->getMessage();
    }
}
?>
