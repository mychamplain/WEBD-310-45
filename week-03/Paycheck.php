<?php
/**
 * @package    Server-Side Scripting - PHP
 *
 * @created    13th November 2021
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-310-45 <https://git.vdm.dev/Llewellyn/WEBD-310-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @week3
 * Create a two-part form that calculates an employee’s weekly gross
 * salary, based on the number of hours worked and an hourly wage
 * that you choose. Use an HTML document named Paycheck.html
 * as a Web form with two text boxes—one for the number of hours
 * worked and one for the hourly wage. Use a PHP document named
 * Paycheck.php as the form handler. Compute any hours over 40 as
 * time-and-a-half. Be sure to verify and validate the submitted form
 * data and provide appropriate error messages for invalid values.
 *
 */

// our little global values
$message = '';
$wage = 0;
$hours = 0;
$valid = true;

// function to validate user input (basic)
// for more advance checkout https://github.com/joomla-framework/filter/blob/2.0-dev/src/InputFilter.php
function validatePostUserInput($key, $type = 'int')
{
	// bring our global values
	global $message;
	global $wage;
	global $hours;
	// we have a local per/field validation switch
	$valid = false;
	// we clear the message each time
	$message = '';
	// first we check if the key exist
	if (!isset($_POST[$key]))
	{
		$valid = false;
	}
	// check if the string that its Calculate :)
    elseif ($type === 'string')
	{
		$valid = $_POST[$key] === 'Calculate';
	}
	// check that this is an int
    elseif ($type === 'int' && is_numeric($_POST[$key]) && is_int($_POST[$key] + 0))
	{
		$value = $_POST[$key];
		$valid = true;
		// check wage
		if ($key === 'wage' && $value > 1000)
		{
			$message = "<br /><small>Earning more than \$1000 dollars per/hour is ludicrous for a doorkeeper. You tried to claim <b>$value</b> per/hour!</small>";
			$valid   = false;
		}
		// check hours not to be more than 48 allowed hours
		if ($key === 'hours' && $value > 48)
		{
			$message = "<br /><small>You do not have permission to book more than 48 hours per/week. You tried to book <b>$value</b> hours!</small>";
			$valid   = false;
		}
	}
	else
	{
		$message = "<br /><small>Only integer values allowed as <b>$key</b>!</small>";
		$valid   = false;
	}
	// we set the value if valid
	if ($valid)
	{
		${$key} = $_POST[$key];
	}

	return $valid;
}

// function to calculate the pay check
function calculatePayCheck(&$overtime, &$overpay, &$standard_salary, &$gross_salary)
{
	// bring our global values
	global $wage;
	global $hours;
	// we need a local hour value
	$hour = $hours;
	// remove any time over 40 hours
	if ($hours > 40)
	{
		$overtime = $hours - 40;
		// calculate the extra pay
		$overpay = round(($wage * 1.5) * $overtime);
		// we only have 40 hours left
		$hour = 40;
	}
	// calculate the standard pay
	$standard_salary = round($wage * $hour);
	// work out the total
	$gross_salary = round($overpay + $standard_salary);
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Paycheck</title>
    <meta http-equiv="content-type"
          content="text/html; charset=utf-8"/>
</head>
<body>
<h2 style="text-align:center">Paycheck</h2>
<?php

// we get the data from the post if there is any
if (!validatePostUserInput('Submit', 'string'))
{
	// show an error as we do not have a post
	echo "<h3 style=\"color: red\">No valid form detected!</h3>";
	$valid = false;
}
else
{
	if (!validatePostUserInput('hours'))
	{
		// show an error as we do not have a hours
		echo "<p style=\"color: red\">No valid <b>hours</b> value detected!$message</p>";
		$valid = false;
	}
	if (!validatePostUserInput('wage'))
	{
		// show an error as we do not have correct wage
		echo "<p style=\"color: red\">No valid <b>wage</b> value detected!$message</p>";
		$valid = false;
	}
}

// we only do calculation if we have a valid post
if ($valid)
{
	$overtime        = 0;
	$overpay         = 0;
	$standard_salary = 0;
	$gross_salary    = 0;
	// calculate paycheck
	calculatePayCheck($overtime, $overpay, $standard_salary, $gross_salary);
	// display the result
	echo "<ul style='list-style-type: none;'>";
	echo "<li>Wage = \$$wage per/hour</li>";
	echo "<li>Hours = $hours per/week</li>";
	// if we have overtime
	if ($overtime == 1)
	{
		echo "<li>Overtime = \$$overpay USD for ($overtime) hour/per/week</li>";
	}
    elseif ($overtime > 1)
	{
		echo "<li>Overtime = \$$overpay USD for ($overtime) hours/per/week</li>";
	}
	// show the break-down
	if ($overtime)
	{
		echo "<li>Normal hours = \$$standard_salary USD per/week</li>";
		echo "<li>Gross Salary = \$$gross_salary USD per/week</li>";
	}
	else
	{
		echo "<li>Gross Salary = \$$gross_salary USD per/week</li>";
	}
	echo "</ul>";
}
?>
<br/>
<p style="text-align:center"><a type="button" href="Paycheck.html">
        <button type="button">Calculate Paycheck</button>
    </a></p>
</body>
</html>