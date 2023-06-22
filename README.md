# Blog_SF6.3

## Exemple de structure de BLOG avec Administration avec Symfony 6.3

***

## Pour l'installer :
```
composer install (installation)
composer update (Mise à jour)
symfony console doctrine:database:create (Création de la BDD en ayant renseigné le .ENV)
symfony console make:migration (Création d'une nouvelle migration)
symfony console doctrine:migrations:migrate (Application de la migration en BDD)
```

## l'Authentification :
```
symfony console make:user (Création de l'utilisateur)
symfony console make:auth (Authentification -->> login/logout)

## Les Entités :
```
symfony console doctrine:database:create (Création de la BDD)
symfony console make:entity (Création de l'entité)
symfony console doctrine:schema:validate (Validation du schéma de BDD)
symfony console make:migration (Création d'une migration)
symfony console doctrine:migrations:migrate (Application de la migration en BDD)
```

## SLUG :
```
composer require stof/doctrine-extensions-bundle
```
##
[Documentation](https://symfony.com/bundles/StofDoctrineExtensionsBundle/current/index.html)
##
[Configuration](https://github.com/doctrine-extensions/DoctrineExtensions/blob/main/doc/sluggable.md)