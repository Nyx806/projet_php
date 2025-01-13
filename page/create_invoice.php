<?php
session_start();
include 'config.php';

require '../fpdf/fpdf.php'; // Assurez-vous que le chemin est correct

$user_sql = "SELECT username, solde  FROM user WHERE user_ID = :user_id";
$stmt = $pdo->prepare($user_sql);
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
// Vérifier si l'ID est passé dans l'URL
if (isset($_GET['total'])) {
    if ($_GET['total']<= $user['solde']){
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
                // Récupérer les informations de la facture
                $sql = "SELECT invoice_ID, user_ID, transac_date, montant, adresse_facture, ville_facturation, code_postal 
                        FROM invoice 
                        WHERE user_ID = :user_id
                        ORDER BY invoice_ID DESC 
                        LIMIT 1";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['user_id' => $_SESSION['user_id']]);
                $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Mettre à jour le solde dans la base de données
                $solde_sql = "UPDATE user SET solde = solde - :panier WHERE user_ID = :userId";
                $stmt = $pdo->prepare($solde_sql);
                $result = $stmt->execute(['panier'=> $montant, 'userId'=> $_SESSION['user_id']]);
                if ($result) {
                    // Récupérer le nouveau solde depuis la base de données
                    $stmt = $pdo->prepare("SELECT solde FROM user WHERE user_ID = :userId");
                    $stmt->execute(['userId' => $_SESSION['user_id']]);
                    $newSolde = $stmt->fetchColumn();

                    // Mettre à jour la session avec le nouveau solde
                    $_SESSION['solde'] = $newSolde;
                }
            

            
                // Vérifier si une facture a été trouvée
                if (!empty($invoices)) {
                    $invoice = $invoices[0]; // Récupérer la première facture

                    
                    $montant = $invoice['montant'];  
                    $adresseFacture = $invoice['adresse_facture'];  
                    $villeFacture = $invoice['ville_facturation']; 
                    $codePostal = $invoice['code_postal'];

                    // Créer un objet FPDF pour générer le PDF
                    $pdf = new FPDF();
                    $pdf->AddPage();

                    // Titre de la facture
                    $pdf->SetFont('Arial', 'B', 16);
                    $pdf->Cell(0, 10, 'Facture', 0, 1, 'C');

                    // Détails de la facture
                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Cell(40, 10, 'ID utilisateur : ' . $user['username'], 0, 1);
                    $pdf->Cell(40, 10, 'Montant : ' . number_format($montant, 2) . ' ' . chr(128), 0, 1);
                    $pdf->Cell(40, 10, 'Adresse de facturation : ' . $adresseFacture, 0, 1);
                    $pdf->Cell(40, 10, 'Ville : ' . $villeFacture, 0, 1);
                    $pdf->Cell(40, 10, 'Code Postal : ' . $codePostal, 0, 1);

                    // Date et heure de la facture
                    $pdf->Cell(40, 10, 'Date : ' . date('Y-m-d H:i:s'), 0, 1);

                    // Ajouter les en-têtes pour forcer le téléchargement
                    // Cela doit être envoyé avant tout contenu HTML ou autre code PHP
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment; filename="facture_' . $user['username'] . '.pdf"');
                    header('Cache-Control: private, max-age=0, must-revalidate');
                    // Générer le PDF et l'envoyer au navigateur
                    $pdf->Output('D', 'facture_' . $user['username'] . '.pdf');

                    // Supprimer les articles du panier après génération du PDF
                    $delete_sql = "DELETE FROM cart WHERE user_id = :user_id";
                    $stmt = $pdo->prepare($delete_sql);
                    $stmt->execute([
                        'user_id' => $user_id,
                    ]);

                } else {
                    echo "Aucune facture trouvée pour cet utilisateur.";
                }
            } else {
                echo "Erreur lors de l'insertion de la facture.";
            }
        } catch (PDOException $e) {
            echo "Erreur de base de données : " . $e->getMessage();
        } catch (Exception $e) {
            echo "Erreur générale : " . $e->getMessage();
        }
    }else{
        $_SESSION['error_message'] = "Votre solde est insuffisant pour effectuer cette transaction.";

        header("Location: panier.php");
        
    }
    
}
?>



