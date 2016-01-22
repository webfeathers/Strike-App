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
	<h1>Customer details</h1>
	

			<form action="customer.php" method="post" id="selectAndGoTarget">
				<fieldset>
					<legend>Select a customer </legend>
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
						
						<div class="col-sm-4"> <a class="btn btn-default btn-lg btn-block" href="/customer-check-in.php?customer_id=<?php echo $row_customer['customer_id']; ?>">Check in here</a> </div>
						<div class="col-sm-4"> <a class="btn btn-default btn-lg btn-block" href="/customer-edit.php?customer_id=<?php echo $row_customer['customer_id']; ?>">Edit Customer</a> </div>
						<div class="col-sm-4"> <a class="btn btn-default btn-lg btn-block" href="/customer-edit.php">Add a new customer</a> </div>
					</fieldset>
			</form>
			<?php if(isset($row_customer['customer_id'])){ //if we have a customer to edit ?>
			<h2><?php echo $row_customer['business_name']; ?></h2>
	

					<div class="row-fluid">
						<div class="span12">
							<div>
								<strong>Contact Person:</strong>
								<?php echo $row_customer['contact_first_name']; ?>
								<?php echo $row_customer['contact_last_name']; ?>
							</div>
							<div>
								<strong>Email: </strong>
								<a href="mailto:<?php echo $row_customer['email']; ?>"><?php echo $row_customer['email']; ?></a>
							</div>
							<div>
								<strong>Phone:</strong>
								<a href="tel:<?php echo $row_customer['phone']; ?>"><?php echo $row_customer['phone']; ?></a>
							</div>
							<div>
								<strong>Notes:</strong>
								<?php echo $row_customer['notes']; ?>
							</div>
							<div>
								<strong>Strike Representative: </strong>
								<?php do { ?>
								<?php if (!(strcmp($row_employees['id'], $row_customer['cust_relation_emp']))) {?>
										<?php echo $row_employees['emp_first_name'] . ' ' . $row_employees['emp_last_name']?>
								<?php }
								} while ($row_employees = mysql_fetch_assoc($employees));
								  $rows = mysql_num_rows($employees);
								  if($rows > 0) {
									  mysql_data_seek($employees, 0);
									  $row_employees = mysql_fetch_assoc($employees);
								  }
								?>
							</div>
							<div>
								<strong>Distributor Representative: </strong>
								<?php do { ?>
								<?php if (!(strcmp($row_distributor_reps['id'], $row_customer['distributor_rep']))) {?>
										<?php echo $row_distributor_reps['first_name'] . ' ' . $row_distributor_reps['last_name']?>
								<?php }
								} while ($row_distributor_reps = mysql_fetch_assoc($distributor_reps));
								  $rows = mysql_num_rows($distributor_reps);
								  if($rows > 0) {
									  mysql_data_seek($distributor_reps, 0);
									  $row_distributor_reps = mysql_fetch_assoc($distributor_reps);
								  }
								?>
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span6">
							<h3>Physical address</h3>
							<?php echo $row_customer['physical_address_number']; ?> <?php echo $row_customer['physical_address_street']; ?>
							<br />
							<?php echo $row_customer['physical_address_city']; ?>, <?php echo $row_customer['physical_address_state']; ?> <?php echo $row_customer['physical_address_zip']; ?>
							<br />
							<a href="http://maps.apple.com/?q=<?php echo $row_customer['physical_address_number']; ?> <?php echo $row_customer['physical_address_street']; ?> <?php echo $row_customer['physical_address_city']; ?>, <?php echo $row_customer['physical_address_state']; ?> <?php echo $row_customer['physical_address_zip']; ?>" target="mapwin">Link to map</a>
						</div>
						<div class="span6">
							<h3>Billing address</h3>
							<?php echo $row_customer['billing_address_number']; ?> <?php echo $row_customer['billing_address_street']; ?>
							<br />
							<?php echo $row_customer['billing_address_city']; ?>, <?php echo $row_customer['billing_address_state']; ?> <?php echo $row_customer['billing_address_zip']; ?>
							</div>
					</div>


				<?php 
			} //end if we have a customer to edit
			else {// otherwise, present the 'add customer form
		?>
			no customer selected
			<?php } //end add new customer form ?>
	<?php include('includes/footer.php'); ?>
</div>
<!-- /container --> 

<?php include('includes/pageClose.php'); ?>

</body>
</html>
<?php
mysql_free_result($customers);

mysql_free_result($customer);

mysql_free_result($employees);

mysql_free_result($account_types);

mysql_free_result($distributor_reps);
?>
