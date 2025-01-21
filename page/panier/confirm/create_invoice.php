<?php
session_start();
include '../../config.php';
require '../../../fpdf/fpdf.php'; // Assurez-vous que le chemin est correct

$user_sql = "SELECT username, solde FROM user WHERE user_ID = :user_id";
$stmt = $pdo->prepare($user_sql);
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'ID est passé dans l'URL
if (isset($_GET['total'])) {
    if ($_GET['total'] <= $user['solde']) {
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

        $formatted_date = $date->format('Y-m-d');

        try {
            // Insérer la facture dans la base de données
            $sql = "INSERT INTO invoice (user_ID, transac_date, montant, adresse_facture, ville_facturation, code_postal) 
                    VALUES (:user_ID, :transac_date, :montant, :adresse_facture, :ville_facturation, :code_postal)";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
                'user_ID' => $user_id,
                'transac_date' => $formatted_date,
                'montant' => $montant,
                'adresse_facture' => $adresseFacture,
                'ville_facturation' => $villeFacture,
                'code_postal' => $codePostal
            ]);

            if ($result) {
                // Récupérer les articles du panier
                $sql = "SELECT article.name, article.prix, COUNT(cart.article_ID) AS quantity
                        FROM cart
                        INNER JOIN article ON cart.article_ID = article.article_id
                        WHERE cart.user_id = :user_id
                        GROUP BY article.article_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['user_id' => $user_id]);
                $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Mettre à jour le solde de l'utilisateur
                $solde_sql = "UPDATE user SET solde = solde - :montant WHERE user_ID = :user_id";
                $stmt = $pdo->prepare($solde_sql);
                $stmt->execute(['montant' => $montant, 'user_id' => $user_id]);

                // Mettre à jour la session avec le nouveau solde
                $_SESSION['solde'] -= $montant;

                // Générer le PDF
                $pdf = new FPDF();
                $pdf->AddPage();

                // Titre de la facture
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(0, 10, 'Facture', 0, 1, 'C');

                // Informations utilisateur
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(40, 10, 'Utilisateur : ' . $user['username'], 0, 1);
                $pdf->Cell(40, 10, 'Montant total : ' . number_format($montant, 2) . chr(128), 0, 1);
                $pdf->Cell(40, 10, 'Adresse de facturation : ' . $adresseFacture, 0, 1);
                $pdf->Cell(40, 10, 'Ville : ' . $villeFacture, 0, 1);
                $pdf->Cell(40, 10, 'Code Postal : ' . $codePostal, 0, 1);
                $pdf->Cell(40, 10, 'Date : ' . date('Y-m-d H:i:s'), 0, 1);

                // Ajouter une ligne vide
                $pdf->Ln(10);

                // Tableau des articles
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(80, 10, 'Article', 1);
                $pdf->Cell(30, 10, 'Quantite', 1);
                $pdf->Cell(40, 10, 'Prix unitaire', 1);
                $pdf->Cell(40, 10, 'Total', 1);
                $pdf->Ln();

                // Contenu des articles
                $pdf->SetFont('Arial', '', 12);
                foreach ($articles as $article) {
                    $article_name = $article['name'];
                    $quantity = $article['quantity'];
                    $price = $article['prix'];
                    $total_price = $quantity * $price;

                    $pdf->Cell(80, 10, $article_name, 1);
                    $pdf->Cell(30, 10, $quantity, 1);
                    $pdf->Cell(40, 10, number_format($price, 2) . chr(128), 1);
                    $pdf->Cell(40, 10, number_format($total_price, 2) . chr(128), 1);
                    $pdf->Ln();
                }

                // Ajouter les en-têtes pour le téléchargement du PDF
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="facture_' . $user['username'] . '.pdf"');
                header('Cache-Control: private, max-age=0, must-revalidate');

                // Générer et envoyer le PDF
                $pdf->Output('D', 'facture_' . $user['username'] . '.pdf');

                // Supprimer les articles du panier
                $delete_sql = "DELETE FROM cart WHERE user_id = :user_id";
                $stmt = $pdo->prepare($delete_sql);
                $stmt->execute(['user_id' => $user_id]);
            } else {
                echo "Erreur lors de l'insertion de la facture.";
            }
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        $_SESSION['error_message'] = "Votre solde est insuffisant pour effectuer cette transaction.";
        header("Location: panier.php");
    }
}
?>
