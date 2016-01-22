<?php require_once('Connections/strike.php'); ?>
<?php require_once('includes/security_check.php'); ?>
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "addEditCustomer")) {
  $updateSQL = sprintf("UPDATE customer SET business_name=%s, contact_first_name=%s, contact_last_name=%s, physical_address_number=%s, physical_address_street=%s, physical_address_city=%s, physical_address_state=%s, physical_address_zip=%s, physical_address_latlong=%s, billing_address_number=%s, billing_address_street=%s, billing_address_city=%s, billing_address_state=%s, billing_address_zip=%s, billing_address_latlong=%s, email=%s, cust_relation_emp=%s, distributor_rep=%s, account_type=%s, notes=%s, phone=%s, date_last_edited=%s WHERE customer_id=%s",
                       GetSQLValueString($_POST['business_name'], "text"),
                       GetSQLValueString($_POST['contact_first_name'], "text"),
                       GetSQLValueString($_POST['contact_last_name'], "text"),
                       GetSQLValueString($_POST['physical_address_number'], "text"),
                       GetSQLValueString($_POST['physical_address_street'], "text"),
                       GetSQLValueString($_POST['physical_address_city'], "text"),
                       GetSQLValueString($_POST['physical_address_state'], "text"),
                       GetSQLValueString($_POST['physical_address_zip'], "text"),
                       GetSQLValueString($_POST['physical_address_latlong'], "text"),
                       GetSQLValueString($_POST['billing_address_number'], "text"),
                       GetSQLValueString($_POST['billing_address_street'], "text"),
                       GetSQLValueString($_POST['billing_address_city'], "text"),
                       GetSQLValueString($_POST['billing_address_state'], "text"),
                       GetSQLValueString($_POST['billing_address_zip'], "text"),
                       GetSQLValueString($_POST['billing_address_latlong'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['cust_relation_emp'], "text"),
                       GetSQLValueString($_POST['distributor_rep'], "text"),
                       GetSQLValueString($_POST['account_type'], "text"),
                       GetSQLValueString($_POST['notes'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['date_edited'], "date"),
                       GetSQLValueString($_POST['customer_id'], "int"));

  mysql_select_db($database_strike, $strike);
  $Result1 = mysql_query($updateSQL, $strike) or die(mysql_error());

  $updateGoTo = "/customer-edit.php?edited=true&customer_id=" . $_POST['customer_id'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addCustomer")) {
  $insertSQL = sprintf("INSERT INTO customer (business_name, contact_first_name,contact_last_name, physical_address_number, physical_address_street, physical_address_city, physical_address_state, physical_address_zip, physical_address_latlong, billing_address_number, billing_address_street, billing_address_city, billing_address_state, billing_address_zip, billing_address_latlong, email, cust_relation_emp, distributor_rep, account_type, notes, phone, date_added, date_last_edited) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['business_name'], "text"),
                       GetSQLValueString($_POST['contact_first_name'], "text"),
                       GetSQLValueString($_POST['contact_last_name'], "text"),
                       GetSQLValueString($_POST['physical_address_number'], "text"),
                       GetSQLValueString($_POST['physical_address_street'], "text"),
                       GetSQLValueString($_POST['physical_address_city'], "text"),
                       GetSQLValueString($_POST['physical_address_state'], "text"),
                       GetSQLValueString($_POST['physical_address_zip'], "text"),
                       GetSQLValueString($_POST['physical_address_latlong'], "text"),
                       GetSQLValueString($_POST['billing_address_number'], "text"),
                       GetSQLValueString($_POST['billing_address_street'], "text"),
                       GetSQLValueString($_POST['billing_address_city'], "text"),
                       GetSQLValueString($_POST['billing_address_state'], "text"),
                       GetSQLValueString($_POST['billing_address_zip'], "text"),
                       GetSQLValueString($_POST['billing_address_latlong'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['cust_relation_emp'], "text"),
                       GetSQLValueString($_POST['distributor_rep'], "text"),
                       GetSQLValueString($_POST['account_type'], "text"),
                       GetSQLValueString($_POST['notes'], "text"),
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['date_added'], "date"),
                       GetSQLValueString($_POST['date_added'], "date"));

  mysql_select_db($database_strike, $strike);
  $Result1 = mysql_query($insertSQL, $strike) or die(mysql_error());

  $insertGoTo = "/customer-edit.php?added=true";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


mysql_select_db($database_strike, $strike);
$query_customers = "SELECT * FROM customer ORDER BY business_name ASC";
$customers = mysql_query($query_customers, $strike) or die(mysql_error());
$row_customers = mysql_fetch_assoc($customers);
$totalRows_customers = mysql_num_rows($customers);

$colname_customer = "-1";
if (isset($_REQUEST['customer_id'])) {
  $colname_customer = $_REQUEST['customer_id'];
}
mysql_select_db($database_strike, $strike);
$query_customer = sprintf("SELECT * FROM customer WHERE customer_id = %s", GetSQLValueString($colname_customer, "int"));
$customer = mysql_query($query_customer, $strike) or die(mysql_error());
$row_customer = mysql_fetch_assoc($customer);
$totalRows_customer = mysql_num_rows($customer);

$maxRows_employees = 10;
$pageNum_employees = 0;
if (isset($_GET['pageNum_employees'])) {
  $pageNum_employees = $_GET['pageNum_employees'];
}
$startRow_employees = $pageNum_employees * $maxRows_employees;

mysql_select_db($database_strike, $strike);
$query_employees = "SELECT * FROM employee";
$query_limit_employees = sprintf("%s LIMIT %d, %d", $query_employees, $startRow_employees, $maxRows_employees);
$employees = mysql_query($query_limit_employees, $strike) or die(mysql_error());
$row_employees = mysql_fetch_assoc($employees);

if (isset($_GET['totalRows_employees'])) {
  $totalRows_employees = $_GET['totalRows_employees'];
} else {
  $all_employees = mysql_query($query_employees);
  $totalRows_employees = mysql_num_rows($all_employees);
}
$totalPages_employees = ceil($totalRows_employees/$maxRows_employees)-1;

mysql_select_db($database_strike, $strike);
$query_account_types = "SELECT * FROM account_types";
$account_types = mysql_query($query_account_types, $strike) or die(mysql_error());
$row_account_types = mysql_fetch_assoc($account_types);
$totalRows_account_types = mysql_num_rows($account_types);

mysql_select_db($database_strike, $strike);
$query_distributor_reps = "SELECT * FROM distributor_reps";
$distributor_reps = mysql_query($query_distributor_reps, $strike) or die(mysql_error());
$row_distributor_reps = mysql_fetch_assoc($distributor_reps);
$totalRows_distributor_reps = mysql_num_rows($distributor_reps);
?>
<?php
	require('includes/pagetop.php');
?>
<?php
	$selectedHeaderTab = "customer";
	include('includes/header.php');
?>
<div class="container">
	<h1>Edit a customer's details</h1>
	<?php if(isset($_GET['edited'])){ ?>
	<div class="alert alert-success">
		<h2>Done</h2>
		<p><?php echo $row_customer['business_name']; ?> has been edited</p>
	</div>
	<?php } ?>
	<?php if(isset($_GET['added'])){ ?>
	<div class="alert alert-success">
		<h2>Done</h2>
		<p>Customer has been added</p>
	</div>
	<?php } ?>
	<form action="customer-edit.php" method="post" id="selectAndGoTarget">
		<div class="form-group">
			<select name="customer_id" id="customerSelect" class="selectAndGoTrigger form-control">
				<option value="">Select a customer to edit</option>
				<?php do { ?>
				<option value="<?php echo $row_customers['customer_id']; ?>"
									<?php
										if($row_customers['customer_id'] == $_REQUEST['customer_id']){
									?>
									selected="selected"
									<?php
										}
									?>
								><?php echo $row_customers['business_name']; ?></option>
				<?php } while ($row_customers = mysql_fetch_assoc($customers)); ?>
			</select>
		</div>
		</fieldset>
	</form>
	<div class="row">
		<?php if(isset($row_customer['customer_id'])){ //if we have a customer to edit ?>
		<div class="col-sm-6"><a class="btn btn-default btn-lg btn-block" href="/customer-check-in.php?customer_id=<?php echo $row_customer['customer_id']; ?>">Check in here</a></div>
		<div class="col-sm-6"><a class="btn btn-default btn-lg btn-block" href="/customer-edit.php">Add a new customer</a></div>
		<?php } ?>
	</div>
	<?php if(isset($row_customer['customer_id'])){ //if we have a customer to edit ?>
	<form action="<?php echo $editFormAction; ?>" name="addEditCustomer" method="POST" id="addEditCustomer">
		<input name="date_edited" type="hidden" value="<?php echo date('Y-m-d H:i:s'); ?>">
		<fieldset class="well">
			<legend>Edit information for <?php echo $row_customer['business_name']; ?></legend>
			<div class="form-group">
				<label for="billing_name">Customer/Business</label>
				<input class="form-control" value="<?php echo $row_customer['business_name']; ?>" type="text" required name="business_name" id="business_name">
			</div>
			<div class="form-group">
				<label for="contact_name">Contact Person</label>
				<div class="row">
					<fieldset class="col-sm-6">
						<label for="contact_first_name">First Name</label>
						<input class="form-control col-sm-6" name="contact_first_name" type="text" id="contact_first_name" value="<?php echo $row_customer['contact_first_name']; ?>">
					</fieldset>
					<fieldset class="col-sm-6">
						<label for="contact_last_name">Last Name</label>
						<input class="form-control col-sm-6" name="contact_last_name" type="text" id="contact_last_name" value="<?php echo $row_customer['contact_last_name']; ?>">
					</fieldset>
				</div>
			</div>
			<div class="form-group">
				<label for="email">Email</label>
				<input class="form-control" type="email" name="email" id="email" value="<?php echo $row_customer['email']; ?>">
			</div>
			<div class="form-group">
				<label for="phone">Phone</label>
				<input class="form-control" type="phone" name="phone" id="phone" value="<?php echo $row_customer['phone']; ?>">
			</div>
		</fieldset>
		<fieldset class="well">
			<legend>Physical Address Information</legend>
			<div class="form-group">
				<label for="physical_address_number">Address (number)</label>
				<input class="form-control" name="physical_address_number" type="text" id="physical_address_number" value="<?php echo $row_customer['physical_address_number']; ?>">
			</div>
			<div class="form-group">
				<label for="physical_address_street">Street Name</label>
				<input class="form-control" name="physical_address_street" type="text" id="physical_address_street" value="<?php echo $row_customer['physical_address_street']; ?>">
			</div>
			<div class="form-group">
				<label for="physical_address_latlong">Lat/Long</label>
				<input class="form-control" name="physical_address_latlong" type="text" id="physical_address_latlong" value="<?php echo $row_customer['physical_address_latlong']; ?>">
			</div>
			<div class="form-group">
				<label for="physical_address_city">City</label>
				<input class="form-control" type="text" name="physical_address_city" id="physical_address_city" value="<?php echo $row_customer['physical_address_city']; ?>">
			</div>
			<div class="form-group">
				<label for="physical_address_state">State</label>
				<input class="form-control" type="text" name="physical_address_state" id="physical_address_state" 
							value="<?php 
							if($row_customer['physical_address_state'] != '') { 
								echo $row_customer['physical_address_state'];
								} else { echo 'CA'; }; ?>">
			</div>
			<div class="form-group">
				<label for="physical_address_zip">Zip</label>
				<input class="form-control" type="text" name="physical_address_zip" id="physical_address_zip" value="<?php echo $row_customer['physical_address_zip']; ?>">
			</div>
		</fieldset>
		<fieldset class="well">
			<legend style="border:0;">Business Address Information
			<button class="btn btn-link" id="copyAddress"><small>copy physical address above</small></button>
			</legend>
			<div class="form-group">
				<label for="billing_address_number">Address (number)</label>
				<input class="form-control" name="billing_address_number" type="text" id="billing_address_number" value="<?php echo $row_customer['billing_address_number']; ?>">
			</div>
			<div class="form-group">
				<label for="billing_address_number">Street</label>
				<input class="form-control" name="billing_address_street" type="text" id="billing_address_street" value="<?php echo $row_customer['billing_address_street']; ?>">
			</div>
			<div class="form-group">
				<label for="billing_address_latlong">Lat/Long</label>
				<input class="form-control" name="billing_address_latlong" type="text" id="billing_address_latlong" value="<?php echo $row_customer['billing_address_latlong']; ?>">
			</div>
			<div class="form-group">
				<label for="billing_address_city">City</label>
				<input class="form-control" type="text" name="billing_address_city" id="billing_address_city" value="<?php echo $row_customer['billing_address_city']; ?>">
			</div>
			<div class="form-group">
				<label for="billing_address_state">State</label>
				<input class="form-control" type="text" name="billing_address_state" id="billing_address_state" value="<?php 
										if($row_customer['billing_address_state'] != '') { 
											echo $row_customer['billing_address_state'];
											} else { echo 'CA'; }; ?>">
			</div>
			<div class="form-group">
				<label for="billing_address_zip">Zip</label>
				<input class="form-control" type="text" name="billing_address_zip" id="billing_address_zip" value="<?php echo $row_customer['billing_address_zip']; ?>">
			</div>
		</fieldset>
		<fieldset class="well">
			<legend>Other</legend>
			<div class="form-group">
				<label for="account_type">Account Type</label>
				<select class="form-control" name="account_type" id="account_type">
					<option>Select one</option>
					<?php
									do {  
									?>
					<option value="<?php echo $row_account_types['id']?>"<?php if (!(strcmp($row_account_types['id'], $row_customer['account_type']))) {echo "selected=\"selected\"";} ?>><?php echo $row_account_types['account_type']?></option>
					<?php
									} while ($row_account_types = mysql_fetch_assoc($account_types));
									  $rows = mysql_num_rows($account_types);
									  if($rows > 0) {
										  mysql_data_seek($account_types, 0);
										  $row_account_types = mysql_fetch_assoc($account_types);
									  }
									?>
				</select>
			</div>
			<div class="form-group">
				<label for="cust_relation_emp">Strike Representative</label>
				<select class="form-control" name="cust_relation_emp" id="cust_relation_emp">
					<option>Select one</option>
					<?php
								do {  
								?>
					<option value="<?php echo $row_employees['id']?>" <?php if (!(strcmp($row_employees['id'], $row_customer['cust_relation_emp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_employees['emp_first_name'] . ' ' . $row_employees['emp_last_name']?></option>
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
			<div class="form-group">
				<label for="cust_relation_emp">DBI Representative</label>
				<select class="form-control" name="distributor_rep" id="distributor_rep">
					<option>Select one</option>
					<?php
								do {  
								?>
					<option value="<?php echo $row_distributor_reps['id']?>" <?php if (!(strcmp($row_distributor_reps['id'], $row_customer['distributor_rep']))) {echo "selected=\"selected\"";} ?>><?php echo $row_distributor_reps['first_name'] . ' ' . $row_distributor_reps['last_name']?></option>
					<?php
								} while ($row_distributor_reps = mysql_fetch_assoc($distributor_reps));
								  $rows = mysql_num_rows($distributor_reps);
								  if($rows > 0) {
									  mysql_data_seek($distributor_reps, 0);
									  $row_distributor_reps = mysql_fetch_assoc($distributor_reps);
								  }
								?>
				</select>
			</div>
			<div class="form-group">
				<label for="notes">Notes</label>
				<textarea class="form-control" name="notes" id="notes"><?php echo $row_customer['notes']; ?></textarea>
				<p>This is for general notes about this customer. You can also "<a href="/customer-check-in.php?customer_id=<?php echo $row_customer['customer_id']; ?>">check in</a>".</p>
			</div>
		</fieldset>
		<input type="hidden" name="customer_id" value="<?php echo $row_customer['customer_id']; ?>">
		<input type="hidden" name="MM_update" value="addEditCustomer">
		<button class="btn btn-primary" type="submit">Submit</button>
	</form>
	<?php 
			} //end if we have a customer to edit
			else {// otherwise, present the 'add customer form
		?>
	<h2 id="addNew">Add a new customer</h2>
	<form action="<?php echo $editFormAction; ?>" name="addCustomer" method="POST" id="addCustomer">
		<input name="date_added" type="hidden" value="<?php echo date('Y-m-d H:i:s'); ?>">
		<fieldset class="well">
			<legend>Customer Information</legend>
			<div class="form-group">
				<label for="billing_name">Customer/Business</label>
				<input class="form-control" type="text" name="business_name" id="business_name" required>
			</div>
			<div class="form-group">
				<label for="contact_name">Contact Person</label>
				<div class="row">
					<fieldset class="col-sm-6">
						<label for="contact_first_name">First Name</label>
						<input class="form-control" name="contact_first_name" type="text" id="contact_first_name" required>
					</fieldset>
					<fieldset class="col-sm-6">
						<label for="contact_last_name">Last Name</label>
						<input class="form-control" name="contact_last_name" type="text" id="contact_last_name" required>
					</fieldset>
				</div>
			</div>
			<div class="form-group">
				<label for="email">Email</label>
				<input class="form-control" type="text" name="email" id="email">
			</div>
			<div class="form-group">
				<label for="phone">Phone</label>
				<input class="form-control" type="phone" name="phone" id="phone" value="">
			</div>
		</fieldset>
		<fieldset class="well">
			<legend>Physical Address Information</legend>
			<div class="form-group">
				<label for="physical_address_number">Address (number)</label>
				<input class="form-control" name="physical_address_number" type="text" id="physical_address_number">
			</div>
			<div class="form-group">
				<label for="physical_address_street">Street Name</label>
				<input class="form-control" name="physical_address_street" type="text" id="physical_address_street">
			</div>
			<div class="form-group">
				<label for="physical_address_latlong">Lat/Long</label>
				<input class="form-control" name="physical_address_latlong" type="text" id="physical_address_latlong">
			</div>
			<div class="form-group">
				<label for="physical_address_city">City</label>
				<input class="form-control" type="text" name="physical_address_city" id="physical_address_city">
			</div>
			<div class="form-group">
				<label for="physical_address_state">State</label>
				<input class="form-control" type="text" name="physical_address_state" id="physical_address_state" 
							value="<?php 
							if($row_customer['physical_address_state'] != '') { 
								echo $row_customer['physical_address_state'];
								} else { echo 'CA'; }; ?>">
			</div>
			<div class="form-group">
				<label for="physical_address_zip">Zip</label>
				<input class="form-control" type="text" name="physical_address_zip" id="physical_address_zip">
			</div>
		</fieldset>
		<fieldset class="well">
			<legend style="border:0;">Business Address Information
			<button class="btn btn-link" id="copyAddress"><small>copy physical address above</small></button>
			</legend>
			<div class="form-group">
				<label for="billing_address_number">Address (number)</label>
				<input class="form-control" name="billing_address_number" type="text" id="billing_address_number">
			</div>
			<div class="form-group">
				<label for="billing_address_number">Street</label>
				<input class="form-control" name="billing_address_street" type="text" id="billing_address_street">
			</div>
			<div class="form-group">
				<label for="billing_address_latlong">Lat/Long</label>
				<input class="form-control" name="billing_address_latlong" type="text" id="billing_address_latlong">
			</div>
			<div class="form-group">
				<label for="billing_address_city">City</label>
				<input class="form-control" type="text" name="billing_address_city" id="billing_address_city">
			</div>
			<div class="form-group">
				<label for="billing_address_state">State</label>
				<input class="form-control" type="text" name="billing_address_state" id="billing_address_state">
			</div>
			<div class="form-group">
				<label for="billing_address_zip">Zip</label>
				<input class="form-control" type="text" name="billing_address_zip" id="billing_address_zip">
			</div>
		</fieldset>
		<fieldset class="well">
			<legend>Other</legend>
			<div class="form-group">
				<label for="account_type">Account Type</label>
				<select class="form-control" name="account_type" id="account_type">
					<option>Select one</option>
					<?php
									do {  
									?>
					<option value="<?php echo $row_account_types['id']?>"<?php if (!(strcmp($row_account_types['id'], $row_customer['account_type']))) {echo "selected=\"selected\"";} ?>><?php echo $row_account_types['account_type']?></option>
					<?php
									} while ($row_account_types = mysql_fetch_assoc($account_types));
									  $rows = mysql_num_rows($account_types);
									  if($rows > 0) {
										  mysql_data_seek($account_types, 0);
										  $row_account_types = mysql_fetch_assoc($account_types);
									  }
									?>
				</select>
			</div>
			<div class="form-group">
				<label for="cust_relation_emp">Strike Representative</label>
				<select class="form-control" name="cust_relation_emp" id="cust_relation_emp">
					<option>Select one</option>
					<?php
								do {  
								?>
					<option value="<?php echo $row_employees['id']?>" <?php if (!(strcmp($row_employees['id'], $row_customer['cust_relation_emp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_employees['emp_first_name']?></option>
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
			<div class="form-group">
				<label for="cust_relation_emp">Distributor Representative</label>
				<select class="form-control" name="distributor_rep" id="distributor_rep">
					<option>Select one</option>
					<?php
								do {  
								?>
					<option value="<?php echo $row_distributor_reps['id']?>" <?php if (!(strcmp($row_distributor_reps['id'], $row_customer['distributor_rep']))) {echo "selected=\"selected\"";} ?>><?php echo $row_distributor_reps['first_name'] . ' ' . $row_distributor_reps['last_name']?></option>
					<?php
								} while ($row_distributor_reps = mysql_fetch_assoc($distributor_reps));
								  $rows = mysql_num_rows($distributor_reps);
								  if($rows > 0) {
									  mysql_data_seek($distributor_reps, 0);
									  $row_distributor_reps = mysql_fetch_assoc($distributor_reps);
								  }
								?>
				</select>
			</div>
			<div class="form-group">
				<label for="notes">Notes</label>
				<textarea name="notes" id="notes" class="form-control" ><?php echo $row_customer['notes']; ?></textarea>
			</div>
		</fieldset>
		<button class="btn btn-primary" type="submit">Submit</button>
		<input type="hidden" name="MM_insert" value="addCustomer">
	</form>
	<?php } //end add new customer form ?>
	<?php include('includes/footer.php'); ?>
</div>
<!-- /container -->

<?php include('includes/pageClose.php'); ?>
<script>
		$('#customerSelect').on('change',function(){
			$('#customerSelectForm').submit();
		});
		$('#copyAddress').on('click',function(){
				$('#billing_address_number').val($('#physical_address_number').val());
				$('#billing_address_street').val($('#physical_address_street').val());
				$('#billing_address_city').val($('#physical_address_city').val());
				$('#billing_address_state').val($('#physical_address_state').val());
				$('#billing_address_zip').val($('#physical_address_zip').val());
				$('#billing_address_latlong').val($('#physical_address_latlong').val());
				return false;
			}
		);
</script>
</body>
</html>
<?php
mysql_free_result($customers);

mysql_free_result($customer);

mysql_free_result($employees);

mysql_free_result($account_types);

mysql_free_result($distributor_reps);
?>
