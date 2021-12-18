<?php
/**
 * @package    BugReport
 *
 * @created    16th December 2021
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-310-45 <https://git.vdm.dev/Llewellyn/WEBD-310-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_WEBD') or die('Restricted access');

/**
 * SETUP YOUR DATABASE AND USER
 *
 * Make sure you have your mysql server installed
 *      sudo apt update
 *      sudo apt install mysql-server
 *      sudo mysql_secure_installation
 *
 * Make sure you have your MySqli extension enabled/installed
 *      sudo apt-get install php-mysql
 *      ALSO: enable it in your php.ini file and reload apache
 *
 * This is how you can setup your DATABASE:
 *      sudo mysql -u root -p
 *      CREATE DATABASE d_web;
 *      quit;
 *
 * This is how you can create your DATABASE user
 *      sudo mysql -u root -p
 *      CREATE USER 'u_web'@'localhost' IDENTIFIED BY 'abcd1234567890EFG';
 *      GRANT ALL PRIVILEGES ON d_web.* To 'u_web'@'localhost';
 *      FLUSH PRIVILEGES;
 *      quit;
 */

// The database connection
// do not access directly!
// USE: $db = getDB();
$MY_DB_CONNECTION = null;
# the application configurations
$MY_DB_HOST = 'localhost';
$MY_DB_USER = 'u_web';
$MY_DB_NAME = 'd_web';
$MY_DB_PASSWORD = 'abcd1234567890EFG';
$MY_DB_PORT = '3306';

/**
 * SETUP YOUR DATABASE TABLE
 *
 * Use the database setup file called debug_table.sql to initialize the database table for the program.
 *
 * This is how you can initialize the tables (placing debug_tables.sql in /home/username/debug_tables.sql)
 *      sudo mysql -u root -p
 *      USE d_web;
 *      SOURCE /home/username/debug_tables.sql
 *      quit;
 */
