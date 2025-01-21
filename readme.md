# Projet Final PHP : Site E-Commerce

## Description

Ce projet consiste en la création d’un site e-commerce développé en PHP natif (sans l'utilisation de frameworks backend comme Laravel). Le serveur sera simulé avec XAMPP, une plateforme de développement qui inclut Apache, MariaDB, PHP et Perl.

Le projet comporte plusieurs fonctionnalités, allant de la gestion des utilisateurs à la création d’articles à vendre, en passant par la gestion d’un panier et des commandes. Une partie administrateur permettra de gérer les utilisateurs et les articles.

## Fonctionnalités

### Authentification et Comptes Utilisateurs

- **Page d’inscription (/register)**  
  Permet de créer un compte utilisateur. Les informations obligatoires sont :  
  - Adresse e-mail (unique)  
  - Nom d’utilisateur (unique)  
  Une fois l'inscription terminée, l’utilisateur est automatiquement connecté et redirigé vers la page d'accueil.

- **Page de connexion (/login)**  
  Permet aux utilisateurs existants de se connecter. Une fois connecté, l’utilisateur est redirigé vers la page d'accueil.

- **Page de compte (/account)**  
  Permet à l'utilisateur de :  
  - Voir ses informations personnelles  
  - Modifier ses informations personnelles (adresse e-mail, mot de passe, etc.)  
  - Voir les articles qu'il a achetés  
  - Voir les articles qu'il a publiés  
  - Ajouter de l’argent à son solde  

### Gestion des Articles

- **Page Home (/)**
  Liste tous les articles en vente avec les plus récents affichés en premier.

- **Page de vente (/sell)**  
  Permet de créer un nouvel article à vendre. Le formulaire de création comprend les champs suivants :  
  - Nom de l'article  
  - Description  
  - Prix  
  - Quantité (Stock)

- **Page de détails (/detail?id=ARTICLE_ID)**  
  Affiche les détails d’un article précisé par un paramètre GET. L’utilisateur peut ajouter cet article à son panier.

- **Page de modification (/edit)**  
  Permet à l'utilisateur de modifier ou de supprimer un article qu'il a publié (uniquement pour le créateur de l’article ou un administrateur).

### Gestion du Panier et des Commandes

- **Page Panier (/cart)**  
  Affiche les articles présents dans le panier de l'utilisateur connecté. Les fonctionnalités incluent :  
  - Augmenter ou diminuer la quantité d'un article  
  - Supprimer un article du panier  
  - Passer commande si le solde est suffisant

- **Page de confirmation (/cart/validate)**  
  Permet de :  
  - Valider le contenu du panier  
  - Renseigner les informations de facturation  
  - Finaliser la commande (si le solde est suffisant), vider le panier et générer une facture

### Partie Administrateur

- **Tableau de bord Administrateur (/admin)**  
  Accessible uniquement aux administrateurs. Les fonctionnalités incluent :  
  - Gérer les articles (modifier ou supprimer n'importe quel article)  
  - Gérer les utilisateurs (modifier ou supprimer n'importe quel utilisateur)

## Contraintes Techniques

- Le backend doit être entièrement réalisé en PHP natif.
- L'utilisation de frameworks backend est strictement interdite.
- XAMPP est utilisé pour la simulation du serveur.
- MariaDB sera utilisé comme base de données pour le stockage des informations.

## Base de Données

### Tables principales

- **Utilisateurs**  
  - user_ID  (unique)
  - username (unique)  
  - email (unique)  
  - password (haché)  
  - solde  
  - role (utilisateur ou administrateur)

- **Article**  
  - article_id (unique)
  - name
  - description  
  - prix  
  - date 
  - auteur_ID (référence à la table Utilisateurs)
  - lienImg

- **Cart**  
  - cart_ID  
  - user_ID  
  - article_ID  
  

- **Invoice**  
  - invoice_id  
  - user_ID  
  - transac_date 
  - montant
  - adresse_facture
  - ville_facturation
  - code_postal

- **Stock**  
  - stock_ID  
  - article_ID 
  - nb_Stock

## Installation

### Prérequis

- XAMPP installé sur votre machine
- Accès à phpMyAdmin pour gérer la base de données

### Configuration

1. Cloner votre dépôt dans le répertoire `htdocs` de XAMPP.
2. Créer une base de données dans phpMyAdmin et importer le fichier `ecommerce.sql` fourni.
3. Configurer les identifiants de connexion à la base de données dans le fichier `config.php`.

### Lancer le projet

1. Démarrez Apache et MySQL depuis le panneau de contrôle XAMPP.
2. Accédez au projet via `http://localhost/projet_php/page/home`.
3. Pour accéder à la partie admin il faudra modifier le role de l'un des utilisateur créer avec l'interface de phpMyAdmin pour le mettre à 1 (il est a 0 par défaut, 0 étant les utilisateur normaux)

## Auteur

Merlo Meyffren Antonin et Marc Reimen