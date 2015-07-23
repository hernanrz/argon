# Argón - Note sharing web app #

To set up argon you must import argon.sql in your database and change the settings in src/settings/database_sample.php and then rename that file to database.php

Also you may have to change the AR_FOLDER constant in the settings.php file, depending on where your project is located. E.g., if argon is located in /var/www/argon, then you must set the AR_FOLDER constant

### REST API ###
Argon includes a json based restful api which isn't yet documented although the usage is explained inside the source code

### License ###

All the files in this repo, except for the jQuery library, are available under the GNU public license.