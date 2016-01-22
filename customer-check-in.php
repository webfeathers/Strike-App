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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "checkin")) {
  $insertSQL = sprintf("INSERT INTO check_ins (customer_id, customer_state, `comment`, employee_id, next_step_date, relevant_recipe, relevant_recipe_size) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['customer_id'], "int"),
                       GetSQLValueString($_POST['customer_state'], "int"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['employee_id'], "int"),
                       GetSQLValueString($_POST['next_step_date'], "date"),
                       GetSQLValueString($_POST['recipe'], "int"),
                       GetSQLValueString($_POST['recipe_size'], "int"));

  mysql_select_db($database_strike, $strike);
  $Result1 = mysql_query($insertSQL, $strike) or die(mysql_error());

  $insertGoTo = "/customer-check-in.php?checkin=success";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
//  header(sprintf("Location: %s", $insertGoTo));
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

mysql_select_db($database_strike, $strike);
$query_customer_states = "SELECT * FROM customer_states";
$customer_states = mysql_query($query_customer_states, $strike) or die(mysql_error());
$row_customer_states = mysql_fetch_assoc($customer_states);
$totalRows_customer_states = mysql_num_rows($customer_states);

$colname_employee = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_employee = $_SESSION['MM_Username'];
}
mysql_select_db($database_strike, $strike);
$query_employee = sprintf("SELECT * FROM employee WHERE emp_first_name = %s", GetSQLValueString($colname_employee, "text"));
$employee = mysql_query($query_employee, $strike) or die(mysql_error());
$row_employee = mysql_fetch_assoc($employee);
$totalRows_employee = mysql_num_rows($employee);

$colname_most_recent_checkin = "-1";
if (isset($_POST['customer_id'])) {
  $colname_most_recent_checkin = $_POST['customer_id'];
}
mysql_select_db($database_strike, $strike);
$query_most_recent_checkin = sprintf("SELECT * FROM check_ins WHERE customer_id = %s", GetSQLValueString($colname_most_recent_checkin, "int"));
$most_recent_checkin = mysql_query($query_most_recent_checkin, $strike) or die(mysql_error());
$row_most_recent_checkin = mysql_fetch_assoc($most_recent_checkin);
$totalRows_most_recent_checkin = mysql_num_rows($most_recent_checkin);

mysql_select_db($database_strike, $strike);
$query_recipes = "SELECT * FROM recipes";
$recipes = mysql_query($query_recipes, $strike) or die(mysql_error());
$row_recipes = mysql_fetch_assoc($recipes);
$totalRows_recipes = mysql_num_rows($recipes);

mysql_select_db($database_strike, $strike);
$query_recipe_sizes = "SELECT * FROM sizes";
$recipe_sizes = mysql_query($query_recipe_sizes, $strike) or die(mysql_error());
$row_recipe_sizes = mysql_fetch_assoc($recipe_sizes);
$totalRows_recipe_sizes = mysql_num_rows($recipe_sizes);
?>
<?php
	require('includes/pagetop.php');
?>
<?php
	$selectedHeaderTab = "checkin";
	include('includes/header.php');
