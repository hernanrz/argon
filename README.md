# What is this? #
Argon is a note sharing web application, it includes a RESTful API 

# Installation #
1. Run `php scripts/install_db.php` this will create a database.php file in the settings folder and then will create the tables in your database.

2. In the settings folder, create a copy of the settings_sample.php file and rename it to settings.php
	2.1 You may need to change the AR_FOLDER constant in this file: e.g., if the url looks like `http://localhost/argon/src` then set it to `argon/src/`

# Requirements #
To run argon you need the following:

* PHP 5.6+
* Apache 2.4+
* MySQL 5.x

# TODOS #

The RESTful API is not documented yet, although you can take a look at the source code to see how it works

# License #
	See LICENSE for licensing info
