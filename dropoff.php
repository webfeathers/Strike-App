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
$query_customers = "SELECT * FROM customer";
$customers = mysql_query($query_customers, $strike) or die(mysql_error());
$row_customers = mysql_fetch_assoc($customers);
$totalRows_customers = mysql_num_rows($customers);
?>
<?php
	require('includes/pagetop.php');
?>
<?php
		include('includes/header.php');
	?>
	<div class="container">
		<h1>Drop Off Keg</h1>
		
		<!-- Example row of columns -->
		<div class="row">
			<div class="span12">
				<form action="dropoff.php" method="post">
					<fieldset>
						<legend>Where are you</legend>
						<label for="customer_location">Pick the customer you're visiting</label>
						<select id="customer_location">
							<option>Select one</option>
							<?php do { ?>
							<option value="<?php echo $row_customers['name']; ?>"><?php echo $row_customers['business_name']; ?></option>
							<?php } while ($row_customers = mysql_fetch_assoc($customers)); ?>
						</select>
					</fieldset>
					<fieldset>
						<legend>What are you dropping off?</legend>
						<label for="product">Select the product</label>
						<select id="product">
							<option>Select one</option>
						</select>
							<label for="quantity">How many kegs?</label>
							<input type="number" min="1" max="50" value="1">
						</fieldset>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
		<?php include('includes/footer.php'); ?>
	</div>
<!-- /container --> 

<script src="http://code.jquery.com/jquery-latest.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/strike-kegs.js"></script>
</body>
</html>
<?php
mysql_free_result($customers);
?>
