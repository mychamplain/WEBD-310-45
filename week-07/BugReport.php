<?php
/**
 * @package    Server-Side Scripting - PHP
 *
 * @created    16th December 2021
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-310-45 <https://git.vdm.dev/Llewellyn/WEBD-310-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @week7
 * Create a Web page to be used for storing software development bug
 * reports in a MySQL database. Include fields such as product name
 * and version, type of hardware, operating system, frequency of occurrence,
 * and proposed solutions. Include links on the main page that
 * allow you to create a new bug report and update an existing bug report.
 *
 **/

/**
 * Constant that is checked in included files to prevent direct access.
 */
define('_WEBD', 1);

// lod the file that will load all I need loaded
if (file_exists(__DIR__ . '/define.php'))
{
	include_once __DIR__ . '/define.php';
}
// get all reports
$reports = getReports();
// get the products
$products = getProducts();
// get the versions
$versions = getVersions();
// get the os's
$oss = getOSs();
// get the hardware
$hardware = getHardware();
// check if we have a form submission ( with errors )
if (isset($reported) && $reported && isset($error) && count($error))
{
	$form_display = '';
	$table_display = ' style="display: none;"';
}
else
{
	$form_display = ' style="display: none;"';
	$table_display = '';
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<title>Bug Reports</title>
	<meta http-equiv="content-type"
	      content="text/html; charset=utf-8"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.11.3/b-2.1.1/b-html5-2.1.1/r-2.2.9/rr-1.2.8/sc-2.0.5/sb-1.3.0/sp-1.4.0/sl-1.3.4/datatables.min.css"/>
	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.6.0/dt-1.11.3/b-2.1.1/b-html5-2.1.1/r-2.2.9/rr-1.2.8/sc-2.0.5/sb-1.3.0/sp-1.4.0/sl-1.3.4/datatables.min.js"></script>
</head>
<body>
<?php if (isset($report_posted) && $report_posted): ?>
<h2 class="success-notice" style="color:green;">Report successfully saved!</h2>
<?php endif; ?>
<div id="make-bug-report-button">
	<button type="button" onclick="makeBugReport()">Submit a bug report</button>
	<br /><br />
</div>
<div id="make-bug-report-form"<?php echo $form_display; ?>>
	<h2>Bug Report</h2>
	<?php if (isset($products) && $products && isset($versions) && $versions &&
				isset($oss) && $oss && isset($hardware) && $hardware): ?>
	<form id="bug_report" action="BugReport.php" method="post">
		<input type="hidden" id="bug_id" name="bug_id" value="0">
		<div>
			<label for="bug_product">Choose a product:</label>
			<select name="bug_product" id="bug_product">
				<option value="">-- Select the product --</option>
				<?php $selected = getValue('bug_product'); ?>
				<?php foreach ($products as $id => $val): ?>
					<?php if ($selected == $id) : ?>
						<option value="<?php echo $id; ?>" selected="true"><?php echo $val; ?></option>
					<?php else: ?>
						<option value="<?php echo $id; ?>"><?php echo $val; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>&nbsp;<?php echo getError('bug_product'); ?>
		</div>
		<br/>
		<div>
			<label for="bug_version">Choose a version:</label>
			<select name="bug_version" id="bug_version">
				<option value="">-- Select the version --</option>
				<?php $selected = getValue('bug_version'); ?>
				<?php foreach ($versions as $id => $val): ?>
					<?php if ($selected == $id) : ?>
						<option value="<?php echo $id; ?>" selected=""true"><?php echo $val; ?></option>
					<?php else: ?>
						<option value="<?php echo $id; ?>"><?php echo $val; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>&nbsp;<?php echo getError('bug_version'); ?>
		</div>
		<br/>
		<div>
			<label for="bug_os">Choose OS:</label>
			<select name="bug_os" id="bug_os">
				<option value="">-- Select the os --</option>
				<?php $selected = getValue('bug_os'); ?>
				<?php foreach ($oss as $id => $val): ?>
					<?php if ($selected == $id) : ?>
						<option value="<?php echo $id; ?>" selected=""true"><?php echo $val; ?></option>
					<?php else: ?>
						<option value="<?php echo $id; ?>"><?php echo $val; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>&nbsp;<?php echo getError('bug_os'); ?>
		</div>
		<br/>
		<div>
			<label for="bug_hardware">Choose hardware:</label>
			<select name="bug_hardware" id="bug_hardware">
				<option value="">-- Select the hardware --</option>
				<?php $selected = getValue('bug_hardware'); ?>
				<?php foreach ($hardware as $id => $val): ?>
					<?php if ($selected == $id) : ?>
						<option value="<?php echo $id; ?>" selected=""true"><?php echo $val; ?></option>
					<?php else: ?>
						<option value="<?php echo $id; ?>"><?php echo $val; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>&nbsp;<?php echo getError('bug_hardware'); ?>
		</div>
		<br/>
		<div>
			<label for="bug_occurrence">Number Occurrences:</label>
			<input type="text" id="bug_occurrence" name="bug_occurrence"
			       placeholder="only numbers allowed"<?php echo setFormValue('bug_occurrence'); ?>/>&nbsp;
			<?php echo getError('bug_occurrence'); ?>
		</div>
		<br/>
		<div>
			<label for="bug_bug">Bug details:</label>
			<textarea rows="4" cols="50" name="bug_bug" id="bug_bug"
			          placeholder="add the bug details here..."><?php echo getValue('bug_bug'); ?></textarea>&nbsp;
			<?php echo getError('bug_bug'); ?>
		</div>
		<br/>
		<div>
			<label for="bug_solution">Solution:</label>
			<textarea rows="4" cols="50" name="bug_solution" id="bug_solution"
			          placeholder="add the bug solution here..."><?php echo getValue('bug_solution'); ?></textarea>&nbsp;
			<?php echo getError('bug_solution'); ?>
		</div>
		<br/>
		<div>
			<input type="reset" value="Cancel" onclick="cancelBugReport()"/>&nbsp;&nbsp;
			<input type="submit" name="Submit" value="Submit Report"/>
		</div>
	</form>
	<?php endif; ?>
</div>
<?php if (isset($reports) && count($reports) > 0) : ?>
<div id="existing-bug-reports"<?php echo $table_display; ?>>
	<table id="bug-reports" class="display" style="width:100%">
		<thead>
			<tr>
				<th colspan="4">Product</th>
				<th colspan="3">Bug Report</th>
			</tr>
			<tr>
				<th>Name</th>
				<th>Version</th>
				<th>OS</th>
				<th>Hardware</th>
				<th>Bug</th>
				<th>Occurrence</th>
				<th>Solution</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($reports as $report) : ?>
			<tr onclick="editBugReport(<?php echo $report['id']; ?>)">
				<td><?php echo $report['product']; ?></td>
				<td><?php echo $report['version']; ?></td>
				<td><?php echo $report['os']; ?></td>
				<td><?php echo $report['hardware']; ?></td>
				<td><?php echo $report['bug']; ?></td>
				<td><?php echo $report['occurrence']; ?></td>
				<td><?php echo $report['solution']; ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	// make the current bug reports available to the page
	let bugs = <?php echo json_encode($reports); ?>;
    // initialize the existing bug report table
    $(document).ready(function() {
        $('#bug-reports').DataTable( {
            select: {
                style: 'single'
            }
        });
    });
    // open the form to edit a bug report
    function editBugReport(id){
        $('#existing-bug-reports').hide();
        $('#make-bug-report-button').hide();
        $.each(bugs, function(index,object){
            if (object.id == id){
                setBugReportToForm(object);
            }
        });
        $('#make-bug-report-form').show();
    }
    // set the form to edit the report
	function setBugReportToForm(object) {
        $('#bug_id').val(object.id);
        $('#bug_product').val(object.product_id);
        $('#bug_version').val(object.version_id);
        $('#bug_os').val(object.os_id);
        $('#bug_hardware').val(object.hardware_id);
        $('#bug_occurrence').val(object.occurrence);
        $('#bug_bug').val(object.bug);
        $('#bug_solution').val(object.solution);
    }
</script>
<?php else: ?>
	<p>There are no bug reports at this time!</p>
<?php endif; ?>
<script type="text/javascript">
    // open the form to make a bug report
    function makeBugReport(){
        // clear the form
        clearForm();
        // hide the table
        $('#existing-bug-reports').hide();
        $('#make-bug-report-button').hide();
        // show the form
        $('#make-bug-report-form').show();
    }
    // cancel the option to make a bug report
    function cancelBugReport(){
        // clear the form
        clearForm();
        // hide the form
        $('.error-serverside').hide();
        $('#make-bug-report-form').hide();
        $('.success-notice').hide();
        // show the table
        $('#existing-bug-reports').show();
        $('#make-bug-report-button').show();
    }
    // clear the form
    function clearForm(){
        // clear out the form
        $('#bug_id').val(0);
        $('#bug_product').val('');
        $('#bug_version').val('');
        $('#bug_os').val('');
        $('#bug_hardware').val('');
        $('#bug_occurrence').val('');
        $('#bug_bug').val('');
        $('#bug_solution').val('');
    }
</script>
</body>
</html>
<?php
	# close our database connection
	if (isset($MY_DB_CONNECTION) && $MY_DB_CONNECTION instanceof mysqli)
	{
		mysqli_close($MY_DB_CONNECTION);
	}
?>
