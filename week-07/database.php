<?php
/**
 * @package    BugReport
 *
 * @created    16th December 2021
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-310-45 <https://git.vdm.dev/Llewellyn/WEBD-310-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 *
 * MySQLi database driver
 * https://www.php.net/manual/en/book.mysqli.php
 *
 * Joomla mysqli DRIVER
 * https://github.com/joomla/joomla-cms/blob/3.10-dev/libraries/joomla/database/driver/mysqli.php
 */

// No direct access to this file
defined('_WEBD') or die('Restricted access');

// we make sure mysqli is supported
if (!function_exists('mysqli_connect'))
{
	die('The MySQLi extension for PHP is not installed or enabled.  Aborting.');
}

// the database connection
function getDB()
{
	// get the Global connection
	global $MY_DB_CONNECTION;
	global $MY_DB_HOST;
	global $MY_DB_USER;
	global $MY_DB_PASSWORD;
	global $MY_DB_NAME;
	global $MY_DB_PORT;
	// check if we have a connection
	if (isset($MY_DB_CONNECTION) && $MY_DB_CONNECTION instanceof mysqli)
	{
		return $MY_DB_CONNECTION;
	}
	// set all the options
	$options['host']     = (isset($MY_DB_HOST)) ? $MY_DB_HOST : 'localhost';
	$options['user']     = (isset($MY_DB_USER)) ? $MY_DB_USER : '';
	$options['password'] = (isset($MY_DB_PASSWORD)) ? $MY_DB_PASSWORD : '';
	$options['database'] = (isset($MY_DB_NAME)) ? $MY_DB_NAME : '';
	$options['port']     = (isset($MY_DB_PORT)) ? (int) $MY_DB_PORT : 3306;
	$options['socket']   = null;

	// start building the connection

	// THANKS TO Joomla
	// https://github.com/joomla/joomla-cms/blob/3.10-dev/libraries/joomla/database/driver/mysqli.php
	$regex = '/^(?P<host>((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))(:(?P<port>.+))?$/';

	if (preg_match($regex, $options['host'], $matches))
	{
		// It's an IPv4 address with or without port
		$options['host'] = $matches['host'];

		if (!empty($matches['port']))
		{
			$port = $matches['port'];
		}
	}
	elseif (preg_match('/^(?P<host>\[.*\])(:(?P<port>.+))?$/', $options['host'], $matches))
	{
		// We assume square-bracketed IPv6 address with or without port, e.g. [fe80:102::2%eth1]:3306
		$options['host'] = $matches['host'];

		if (!empty($matches['port']))
		{
			$port = $matches['port'];
		}
	}
	elseif (preg_match('/^(?P<host>(\w+:\/{2,3})?[a-z0-9\.\-]+)(:(?P<port>[^:]+))?$/i', $options['host'], $matches))
	{
		// Named host (e.g example.com or localhost) with or without port
		$options['host'] = $matches['host'];

		if (!empty($matches['port']))
		{
			$port = $matches['port'];
		}
	}
	elseif (preg_match('/^:(?P<port>[^:]+)$/', $options['host'], $matches))
	{
		// Empty host, just port, e.g. ':3306'
		$options['host'] = 'localhost';
		$port            = $matches['port'];
	}

	// ... else we assume normal (naked) IPv6 address, so host and port stay as they are or default

	// Get the port number or socket name
	if (isset($port) && is_numeric($port))
	{
		$options['port'] = (int) $port;
	}
	elseif (isset($port))
	{
		$options['socket'] = $port;
	}

	// Attempt to connect to the server.
	$MY_DB_CONNECTION = @mysqli_connect(
		$options['host'], $options['user'], $options['password'], null, $options['port'], $options['socket']
	);

	// check if we had success in the connection
	if (! $MY_DB_CONNECTION)
	{
		die('Could not connect to MySQL server, check that you have the correct USER and/or DATABASE details.  Aborting.');
	}

	// Set sql_mode to non_strict mode
	mysqli_query( $MY_DB_CONNECTION, "SET @@SESSION.sql_mode = '';");

	// select the given database.
	if (!empty($options['database']))
	{
		if (!mysqli_select_db( $MY_DB_CONNECTION, $options['database']))
		{
			die('Could not connect to MySQL database.  Aborting.');
		}
	}

	// return the connection
	return $MY_DB_CONNECTION;
}

