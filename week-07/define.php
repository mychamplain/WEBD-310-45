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

// add the configurations
if (file_exists(__DIR__ . '/config.php'))
{
	include_once __DIR__ . '/config.php';
}
// add the database connection
if (file_exists(__DIR__ . '/database.php'))
{
	include_once __DIR__ . '/database.php';
}
// add the form validation
if (file_exists(__DIR__ . '/form.php'))
{
	include_once __DIR__ . '/form.php';
}
