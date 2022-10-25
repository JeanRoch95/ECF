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

* 1 ```composer install``` , afin d'installer toutes les dépendances dans votre projet

* 2 Installer la base de donnée MySQL. Pour paramétrer la création de votre base de donnée, rdv dans le fichier .env du projet, et modifier la variable d'environnement selon vos paramètres :
```DATABASE_URL=mysql://User:Password@127.0.0.1:3306/nameDatabasse?serverVersion=5.7```

* 4 Puis exécuter la création de la base de donnée avec la commande : ```symfony console doctrine:database:create```

* 5 Exécuter la migration en base de donnée : ```symfony console doctrine:migration:migrate```

* 6 Exécuter les dataFixtures avec la commande : ```php bin/console doctrine:fixtures:load```

* 7 Vous pouvez maintenant accéder à votre portfolio en vous connectant au serveur : ```symfony server -d```

----

## Démarrage 

Une fois sur l'application vous n'aurez plus qu'a vous connecter. L'application crée des utilisateurs Admins/Partenaires/structures. 
Par défaut, les logins admin sont : 
* Identifiant : ```admin@admin.fr```
* password : ```password```

----

## Fixtures 

e projet comprend des données factices pour vous permettre de tester rapidement le portfolio.

Pour modifier vos fixtures rendez vous dans le fichier : ```src/DataFixtures/``` Exemple :

```
public function load(ObjectManager $manager): void
    {
$partenaire = new UserPartenaire();
            $partenaire
                ->setEmail($this->faker->email)
                ->setDescription($this->faker->text(20))
                ->setStatus(mt_rand(0, 1))
                ->setPhone($this->faker->phoneNumber)
                ->setPartenaireName($this->faker->name)
                ->setPassword($this->passwordHasher->hashPassword($partenaire, 'password'))
                ->setAddress($this->faker->address)
                ->setCity($this->faker->city)
                ->setZipcode($this->faker->countryCode)
                ->setIsVerified(mt_rand(0, 1));

            $partenaires[] = $partenaire;
            $manager->persist($partenaire);
            $manager->flush();

        }
```
Une fois vos paramètres personnalisés, relancer la commande : ```php bin/console doctrine:fixtures:load```

----

## Fabriqué avec

* [Symfony](https://symfony.com/) - Framework PHP Symfony Latest Stable Release: 6.1

#### Bundle utilisé pour ce projet : 

* [Mailer](https://symfony.com/doc/current/mailer.html)
* [Doctrine](https://symfony.com/doc/current/doctrine.html)
* [SymfonyCast VerifyEmailBundle](https://github.com/SymfonyCasts/verify-email-bundle)
* [VichUploaderBundle](https://symfony.com/bundles/EasyAdminBundle/2.x/integration/vichuploaderbundle.html)
* [Faker](https://github.com/fzaninotto/Faker)

----

## Version

Version 0.0.1

## Auteurs

[Jean-Roch Tomaso](https://github.com/JeanRoch95)
