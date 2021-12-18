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

// our little global values
$message = '';
$error = array();
// form switches
$valid = false;
$reported = false;
$report_posted = false;
// form keys
$form = array(
	'bug_id' => array('filter' => 'int', 'key' => 'id', 'required' => false, 'table' => 'debug_report'),
	'bug_product' => array('filter' => 'int', 'key' => 'product', 'required' => true, 'table' => 'debug_product'),
	'bug_version' => array('filter' => 'int', 'key' => 'version', 'required' => true, 'table' => 'debug_product_version'),
	'bug_os' => array('filter' => 'int', 'key' => 'os', 'required' => true, 'table' => 'debug_os'),
	'bug_hardware' => array('filter' => 'int', 'key' => 'hardware', 'required' => true, 'table' => 'debug_hardware'),
	'bug_bug' => array('filter' => 'string', 'key' => 'bug', 'required' => true),
	'bug_occurrence' => array('filter' => 'int', 'key' => 'occurrence', 'required' => true),
	'bug_solution' => array('filter' => 'string', 'key' => 'solution', 'required' => true)
);
// form values
$entry = array();
// validate post input
function validatePostUserInput(string $key, $details): bool
{
	// bring our global values
	global $message;
	global $entry;
	global $form;
	// we have a local per/field validation switch
	$valid = false;
	// we clear the message each time
	$message = '';
	// first we check if the key exist
	if (!isset($_POST[$key]) && (!isset($details['required']) || $details['required']))
	{
		$valid = false;
	}
	// check if the string that its Register :)
	elseif ($details === 'form')
	{
		$valid = $_POST[$key] === 'Submit Report';
	}
	// check if the string (only name)
	elseif ($details['filter'] === 'string')
	{
		$value = preg_replace("/[^A-Za-z\. ]/", '', $_POST[$key]);
		$valid = true;
		// check name
		if (strlen(trim($value)) == 0 && $details['required'])
		{
			$message = "<br /><small>The <b>$key</b> field is required and can not be empty!</small>";
			$valid   = false;
		}
		// remove all white space
		$value = trim($value);
	}
	// check that this is an int
	elseif ($details['filter'] === 'int')
	{
		$value = $_POST[$key];
		$valid = true;
		// check the age to be realistic
		if (!is_numeric($value) && isset($details['required']) && $details['required'])
		{
			$message = "<br /><small>Invalid $key was entered, integer required.</small>";
			$valid   = false;
		}
		// make sure ID exist if table is set
		elseif(is_numeric($value) && $value > 0 && isset($details['table']) && !idExist($value, $details['table']))
		{
			$message = "<br /><small>Invalid $key was entered, ID does not exist.</small>";
			$valid   = false;
		}
		$value = (int) $value;
	}
	// we set the value if valid
	if ($valid && isset($form[$key]))
	{
		$entry[$key] = $_POST[$key];
	}

	return $valid;
}

// get a valid value
function getValue($key, $default = ''): string
{
	global $entry;
	global $reported;
	// check if the value was set
	if ($reported && isset($entry[$key]))
	{
		return $entry[$key];
	}

	return $default;
}

// to retain valid values
function setFormValue($key, $as_html = true): string
{
	global $entry;
	global $reported;
	// check if the value was set
	if ($reported && isset($entry[$key]))
	{
		return $as_html ? " value=\"$entry[$key]\"" : $entry[$key];
	}

	return '';
}

// to display an error message
function getError($key): string
{
	global $error;
	global $reported;
	// check if the value was set
	if ($reported && isset($error[$key]))
	{
		return "<br /><span class=\"error-serverside\" style=\"color: red;\">$error[$key]</span>";
	}

	return '';
}

// we get the data from the post if there is any
if (validatePostUserInput('Submit', 'form'))
{
	// reported
	$valid = true;
	$reported = true;
	// check if valid
	foreach ($form as $field => $validate)
	{
		if (!validatePostUserInput($field, $validate))
		{
			// show an error as we do not have a hours
			$error[$field] = "Invalid value detected!$message";
			$valid         = false;
		}

	}
}

// if we have a valid report save to database
if ($reported && $valid)
{
	// get the DB to escape strings
	$db = getDB();
	// build data bucket
	$data = array();
	// convert to database input array
	foreach ($form as $field => $validate)
	{
		if (($val = getValue($field, false)) != false)
		{
			if (is_numeric($val))
			{
				$data[$validate['key']] = (int) $val;
			}
			else
			{
				$data[$validate['key']] = "'" . mysqli_real_escape_string($db, $val) . "'";
			}
		}
	}
	// now we save the data
	if (saveReport($data))
	{
		// reported
		$report_posted = true;
		$valid    = false;
		$reported = false;
	}
}
