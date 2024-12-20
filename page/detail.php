<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail du Produit</title>
    <link rel="stylesheet" href="../style/detail.css">
</head>

<?php 
include 'header.php';
include 'config.php';
?>

<body>
    <main>
        <div class="product-detail-container">
            <!-- Image du produit -->
            <div class="product-image">
                <img src="produit.jpg" alt="Produit" />
            </div>

            <!-- Détails du produit -->
            <div class="product-info">
                <h2>Nom du Produit</h2>
                <p class="price">Prix: 29,99€</p>
                <p class="description">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec urna in nisi laoreet cursus.
                </p>
                <p class="availability">Disponibilité: En stock</p>

                <!-- Bouton ajouter au panier -->
                <form action="ajouter_au_panier.php" method="POST">
                    <label for="quantity">Quantité :</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="10">
                    <button type="submit">Ajouter au panier</button>
                </form>
            </div>
        </div>
    </main>
</body>

<?php include 'footer.php' ?>

</html>
