# ECF session decembre 2022 STUDI 

----

## Prérequis sur votre machine pour le bon fonctionnement de ce projet : 

* PHP 8 [Installation PHP 8](https://www.php.net/manual/fr/install.php)
* Symfony 6 minimum & CLI Symfony -- [Installer Symfony](https://symfony.com/doc/current/setup.html) & [Installer CLI](https://symfony.com/download)
* Composer [Installer Composer](https://getcomposer.org/download/)

----

## Installation

Cloner le projet dans le dossier de votre choix puis ouvrez le avec l'éditeur de code souhaité.

Lancez les commandes dans le terminal de votre éditeur dans cet ordre : 

* 1. composer install , afin d'installer toutes les dépendances dans votre projet

* 2. Installer la base de donnée MySQL. Pour paramétrer la création de votre base de donnée, rdv dans le fichier .env du projet, et modifier la variable d'environnement selon vos paramètres :
DATABASE_URL=mysql://User:Password@127.0.0.1:3306/nameDatabasse?serverVersion=5.7

* 4. Puis exécuter la création de la base de donnée avec la commande : symfony console doctrine:database:create

* 5. Exécuter la migration en base de donnée : symfony console doctrine:migration:migrate

* 6. Exécuter les dataFixtures avec la commande : php bin/console doctrine:fixtures:load

* 7. Vous pouvez maintenant accéder à votre portfolio en vous connectant au serveur : symfony server -d
