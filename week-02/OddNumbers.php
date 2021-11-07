<?php
/**
 * @package    Server-Side Scripting - PHP
 *
 * @created    7th November 2021
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-310-45 <https://git.vdm.dev/Llewellyn/WEBD-310-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @week2
 * In this script I wrote a while statement that displays all odd
 * numbers between 1 and 100 on the page.
 *
 */

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Odd Numbers</title>
    <meta http-equiv="content-type"
          content="text/html; charset=utf-8"/>
</head>
<body>
<h1>Odd Numbers between 1 and 100</h1>
<p>
    <?php
	// my first number
	$number       = 0;
	$breakCounter = 1;
	// while loop to print out all odd number between 1 and 100
	while ($number <= 100)
	{
		if ($number % 2 != 0)
		{
			// every 10 we add a break
			if ($breakCounter == 10)
			{
				echo "$number<br /><br />";
				$breakCounter = 0;
			}
			// don't add a space to our last number
            elseif ($number != 99)
			{
				if ($number < 10)
				{
					echo "$number&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				}
				else
				{
					echo "$number&nbsp;&nbsp;&nbsp;";
				}
			}
			else
			{
				echo "$number";
			}
			$breakCounter++;
		}
		$number++;
	}
	?>
</p>
</body>
</html>
