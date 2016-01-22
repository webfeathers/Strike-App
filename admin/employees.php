<?php require_once('../Connections/strike.php'); ?>
<?php require_once('../includes/security_check.php'); ?>

<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editItem")) {
  $updateSQL = sprintf("UPDATE employee SET emp_first_name=%s, emp_last_name=%s, username=%s, password=%s, emp_start_date=%s WHERE id=%s",
                       GetSQLValueString($_POST['emp_first_name'], "text"),
                       GetSQLValueString($_POST['emp_last_name'], "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['emp_start_date'], "date"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_strike, $strike);
  $Result1 = mysql_query($updateSQL, $strike) or die(mysql_error());

  $updateGoTo = "/admin/employees.php?edited=true";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addEmployee")) {
  $insertSQL = sprintf("INSERT INTO employee (emp_first_name, emp_last_name, password, emp_start_date) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['emp_first_name'], "text"),
                       GetSQLValueString($_POST['emp_last_name'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['emp_start_date'], "date"));

  mysql_select_db($database_strike, $strike);
  $Result1 = mysql_query($insertSQL, $strike) or die(mysql_error());

  $insertGoTo = "?added=true";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_strike, $strike);
$query_employees = "SELECT * FROM employee";
$employees = mysql_query($query_employees, $strike) or die(mysql_error());
$row_employees = mysql_fetch_assoc($employees);
$totalRows_employees = mysql_num_rows($employees);

$colname_employee = "-1";
if (isset($_POST['employee_id'])) {
  $colname_employee = $_POST['employee_id'];
}
mysql_select_db($database_strike, $strike);
$query_employee = sprintf("SELECT * FROM employee WHERE id = %s", GetSQLValueString($colname_employee, "int"));
$employee = mysql_query($query_employee, $strike) or die(mysql_error());
$row_employee = mysql_fetch_assoc($employee);
$totalRows_employee = mysql_num_rows($employee);
?>
<?php
	require('../includes/pagetop.php');
?>
<?php
	$selectedHeaderTab = "admin";
	include('../includes/header.php');
?>
<div class="container">
	<h1>Add/Edit Employees</h1>
	<?php if(isset($_GET['edited'])){ ?>
	<div class="alert alert-success">
		<h2>Done</h2>
		<p>The employee has been edited</p>
	</div>
	<?php } ?>
	<?php if(isset($_GET['added'])){ ?>
	<div class="alert alert-success">
		<h2>Done</h2>
		<p>The employee has been added</p>
	</div>
	<?php } ?>
	<form method="post" action="/admin/employees.php" id="selectAndGoTarget">
		<fieldset>
			<legend>Select an employee to edit</legend>
			<div class="form-group">
				<select name="employee_id" class="selectAndGoTrigger form-control">
					<option class="form-control">Select an employee to edit</option>
					<?php
								do {  
								?>
					<option value="<?php echo $row_employees['id']?>"<?php if (!(strcmp($row_employees['id'], $row_employee['id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_employees['emp_first_name'] . ' ' .  $row_employees['emp_last_name']?></option>
					<?php
								} while ($row_employees = mysql_fetch_assoc($employees));
								  $rows = mysql_num_rows($employees);
								  if($rows > 0) {
									  mysql_data_seek($employees, 0);
									  $row_employees = mysql_fetch_assoc($employees);
								  }
	?>
				</select>
			</div>
		</fieldset>
	</form>
	<a class="btn btn-default btn-lg btn-block" href="/admin/employees.php">Add a new employee</a>
	<?php if(isset($row_employee['id'])){ //if we have a customer to edit ?>
	<h2>Edit </h2>
	<form action="<?php echo $editFormAction; ?>" method="POST" name="editItem" id="editItem">
		<input type="hidden" name="id" value="<?php echo $row_employee['id']; ?>" />
		<fieldset>
			<legend>Employee name</legend>
			<div class="form-group col-sm-6">
				<label for="emp_first_name">First Name</label>
				<input class="form-control" type="text" name="emp_first_name" value="<?php echo $row_employee['emp_first_name']; ?>" />
			</div>
			<div class="form-group col-sm-6">
				<label for="emp_last_name">Last Name</label>
				<input class="form-control" type="text" name="emp_last_name" value="<?php echo $row_employee['emp_last_name']; ?>" />
			</div>
		</fieldset>
		<fieldset>
			<legend>Other</legend>
			<div class="form-group col-sm-6">
				<label for="password">Username</label>
				<input class="form-control" type="text" name="username" value="<?php echo $row_employee['username']; ?>" />
			</div>
			<div class="form-group col-sm-6">
				<label for="password">Password</label>
				<input class="form-control" type="text" name="password" value="<?php echo $row_employee['password']; ?>" />
			</div>
			<div class="form-group col-sm-6">
				<label for="emp_start_date">Start Date</label>
				<input class="form-control" type="text" name="emp_start_date" value="<?php echo $row_employee['emp_start_date']; ?>" />
			</div>
		<div class="form-group col-sm-6">
			<button class="btn btn-lg btn-primary">Edit Employee</button>
		<input type="hidden" name="MM_update" value="editItem">
		</div>
		</fieldset>
	</form>
	<?php 
	} //end if we have an employee to edit
	else {// otherwise, present the 'add employee' form 
	?>
	<h2>Add a new employee</h2>
	<form action="<?php echo $editFormAction; ?>" method="POST" name="addEmployee" id="addEmployee">
		<fieldset>
			<legend>Employee name</legend>
			<div class="form-group col-sm-6">
					<label for="emp_first_name">First Name</label>
					<input class="form-control" type="text" name="emp_first_name" value="" />
				</div>
			<div class="form-group col-sm-6">
					<label for="emp_last_name">Last Name</label>
					<input class="form-control" type="text" name="emp_last_name" value="" />
				</div>
	
		</fieldset>
		<fieldset>
			<legend>Other</legend>
			<div class="form-group col-sm-6">
				<label for="password">Username</label>
				<input class="form-control" type="text" name="username" value="<?php echo $row_employee['username']; ?>" />
			</div>
			<div class="form-group col-sm-6">
					<label for="password">Password</label>
					<input class="form-control" type="text" name="password" value="" />
				</div>
			<div class="form-group col-sm-6">
					<label for="emp_start_date">Start Date</label>
					<input class="form-control" type="text" name="emp_start_date" value="<?php echo date("Y-m-j"); ?>" placeholder="<?php echo date("Y-m-j"); ?>" />
				</div>
				
			<div class="form-group col-sm-6">
				<button class="btn btn-lg btn-primary">Add employee</button>
		<input type="hidden" name="MM_insert" value="addEmployee">
			
			</div>
			</fieldset>
	</form>
	<?php } //end add new employee form ?>
	
	<?php include('../includes/footer.php'); ?>
</div>


<?php include('../includes/pageClose.php'); ?>

</body>
</html>
<?php
mysql_free_result($employees);

mysql_free_result($employee);
?>
