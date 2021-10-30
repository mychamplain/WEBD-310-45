<?php
/**
 * @package    Server-Side Scripting - PHP
 *
 * @created    6th October 2021
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-310-45 <https://git.vdm.dev/Llewellyn/WEBD-310-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @week1
 * This script assign the English days of the week to an array named Days, then displays those.
 * Next it updates that same array of days with the French days of the week, then displays those.
 *
 */

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<title>Days of the Week</title>
	<meta http-equiv="content-type"
	      content="text/html; charset=utf-8"/>
</head>
<body>
<?php
// Assign the English days of the week to an array named Days
$Days = [
	'Sunday',
	'Monday',
	'Tuesday',
	'Wednesday',
	'Thursday',
	'Friday',
	'Saturday'
];

// output statement for English
echo "The days of the week in English are: " . implode(", ", $Days);
echo "<br />";
// Assign the French days of the week to the same array named Days
$Days = [
	'Dimanche',
	'Lundi',
	'Mardi',
	'Mercredi',
	'Jeudi',
	'Vendredi',
	'Samedi'
];
// output statement for French
echo "The days of the week in French are: " . implode(", ", $Days);
?>
</body>
</html>