?>
<div class="container">
	<h1>Customer Check-In</h1>
	<p class="nogeo"></p>
	<?php if(isset($_GET['checkin'])){ ?>
	<div class="alert alert-success">
		<h2>Done</h2>
		<p>You've checked in!</p>
	</div>
	<?php } ?>
	
	<!-- Example row of columns -->
	<form id="selectAndGoTarget" method="post" action="customer-check-in.php">
		<fieldset>
			<div class="form-group">
				<select name="customer_id" id="customersSelect" class="selectAndGoTrigger form-control">
					<option>Choose a customer</option>
					<?php do { ?>
					<option data-latlong="<?php echo $row_customers['physical_address_latlong']?>" 
							value="<?php echo $row_customers['customer_id']?>" 
								<?php if (!(strcmp($row_customers['customer_id'], $row_customer['customer_id']))) {?> 
									selected="selected" 
								<?php } ?>> <?php echo $row_customers['business_name']?> </option>
					<?php
							} while ($row_customers = mysql_fetch_assoc($customers));
							  $rows = mysql_num_rows($customers);
							  if($rows > 0) {
								  mysql_data_seek($customers, 0);
								  $row_customers = mysql_fetch_assoc($customers);
							  }
						?>
				</select>
			</div>
			<div id="preselect" class="col-md-3 hide"> Are you at <span class="customer"></span>?
				<button class="btn btn-mini btn-success" id="selectCustomer">Yes!</button>
			</div>
		</fieldset>
	</form>
	<?php
		if(isset($row_customer['business_name'])){ ?>
		<div class="row">
	<div class="col-sm-6"><a class="btn btn-lg btn-default btn-block" href="customer-edit.php?customer_id=<?php echo $row_customer['customer_id']; ?>">Edit Customer</a></div>
	<div class="col-sm-6"><a class="btn btn-default btn-lg btn-block" href="/customer-edit.php">Add a new customer</a></div>
</div>
	<form method="POST" action="<?php echo $editFormAction; ?>" name="checkin" id="checkin">
		<input type="hidden" name="customer_id" value="<?php echo $row_customer['customer_id']; ?>" />
		<input type="hidden" name="employee_id" value="<?php echo $row_employee['id']; ?>" />
		<fieldset class="well">
			<legend>Check In with <?php echo $row_customer['business_name']; ?></legend>
			<div class="form-group">
				<label for="customer_state">Customer's new state</label>
				<select class="form-control" name="customer_state" id="customer_state">
				<?php
		do {  
		?>
				<option
								value="<?php echo $row_customer_states['id']?>"
								<?php if($row_most_recent_checkin['customer_state'] ===  $row_customer_states['id']){ ?>selected="selected"<?php } ?>
								><?php echo $row_customer_states['state']?></option>
				<?php
		} while ($row_customer_states = mysql_fetch_assoc($customer_states));
		  $rows = mysql_num_rows($customer_states);
		  if($rows > 0) {
			  mysql_data_seek($customer_states, 0);
			  $row_customer_states = mysql_fetch_assoc($customer_states);
		  }
		?>
				</select>
			</div>
			<div class="form-group col-6-sm">
				<label for="next_step_date">Follow up on:</label>
				<input class="form-control" type="date" name="next_step_date" id="next_step_date" value="<?php echo date('Y-m-d'); ?>">
			</div>

			<div class="form-group">
				
				<label for="comments">Customer is interested in:</label>
					<select name="recipe">
						<option value="">Choose a recipe</option>
						<?php
do {  
?>
						<option value="<?php echo $row_recipes['id']?>"><?php echo $row_recipes['name']?></option>
						<?php
} while ($row_recipes = mysql_fetch_assoc($recipes));
  $rows = mysql_num_rows($recipes);
  if($rows > 0) {
      mysql_data_seek($recipes, 0);
	  $row_recipes = mysql_fetch_assoc($recipes);
  }
?>
					</select>
					<select name="recipe_size">
						<option value="">Choose a size</option>
						<?php
do {  
?>
						<option value="<?php echo $row_recipe_sizes['id']?>"><?php echo $row_recipe_sizes['name']?></option>
						<?php
} while ($row_recipe_sizes = mysql_fetch_assoc($recipe_sizes));
  $rows = mysql_num_rows($recipe_sizes);
  if($rows > 0) {
      mysql_data_seek($recipe_sizes, 0);
	  $row_recipe_sizes = mysql_fetch_assoc($recipe_sizes);
  }
?>
					</select>
			</div>
			
			<div class="form-group">
				<label for="comments">Comments</label>
				<textarea class="form-control" name="comments" id="comments" required></textarea>
			</div>
			
			
			
			<input type="hidden" name="MM_insert" value="checkin">
			
		</fieldset>
		<div class="form-group">
			<button type="submit" class="btn btn-primary">Check In!</button>
		</div>
	</form>
	<?php } //end of if business_name is set
				else {
		 ?>

		<div class="row">
		<div class="col-sm-12">Choose a customer above - we'll try to detect where you are, but the geo-location coordinates aren't there for every customer yet.</div>
		</div>
		<div class="row">
	<div class="col-sm-12"><a class="btn btn-default btn-lg btn-block" href="/customer-edit.php">Add a new customer</a></div>
</div>

	<?php } //end of else for business_name check ?>
	<?php include('includes/footer.php'); ?>
</div>

<?php include('includes/pageClose.php'); ?>

</body>
</html>
<?php
mysql_free_result($customers);

mysql_free_result($customer);

mysql_free_result($customer_states);

mysql_free_result($employee);

mysql_free_result($most_recent_checkin);

mysql_free_result($recipes);

mysql_free_result($recipe_sizes);
?>
