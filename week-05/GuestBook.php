<?php
/**
 * @package    Server-Side Scripting - PHP
 *
 * @created    3rd December 2021
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-310-45 <https://git.vdm.dev/Llewellyn/WEBD-310-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @week5
 * Use the techniques you learned in this chapter to create a Guest Book
 * script that stores visitor names and e-mail addresses in a text file.
 * Include functionality that allows users to view the guest book and
 * prevents the same user name from being entered twice. Also, include
 * code that sorts the guest book by name and deletes duplicate entries.
 *
 */

// our little global values
$message = '';
$error = array();
$valid = true;
$signed = true;
// form keys
$form = array('name' => 'string', 'email' => 'email');
// form values
$entry = array();
// previous guest book values
$opened = false;
$questBook = array();

// function to validate user input (basic)
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
		$valid = $_POST[$key] === 'Sign';
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
		elseif ($key === 'name' && !validName($value))
		{
			$message = "<br /><small><b>$value</b> has already signed our guest book!</small>";
			$valid   = false;
		}
		// remove all white space
		$value = trim($value);
	}
	// check that this is an int
	elseif ($type === 'email')
	{
		$value = $_POST[$key];
		$valid = true;
		// check the age to be realistic
		if (!validEmail($value))
		{
			$message = "<br /><small>Invalid email was entered.</small>";
			$valid   = false;
		}
	}
	// we set the value if valid
	if ($valid && isset($form[$key]))
	{
		$entry[$key] = $_POST[$key];
	}

	return $valid;
}

# check that we have a valid email address
function validEmail($email)
{
	return filter_var($email, FILTER_VALIDATE_EMAIL)
		&& preg_match('/@.+\./', $email);
}

# check if this name was already used before
function validName($name)
{
	// the guestbook
	global $questBook;
	// open and validate
	if (openGuestBook() && !isset($questBook[$name]))
	{
		// I could have used the
		// in_array (https://www.php.net/manual/en/function.in-array.php)
		// but checking for a key in an array is much faster
		return true;
	}

	return false;
}

// get a valid value
function getValue($key, $default = '')
{
	global $entry;
	global $signed;
	// check if the value was set
	if ($signed && isset($entry[$key]))
	{
		return $entry[$key];
	}

	return $default;
}

// to retain valid values
function setFormValue($key)
{
	global $entry;
	global $signed;
	// check if the value was set
	if ($signed && isset($entry[$key]))
	{
		return " value=\"$entry[$key]\"";
	}

	return '';
}

// to display an error message
function getError($key)
{
	global $error;
	global $signed;
	// check if the value was set
	if ($signed && isset($error[$key]))
	{
		return "<br /><span id=\"$key-error-serverside\" style=\"color: red;\">$error[$key]</span>";
	}

	return '';
}

// function open the guest book
function openGuestBook()
{
	// for errors
	global $error;
	// the guestbook
	global $questBook;
	// the open switch
	global $opened;
	// check if we already open this
	if ($opened)
	{
		return true;
	}
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
	$file_path = $path . "/guest-book.json";
	// check the file does not exist
	if (!file_exists($file_path))
	{
		// creat and place the values as the first line
		if (file_put_contents($file_path, "{}"))
		{
			return true;
		}
		$error[] = 'We could not create/write to the guest book, check your file permissions.';

		return false;
	}
	// or just append the values
	elseif (($content = @file_get_contents($file_path)) !== false)
	{
		///////////////////////////////////////////////////////////////////////////////
		// I am storing the data as json, since this is much faster to access than
		// loading the file each time, line by line.
		// yet I know how to do the line by line option
		// so here is that code in case:
		////////////////////////////////////////////////////////////////////////////////
		//  $array = explode("\n", $content);
		//  $questBook = array();
		//  foreach ($array as $line)
		//  {
		//      $valueArray = explode(';', $line);
		//      $questBook[$valueArray[0]] = $valueArray[1];
		//  }
		///////////////////////////////////////////////////////////////////////////////
		// set the guest book values
		$questBook = json_decode($content, true);
		// only open this once per page load
		$opened = true;

		return true;
	}
	$error[] = 'We could not write to the guest book file, check your file permissions.';

	return false;
}

