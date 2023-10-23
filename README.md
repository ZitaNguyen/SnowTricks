# SnowTricks
Création d'un site collaboratif pour faire connaître le snowboard auprès du grand public et aider à l'apprentissage des figures (tricks).
Le site propose un menu avec les sections suivantes : Accueil, Figures, + (Ajouter une nouvelle figure, dès connexion), Inscription/Connexion/Déconnexion

## Fonctionnalités

Le site offre les fonctionnalités suivantes :

### Page d'inscription:
- Crétion un compte afin de pouvoir ajouter une nouvelle figure, modifier la figure, supprimer la figure, commentaire sur une figure
- Un formualaire demande les informations suivantes: nom, adresse email, mot de passe
- Une fois ces informations entrées, l'utilisateur reçoit un email permettant de valider la création du compte et d'activer le compte (via un token de validation)

### Page de connexion:
- Connexion avec votre nom et mot de passe
- Un bouton "mot de passe oublié" redirige l'utilisateur sur la page de mot de passe oublié.

### Page de mot de passe oublié:
- L'utilisateur remplit son nom via un formulaire.
- Si son nom est bien enregistré dans la base de donné, il recevra un email avec un lien pour créer de nouveau mot de passe, qui l'emmènera vers la page de réinitialisation du mot de passe.

### Page de réinitialisation du mot de passe:
- L'utilisateur peut entrer un nouveau mot de passe via un formulaire.
- Une fois son mot de passe changé, l'utilisateur sera redirigé vers la page d'accueil.

### Page d'accueil:
- La page est accessible par tous les utilisateurs.
- Affichage les douze dernière figures du site.
- Pagination pour consulter des figures suivants.
- Consultation des détails de la figure en cliquant sur le titre de la figure.
- Il y a des icônes "crayon" et "poubelle" pour modifier ou supprimer la figure (si vous êtes l'auteur de la figure).
- Il y a des flèches pour remonter en haut et descendre en bas de la page avec jQuery.

### Page des figures:
- Affichage de la liste des figures avec une pagination pour consulter des figures suivantes.
- Il y a des flèches pour remonter en haut et descendre en bas de la page avec jQuery.

### Page d'ajout d'une figure:
- Un formulaire comprend un champ pour le nom du figure, un champ pour la discription, un menu déroulant pour choisir la catégorie , un champ pour télécharger un ou plusieurs photos, un bouton pour ajouter un ou plusieurs videos (géré par jQuery)

### Page de détail d'une figure:
- Affichage les détails d'une figure (description, photos, videos, commentaires)
- Il y a les boutons 'modifier' et 'supprimer' qui apparaissent lorsque l'utilisateur est connecté et est l'auteur de cette figure.
- La modification, la suppression des photos, des videos et de la figure est réalisée par jQuery.
- Possibilité d'ajouter un commentaire si vous êtes connecté.

## Installation
Pour commencer avec ce projet PHP, suivez les étapes ci-dessous
1. Clonez le dépôt
   `git clone https://github.com/ZitaNguyen/SnowTricks.git`
2. Accédez au répertoire du projet
   `cd <nom du répertoire>`
3. Installez les dépendances requises pour le projet
   `composer install`
4. Configurez de la base de données
- Installez MAMP ou XAMPP si besoin
- Modifiez les valeurs dans le fichier `.env` pour les adapter à votre configuratione locale.
   `DATABASE_URL="mysql://USER:PASSWORD@127.0.0.1:8889/SnowTricks?serverVersion=5.7.40"`
5. Exécuter la création de la base de donnée avec la commande: `symfony console doctrine:database:create`
6. Exécuter la migration en base de donnée: `symfony console doctrine:migration:migrate`
7. Exécuter les dataFixtures avec la commande: `php bin/console doctrine:fixtures:load`
8. Démarrez le serveur de développement `symfony server:start`
9. Utilisez Mailer pour envoyer des emails et MailTrap pour recevoir des emails.
- Connectez-vous à MailTrap et entrez cette commande pour commencer à recevoir des emails: `php bin/console messenger:consume async`

## Licence

Ce projet est sous licence Apache License 2.0.