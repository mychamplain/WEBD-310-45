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
 * In this script I have modified the nested if statement so it instead
 * uses a compound conditional expression. I used logical operators
 * such as || (or) and && (and) to execute a conditional or looping
 * statement based on multiple criteria.
 *
 */

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Gas Prices</title>
    <meta http-equiv="content-type"
          content="text/html; charset=utf-8"/>
</head>
<body>
<?php
$GasPrice = 2.57;
if ($GasPrice >= 2 && $GasPrice <= 3)
{
	echo "<p>Gas prices are between $2.00 and $3.00.</p>";
}
else
{
    echo "<p>Gas prices are not between $2.00 and $3.00</p>";
}
?>
</body>
</html>
