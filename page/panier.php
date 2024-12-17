<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier</title>
    <link rel="stylesheet" href="../style/panier.css">
</head>
<body>
    <!-- En-tête -->
    <header>
        <h1>Mon Panier</h1>
    </header>

    <!-- Section panier -->
    <main class="panier-container">
        <table class="panier-table">
            <thead>
                <tr>
                    <th>Nom de l'article</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemple de ligne panier (remplacer par PHP) -->
                <tr>
                    <td>Gourde en inox</td>
                    <td>25.00€</td>
                    <td>
                        <input type="number" value="1" min="1" class="qty-input">
                    </td>
                    <td class="total">25.00€</td>
                    <td>
                        <button class="remove-btn">Supprimer</button>
                    </td>
                </tr>
                <!-- Fin exemple -->
            </tbody>
        </table>

        <!-- Total du panier -->
        <div class="total-panier">
            <p>Total : <span id="total">25.00€</span></p>
            <p>Solde disponible : <span id="solde">100.00€</span></p>

            <!-- Bouton de passage de commande -->
            <button id="passer-commande" class="btn">Passer la Commande</button>
        </div>
    </main>

    <!-- Pied de page -->
    <footer>
        <p>&copy; 2024 Boutique en Ligne. Tous droits réservés.</p>
    </footer>

    <script src="panier.js"></script>
</body>
</html>