// get reports
function getReports()
{
	$db = getDB();
	// the mysql to select the data
	$select = array();
	$select[] = "a.id AS id";
	$select[] = "a.bug AS bug";
	$select[] = "a.product AS product_id";
	$select[] = "p.name AS product";
	$select[] = "p.description AS description";
	$select[] = "a.version AS version_id";
	$select[] = "v.name AS version";
	$select[] = "a.os AS os_id";
	$select[] = "o.name AS os";
	$select[] = "a.hardware AS hardware_id";
	$select[] = "h.name AS hardware";
	$select[] = "a.occurrence AS occurrence";
	$select[] = "a.solution AS solution";
	$query = array();
	$query[] = "SELECT " . implode(', ', $select);
	$query[] = "FROM `debug_report` AS `a`";
	$query[] = "LEFT JOIN `debug_product` AS `p` ON p.id = a.product";
	$query[] = "LEFT JOIN `debug_product_version` AS `v` ON v.id = a.version";
	$query[] = "LEFT JOIN `debug_os` AS `o` ON o.id = a.os";
	$query[] = "LEFT JOIN `debug_hardware` AS `h` ON h.id = a.hardware";
	$query[] = "ORDER BY p.name desc";
	// convert to string
	$query = (string) implode(PHP_EOL, $query);
	// get the data
	$data = mysqli_query($db, $query);
	// did we get the data
	if (!$data)
	{
		die("Database query failed!");
	}
	// our return array
	$return = array();
	// if we have data lets load it
	while ($row = mysqli_fetch_assoc($data))
	{
		$return[] = $row;
	}
	// we return all we found
	return $return;
}

// get the data
function getData(string $table)
{
	$db = getDB();
	// the mysql to select the data
	$select = array();
	$select[] = "a.id";
	$select[] = "a.name";
	$query = array();
	$query[] = "SELECT " . implode(', ', $select);
	$query[] = "FROM `$table` AS `a`";
	$query[] = "ORDER BY a.name desc";
	// convert to string
	$query = (string) implode(PHP_EOL, $query);
	// get the data
	$data = mysqli_query($db, $query);
	// did we get the data
	if (!$data)
	{
		die("Database query failed!");
	}
	// our return array
	$return = array();
	// if we have data lets load it
	while ($row = mysqli_fetch_assoc($data))
	{
		$return[$row['id']] = $row['name'];
	}
	// we return all we found
	return $return;
}

// check if ID exist
function idExist(int $id, string $table): bool
{
	$db = getDB();
	// the mysql to select the data
	$select = array();
	$select[] = "a.id";
	$query = array();
	$query[] = "SELECT " . implode(', ', $select);
	$query[] = "FROM `$table` AS `a`";
	$query[] = "WHERE a.id = " . (int) $id;
	// convert to string
	$query = (string) implode(PHP_EOL, $query);
	// get the data
	$data = mysqli_query($db, $query);
	// did we get the data
	if (!$data)
	{
		return false;
	}
	// our return array
	$return = array();
	// if we have data lets load it
	while ($row = mysqli_fetch_assoc($data))
	{
		$return[$row['id']] = $row['id'];
	}
	// we return all we found
	return count($return) > 0;
}

// get the products
function getProducts()
{
	// we return all we found
	return getData('debug_product');
}

// get the versions
function getVersions()
{
	// we return all we found
	return getData('debug_product_version');
}

// get the hardware
function getOSs()
{
	// we return all we found
	return getData('debug_os');
}

// get the hardware
function getHardware()
{
	// we return all we found
	return getData('debug_hardware');
}

// save report
function saveReport(array $data)
{
	$db = getDB();
	// if there is an ID we update
	if (isset($data['id']) && $data['id'] > 0)
	{
		// get the ID
		$id = $data['id'];
		unset($data['id']);
		// build the fields
		$fields = array();
		foreach ($data as $key => $value)
		{
			$fields[] = "`$key`=$value";
		}
		// the sql update
		$sql = array();
		$sql[] = "UPDATE `debug_report`";
		$sql[] = "SET " . implode(', ', $fields);
		$sql[] = "WHERE id=" . $id;
	}
	else
	{
		// always remove the ID
		unset($data['id']);
		// build the fields
		$columns = array();
		$values = array();
		foreach ($data as $key => $value)
		{
			$columns[] = $key;
			$values[] = $value;
		}
		// the sql update
		$sql = array();
		$sql[] = "INSERT INTO `debug_report` (" . implode(', ', $columns) . ")";
		$sql[] = "VALUES (" . implode(', ', $values) . ");";
	}
	// execute the query
	if (mysqli_query($db, implode(PHP_EOL, $sql))) {
		return true;
	} else {
		return false;
	}
}
