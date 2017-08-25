<?php

/**
 * This configuration file is provided to to make it easier for you,
 * to set the correct settings in the index.php file.
 * Read also the report/documentation file :
 * https://github.com/eellak/gsoc17-donationbox/blob/master/Database/README.md
 *
 */

$CONFIG = array (
/**
 * The name of the base database of the donation-box netwrok, which is set during installation.
 * You should not need to change this.
 */
  'dbname' => 'donationbox_network',

/**
 * Your host server name, for example ``localhost``, ``hostname``,
 * ``hostname.example.com``, or the IP address. To specify a port use
 * ``hostname:####``; to specify a Unix socket use
 * ``localhost:/path/to/socket``.
 * You should not need to change this.
 */
  'dbhost' => 'localhost',

/**
 * The user that the index.php file uses to write to the database.
 * This user is defined in the database. Read how you will do this here :
 * https://github.com/eellak/gsoc17-donationbox/blob/master/Database/README.md
 */
  'dbuser' => 'db_admin',

/**
* The password for the above database user.
* This user is defined in the database. Read how you will do this here :
* https://github.com/eellak/gsoc17-donationbox/blob/master/Database/README.md
*/
  'dbpassword' => '123456789',
);
