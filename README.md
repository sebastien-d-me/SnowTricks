# SnowTricks
Develop from A to Z the SnowTricks community website

### Téléchargement
1. Téléchargez ou clonez le projet.
2. Nécessite PHP : https://www.apachefriends.org/fr/index.html
2. Nécessite Composer : https://getcomposer.org/
3. Nécessite NPM : https://nodejs.org/en/download
4. Nécessite Symfony : https://symfony.com/download
5. Pour les mails j'ai utilisé : Papercut SMTP (pour les test)


### Installation
1. Configurez le fichier .env (en y mettant vos informations de base de données)
2. Effectuez la commande : `composer install` à la racine
3. Effectuez la commande : `npm install` dans le dossier public/assets
4. Effectuez la commande : `php bin/console doctrine:database:create` à la racine du projet
5. Effectuez la commande : `php bin/console make:migration` à la racine du projet
6. Effectuez la commande : `php bin/console doctrine:migrations:migrate` à la racine du projet
7. Effectuez la commande : `php bin/console doctrine:fixtures:load` à la racine du projet
8. Effectuez la commande : `php bin/console messenger:consume async` pour les mails à la racine du projet
9. Lancez le projet avec la commande : `symfony serve`