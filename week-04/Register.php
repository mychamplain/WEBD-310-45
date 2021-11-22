<?php
/**
 * @package    Server-Side Scripting - PHP
 *
 * @created    20th November 2021
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-310-45 <https://git.vdm.dev/Llewellyn/WEBD-310-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @week4
 * Create a document with a form that registers bowlers for a bowling
 * tournament. Use a single text ﬁ le that saves information for each
 * bowler on a separate line. Include the bowler’s name, age, and aver-
 * age, separated by commas. Ensure that the Projects directory has read
 * and write permissions for everyone.
 *
 */

// our little global values
$message = '';
$error = array();
$valid = true;
$registered = true;
// form keys
$form = array('name' => 'string', 'age' => 'int', 'average' => 'int');
// form values
$entry = array();

// function to validate user input (basic)
// for more advance checkout https://github.com/joomla-framework/filter/blob/2.0-dev/src/InputFilter.php
function validatePostUserInput($key, $type = 'int')
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
	if (!isset($_POST[$key]))
	{
		$valid = false;
	}
	// check if the string that its Register :)
	elseif ($type === 'form')
	{
		$valid = $_POST[$key] === 'Register';
	}
	// check if the string (only name)
	elseif ($type === 'string')
	{
		$value = preg_replace("/[^A-Za-z\. ]/", '', $_POST[$key]);
		$valid = true;
		// check name
		if ($value !== $_POST[$key])
		{
			$message = "<br /><small>Please only use alphabetical characters for the <b>$key</b> field!</small>";
			$valid   = false;
		}
		elseif (strlen(trim($value)) == 0)
		{
			$message = "<br /><small>The <b>$key</b> field is required and can not be empty!</small>";
			$valid   = false;
		}
		// remove all white space
		$value = trim($value);
	}
	// check that this is an int
	elseif ($type === 'int' && is_numeric($_POST[$key]) && is_int($_POST[$key] + 0))
	{
		$value = (int) $_POST[$key];
		$valid = true;
		// check the age to be realistic
		if ($key === 'age' && ($value > 120 || $value < 10))
		{
			if ($value > 120)
			{
				$message = "<br /><small>Only below 120 years can register to this tournament.</small>";
			}
			else
			{
				$message = "<br /><small>Only 10 years and above can register to this tournament.</small>";
			}
			$valid = false;
		}
		// check the bowling average to be reasonable
		// https://www.quora.com/What-is-a-good-bowling-average-for-an-amateur-Bowling-on-a-lane-not-bowling-in-cricket
		// Lets set the range wide to allow beginners and professionals
		if ($key === 'average' && ($value > 300 || $value < 60))
		{
			$message = "<br /><small>Please enter a reasonable average!</small>";
			$valid   = false;
		}
	}
	else
	{
		$message = "<br /><small>Only integer values allowed as <b>$key</b>!</small>";
		$valid   = false;
	}
	// we set the value if valid
	if ($valid && isset($form[$key]))
	{
		$entry[$key] = $_POST[$key];
	}

	return $valid;
}

// get a valid value
function getValue($key, $default = '')
{
	global $entry;
	global $registered;
	// check if the value was set
	if ($registered && isset($entry[$key]))
	{
		return $entry[$key];
	}

	return $default;
}

// to retain valid values
function setFormValue($key)
{
	global $entry;
	global $registered;
	// check if the value was set
	if ($registered && isset($entry[$key]))
	{
		return " value=\"$entry[$key]\"";
	}

	return '';
}

// to display an error message
function getError($key)
{
	global $error;
	global $registered;
	// check if the value was set
	if ($registered && isset($error[$key]))
	{
		return "<br /><span id=\"$key-error-serverside\" style=\"color: red;\">$error[$key]</span>";
	}

	return '';
}

// function to save the entry to a file
function saveToFile()
{
	// for errors
	global $error;
	// get the entries
	global $entry;
	// the path to our project registration directory
	$path = getcwd() . '/reg';
	// check if the path exist
	if (!file_exists($path))
	{
		// We create the path so everyone has access to this project registration directory
		if (!mkdir($path, 0777))
		{
			$error[] = 'We could not create the project folder, check your folder permissions.';

			return false;
		}
	}
	// the file where we store the registrations
	$file_path = $path . "/bowlers.txt";
	// check the file does not exist
	if (!file_exists($file_path))
	{
		// creat and place the values as the first line
		if (file_put_contents($file_path, implode(",", $entry) . "\n"))
		{
			return true;
		}
		$error[] = 'We could not create/write to the registration file, check your file permissions.';

		return false;
	}
	// or just append the values
	elseif (file_put_contents($file_path, implode(",", $entry) . "\n", FILE_APPEND))
	{
		return true;
	}
	$error[] = 'We could not write to the registration file, check your file permissions.';

	return false;
}

