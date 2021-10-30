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
 * This script sets statements of interest rates. Then load those interests to an array called
 * RatesArray, then displays each array element.
 *
 */

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<title>Interest Array</title>
	<meta http-equiv="content-type"
	      content="text/html; charset=utf-8"/>
</head>
<body>
<?php
// statements of interest rates
$InterestRate1 = .0725;
$InterestRate2 = .0750;
$InterestRate3 = .0775;
$InterestRate4 = .0800;
$InterestRate5 = .0825;
$InterestRate6 = .0850;
$InterestRate7 = .0875;

// declare an empty array
$RatesArray = array();
// get the range of integers from 1 to 7
$rates = range(1, 7);
// now load the interests to the array
foreach ($rates as $rate)
{
	$RatesArray["Rate $rate"] = (float) ${"InterestRate$rate"};
}
?>
<h2>Display each array element</h2>
<ul>
    <?php foreach($RatesArray as $key => $value) : ?>
        <li><?php echo $key; ?>: <?php echo number_format($value, 4); ?></li>
    <?php endforeach; ?>
</ul>
</body>
</html>
