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

if ((isset($_POST['cust_id'])) && ($_POST['cust_id'] != "") && (isset($_POST['delete_customers']))) {
	$customer_ids = implode(", ", $_POST['cust_id']);
	$deleteSQL = sprintf("DELETE FROM customer WHERE customer_id IN (" . $customer_ids . ")");
	mysql_select_db($database_strike, $strike);
	$Result1 = mysql_query($deleteSQL, $strike) or die(mysql_error());
	
	$deleteGoTo = "/customers.php?deleted=true";
	if (isset($_SERVER['QUERY_STRING'])) {
		$deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
		$deleteGoTo .= $_SERVER['QUERY_STRING'];
	}
	header(sprintf("Location: %s", $deleteGoTo));
}

mysql_select_db($database_strike, $strike);
$query_customers = "SELECT * FROM customer ORDER BY business_name ASC";
$customers = mysql_query($query_customers, $strike) or die(mysql_error());
$row_customers = mysql_fetch_assoc($customers);
$totalRows_customers = mysql_num_rows($customers);
?>
<?php
	require('includes/pagetop.php');
?>
<?php
	$selectedHeaderTab = "customer";
	include('includes/header.php');
?>
<div class="container">
	<h1>Customers</h1>
	<div class="well">
		<p>This is a list of all customers in the database. You can delete customers by selecting them and clicking "Delete" at the bottom of this page, or click on the "edit" link to modify a customer's details.</p>
		<p>You can also <a href="customer-edit.php">add a new customer</a>.</p>
	</div>
	<?php if(isset($_GET['deleted'])){ ?>
	<div class="alert alert-success">
		<h2>Done</h2>
		<p>Those customers have been deleted.</p>
	</div>
	<?php } ?>
	<form method="post" onSubmit="return confirmDeletion()">
		<input type="hidden" name="delete_customers" id="delete_customers" value="true">
		<?php do { ?>
			<div class="col-sm-6 well">
				<div class="row">
					<div class="col-xs-6">
						<label class="checkbox" for="cust_<?php echo $row_customers['customer_id']; ?>" >
							<input
									type="checkbox"
									name="cust_id[]"
									value="<?php echo $row_customers['customer_id']; ?>"
									id="cust_<?php echo $row_customers['customer_id']; ?>"
									rel="<?php echo $row_customers['business_name']; ?>" />
							<?php echo $row_customers['business_name']; ?> </label>
					</div>
					<div class="col-xs-6"> <a class="btn btn-default btn-block btn-info  " href="customer.php?customer_id=<?php echo $row_customers['customer_id']; ?>">view</a> <a class="btn btn-default btn-block btn-info " href="customer-edit.php?customer_id=<?php echo $row_customers['customer_id']; ?>">edit</a></div>
				</div>
			</div>
			<?php } while ($row_customers = mysql_fetch_assoc($customers)); ?>
		<div class="row">
			<button type="submit" class="btn btn-default btn-lg">Delete selected</button>
		</div>
	</form>
	<?php include('includes/footer.php'); ?>
</div>
<?php include('includes/pageClose.php'); ?>
<script>
	function confirmDeletion(){
		var customers = $(":checked"),
			customerList = "Are you sure you want to delete:\n";
			$(customers).each(function(){
					customerList += " - " + $(this).attr("rel") + "\n";
			});
			customerList += "\nTHIS IS IRREVERSIBLE";
		return confirm(customerList);
	}
</script>
</body>
</html>
<?php
mysql_free_result($customers);
?>