// function to save the entry to a file
function saveGuest()
{
	// get the entries
	global $entry;
	// the guestbook
	global $questBook;
	// make sure we have our guest book open
	if (openGuestBook())
	{
		// the file where we store the registrations
		$file_path = getcwd() . '/reg/guest-book.json';
		// add the entry to the guest book (avoiding any duplicates)
		$questBook[$entry['name']] = $entry['email'];
		///////////////////////////////////////////////////////////////////////////////
		// I am storing the data as json, since this is much faster to access than
		// loading the file each time, line by line.
		// yet I know how to do the line by line option
		// so here is that code in case:
		////////////////////////////////////////////////////////////////////////////////
		//  if (file_put_contents($file_path, implode(";", $entry) . "\n", FILE_APPEND))
		///////////////////////////////////////////////////////////////////////////////
		// creat and place the values as the first line
		if (file_put_contents($file_path, json_encode($questBook, JSON_PRETTY_PRINT)))
		{
			return true;
		}
	}

	return false;
}

// we get the data from the post if there is any
if (!validatePostUserInput('Submit', 'form'))
{
	// registration was not yet made, or a serious issue. So we try again.
	$signed = false;
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
	<title>Guest Book</title>
	<meta http-equiv="content-type"
	      content="text/html; charset=utf-8"/>
</head>
<body>
<?php if (!openGuestBook() && count($error) > 0): ?>
	<h3 style="color: red">Server failure!</h3>
	<div style="color: red;">
		<ul style="list-style-type: none;">
			<li><?php echo implode('</li><li>', $error) ?></li>
		</ul>
	</div>
<?php elseif ($signed && $valid) : ?>
	<?php if (saveGuest()): ?>
		<h2 style=" color: green"><?php echo getValue('name', 'The Guest'); ?> has successfully signed our quest book.</h2>
		<hr/>
			<div style="color: green">With these details:
				<?php array_walk($entry, function (&$value, $key) {
					$value = "<i>" . ucfirst($key) . ":</i> <b>$value</b>";
				}); ?>
				<ul style="list-style-type: none;">
					<li><?php echo implode('</li><li>', $entry) ?></li>
				</ul>
			</div>
		<hr/>
		<p><a type="button" href="GuestBook.php">
				<button type="button">Click here to sign our quest book again.</button>
			</a></p>
	<?php else: ?>
		<h2 style="color: red">There was an server error</h2>
		<?php if (count($error) > 0) : ?>
		<div style="color: red;">
			<ul style="list-style-type: none;">
				<li><?php echo implode('</li><li>', $error) ?></li>
			</ul>
		</div>
		<?php endif; ?>
		<p style="color: red">The signing our quest book could not be captured, please try again later. Should this
			issue continue
			then inform the our organizers.</p>
		<p><a type="button" href="GuestBook.php">
				<button type="button">Click here to try again.</button>
			</a></p>
	<?php endif; ?>
<?php else: ?>
	<h2>Sign our Guest Book</h2>
<?php if (count($error) > 0) : ?>
	<h3 style="color: red">Submission failed, please try again</h3>
<?php endif; ?>
	<form id="registration" name="registration" action="GuestBook.php" method="post">
		<div>Your Name:&nbsp;&nbsp;
			<input type="text" name="name"<?php echo setFormValue('name'); ?>/>
			<?php echo getError('name'); ?>
		</div>
		<br/>
		<div>Your email:&nbsp;&nbsp;
			<input type="text" name="email"<?php echo setFormValue('email'); ?>/>&nbsp;
			<span id="mail-error" style="color: red; visibility:hidden;">only valid emails allowed</span>
			<?php echo getError('email'); ?>
		</div>
		<br/>
		<div>
			<input type="reset" value="Clear Form" onclick="resetForm();"/>&nbsp;&nbsp;
			<input type="submit" name="Submit" value="Sign"/></div>
	</form>
	<script type="text/javascript">
        // reset or hide errors
        function resetForm() {
            // if server side error are set hide them as well
            let name_error = document.getElementById("name-error-serverside");
            if (name_error) {
                name_error.style.visibility = "hidden";
            }
            let age_error = document.getElementById("email-error-serverside");
            if (age_error) {
                age_error.style.visibility = "hidden";
            }
        }
	</script>
<?php endif; ?>
<?php if (openGuestBook() && count($questBook) > 0): ?>
	<hr/>
	<h1>Our Quest Book</h1>
	<?php
	// sort the guest book by name
	ksort($questBook);
	// format the output
	array_walk($questBook, function (&$value, $key) {
		$value = "<i>" . ucfirst($key) . ":</i> <b>$value</b>";
	}); ?>
	<ul style="list-style-type: none;">
		<li><?php echo implode('</li><li>', $questBook) ?></li>
	</ul>
<?php endif; ?>
</body>
</html>