// we get the data from the post if there is any
if (!validatePostUserInput('Submit', 'form'))
{
	// registration was not yet made, or a serious issue. So we try again.
	$registered = false;
}
else
{
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

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<title>Registers Bowlers</title>
	<meta http-equiv="content-type"
	      content="text/html; charset=utf-8"/>
</head>
<body>
<?php if ($registered && $valid) : ?>
	<?php if (saveToFile()): ?>
		<h2 style="text-align:center; color: green"><?php echo getValue('name', 'The Bowler'); ?> was Registered Successfully to the
			Bowling Tournament.</h2>
		<hr/>
		<div style="text-align:center; color: green">With these details:
			<?php array_walk($entry, function (&$value, $key) {
				$value = "<i>" . ucfirst($key) . ":</i> <b>$value</b>";
			}); ?>
			<ul style="list-style-type: none;">
				<li><?php echo implode('</li><li>', $entry) ?></li>
			</ul>
		</div>
		<hr/>
		<p style="text-align:center"><a type="button" href="Register.php">
			<button type="button">Click here to register another bowler</button>
		</a></p>
	<?php else: ?>
		<h2 style="text-align:center; color: red">There was an server error</h2>
		<?php if (count($error) > 0) : ?>
			<div style="text-align:center; color: red;">
				<ul style="list-style-type: none;">
					<li><?php echo implode('</li><li>', $error) ?></li>
				</ul>
			</div>
		<?php endif; ?>
		<p style="text-align:center; color: red">The registration could not be captured, please try again later. Should this
			issue continue
			then inform the Bowling Tournament organizers.</p>
		<p style="text-align:center"><a type="button" href="Register.php">
				<button type="button">Click here to try again.</button>
			</a></p>
	<?php endif; ?>
<?php else: ?>
	<h2 style="text-align:center">Register for Bowling Tournament</h2>
	<?php if (count($error) > 0) : ?>
		<h3 style="color: red">Submission failed, please try again</h3>
	<?php endif; ?>
	<form id="registration" name="registration" action="Register.php" method="post" onSubmit="return checkForm();">
		<div>Name of Bowler:&nbsp;&nbsp;
			<input type="text" name="name"<?php echo setFormValue('name'); ?>/>
			<?php echo getError('name'); ?>
		</div>
		<br/>
		<div>Age of Bowler:&nbsp;&nbsp;
			<input type="text" name="age"<?php echo setFormValue('age'); ?>/>&nbsp;
			<span id="age-error" style="color: red; visibility:hidden;">only integer values allowed</span>
			<?php echo getError('age'); ?>
		</div>
		<br/>
		<div>Bowling Average:&nbsp;&nbsp;
			<input type="text" name="average"<?php echo setFormValue('average'); ?>/>&nbsp;
			<span id="average-error" style="color: red; visibility:hidden;">only integer values allowed</span>
			<?php echo getError('average'); ?>
		</div>
		<br/>
		<div>
			<input type="reset" value="Clear Form" onclick="resetForm();"/>&nbsp;&nbsp;
			<input type="submit" name="Submit" value="Register"/></div>
	</form>
	<script type="text/javascript">
		// reset or hide errors
		function resetForm() {
		    document.getElementById('age-error').style.visibility = "hidden";
		    document.getElementById('average-error').style.visibility = "hidden";
		    // if server side error are set hide them as well
		    let name_error = document.getElementById("name-error-serverside");
		    if (name_error) {
		        name_error.style.visibility = "hidden";
		    }
		    let age_error = document.getElementById("age-error-serverside");
		    if (age_error) {
		        age_error.style.visibility = "hidden";
		    }
		    let average_error = document.getElementById("average-error-serverside");
		    if (average_error) {
		        average_error.style.visibility = "hidden";
		    }
		}

		// validate data (basic)
		function checkForm() {
		    // convert the form to an object
		    let formData = new FormData(document.getElementById("registration"));
		    // post the form
		    let allowed = true;
		    // we just validate int here
		    if (!isInt(formData.get("age"))) {
		        document.getElementById('age-error').style.visibility = "visible";
		        // show the wrong value in console
		        console.log(formData.get("age"));
		        // stop the posting
		        allowed = false;
		    }
		    if (!isInt(formData.get("average"))) {
		        document.getElementById('average-error').style.visibility = "visible";
		        // show the wrong value in console
		        console.log(formData.get("average"));
		        // stop the posting
		        allowed = false;
		    }
		    // stop the posting if not valid
		    return allowed;
		    // to test server side validation
		    // return true;
		}

		// Thanks to https://stackoverflow.com/a/14794066/1429677
		function isInt(value) {
		    return !isNaN(value) && (function (x) {
		        return (x | 0) === x;
		    })(parseFloat(value))
		}
	</script>
<?php endif; ?>
</body>
</html>