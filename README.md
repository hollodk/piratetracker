Pirate Tracker
==============

A Symfony project created on May 4, 2015, 10:04 pm.

This is how it works
====================

First you have to get the composer, follow the guide on the page https://getcomposer.org/.

Now to the source.

~~~~
git clone https://github.com/hollodk/piratetracker.git
cd piratetracker
cp app/config/parameters.yml.dist app/config/parameters.yml
php ../composer.phar install
php app/console doctrine:database:create
php app/console doctrine:schema:update --dump-sql --force
chmod -R 777 app/cache app/logs
~~~~
Now browse to the page http://localhost/piratetracker/web/app.php, if you have enabled mod_rewrite, you can leave app.php.

Thats about it.. Have fun.

Special urls
============

Run command to find all possible routes for the system, which includes CRUD to add data to system.

~~~~
php app/console router:debug
~~~~
