Symfony
=======

A Symfony project created on January 6, 2018, 9:56 am.


Bonjour, voila quelques manip pour lancer le projet:
-php composer.phar require doctrine/doctrine-fixtures-bundle:2.1.*@dev
-Il faut faire update composer.phar + la dépandence, ne surtout pas update tout le fichier composer.phar car plein de dépandence ne sont pas compatible dans les nouvelles versions
-php bin/console doctrine:schema:update --force
-les identifiants sont : user=Admin password=Admin       


