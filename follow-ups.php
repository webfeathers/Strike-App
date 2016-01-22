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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form")) {
  $updateSQL = sprintf("UPDATE check_ins SET clear_follow_up=%s WHERE id=%s",
                       GetSQLValueString($_POST['clear_follow_up'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_strike, $strike);
  $Result1 = mysql_query($updateSQL, $strike) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "setNewFollowUpDate")) {
  $updateSQL = sprintf("UPDATE check_ins SET next_step_date=%s, clear_follow_up=%s WHERE id=%s",
                       GetSQLValueString($_POST['next_step_date'], "date"),
                       GetSQLValueString($_POST['clear_follow_up'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_strike, $strike);
  $Result1 = mysql_query($updateSQL, $strike) or die(mysql_error());
}

mysql_select_db($database_strike, $strike);
$query_employees = "SELECT * FROM employee";
$employees = mysql_query($query_employees, $strike) or die(mysql_error());
$row_employees = mysql_fetch_assoc($employees);
$totalRows_employees = mysql_num_rows($employees);

$colname_employee_id_for_session = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_employee_id_for_session = $_SESSION['MM_Username'];
}
mysql_select_db($database_strike, $strike);
$query_employee_id_for_session = sprintf("SELECT * FROM employee WHERE emp_first_name = %s", GetSQLValueString($colname_employee_id_for_session, "text"));
$employee_id_for_session = mysql_query($query_employee_id_for_session, $strike) or die(mysql_error());
$row_employee_id_for_session = mysql_fetch_assoc($employee_id_for_session);
$totalRows_employee_id_for_session = mysql_num_rows($employee_id_for_session);



$todaysDate = date('Y-m-d');
//$todaysDate_followUps = "2013-04-22";
if (isset($todaysDate)) {
  $todaysDate_followUps = $todaysDate;
}
$userID_followUps =  $row_employee_id_for_session['id']; 


if (isset($_REQUEST['employee_id']) ) {
  $userID_followUps = $_REQUEST['employee_id'];
} else {
  $userID_followUps =  $row_employee_id_for_session['id'];
}
mysql_select_db($database_strike, $strike);
$query_followUps = sprintf("SELECT t1.*, t1.id AS checkinID, t2.*, t3.* 
							FROM check_ins t1 
								INNER JOIN 
								customer t2 
									ON t2.customer_id = t1.customer_id 
								LEFT JOIN 
								distributor_reps t3 ON t2.distributor_rep = t3.id 
									WHERE t1.employee_id = %s 
										AND t1.next_step_date >= %s
										AND t1.clear_follow_up = 0
							ORDER BY t1.next_step_date  ASC", GetSQLValueString($userID_followUps, "text"),GetSQLValueString($todaysDate_followUps, "text"));
$followUps = mysql_query($query_followUps, $strike) or die(mysql_error());
$row_followUps = mysql_fetch_assoc($followUps);
$totalRows_followUps = mysql_num_rows($followUps);

?>
<?php
	require('includes/pagetop.php');
?>
<?php
	$selectedHeaderTab = "customer";
	include('includes/header.php');
?>
<div class="container striped">
	<h1>Follow-Ups</h1>
	
	<?php if($row_followUps){ ?>
	<?php
		$even = true;
		do { ?>
		<div class=" row">
		<div class="col-sm-6">
			<?php if($row_followUps['next_step_date'] == $todaysDate_followUps){ ?>
			<div class="alert alert-info">Follow-up Today!</div>
			<?php } ?>
			<p><a href="../customer-edit.php?customer_id=<?php echo $row_followUps['customer_id']; ?>"><?php echo $row_followUps['business_name']; ?></a> on <?php echo $row_followUps['next_step_date']; ?> </p>
			<p> <strong>Last comment was:</strong> <em><?php echo $row_followUps['comment']; ?></em> </p>
			<form method="POST" action="<?php echo $editFormAction; ?>" name="form" class="form-inline" style="display:inline;" >
				<div class="form-group">
					<input type="hidden" name="id" value="<?php echo $row_followUps['checkinID']; ?>" />
					<input type="hidden" name="clear_follow_up" value="1" />
					<button type="submit" class="btn btn-xs btn-primary">Clear follow up</button>
					<input type="hidden" name="MM_update" value="form">
				</div>
			</form>
			or, set a new follow-up date for <form method="POST" action="<?php echo $editFormAction; ?>" name="setNewFollowUpDate" class="form-inline" style="display:inline;" 
						
			<input type="hidden" name="id" value="<?php echo $row_followUps['checkinID']; ?>" />
			<input type="hidden" name="clear_follow_up" value="" />
			<input class=" input-sm " type="date" name="next_step_date" value="<?php echo date('Y-m-d', strtotime("+1 week")); ?>" />
			<button type="submit" class="btn btn-default btn-xs">Set</button>
			<input type="hidden" name="MM_update" value="setNewFollowUpDate">
			</form>
		</div>
		<div class="col-sm-6">
			<div class="well">
				<h4><?php echo $row_followUps['business_name']; ?></h4>
				<?php if($row_followUps['phone'] != '') { ?>
				<div class="row">
					<strong class="col-sm-3">Phone:</strong>
					<span class="col-sm-9"><a href="tel:<?php echo $row_followUps['phone']; ?>"><?php echo $row_followUps['phone']; ?></a></span>
				</div>
				<?php } ?>
				
				<?php if($row_followUps['email'] != '') { ?>
				<div class="row">
					<strong class="col-sm-3">E-mail:</strong>
					<span class="col-sm-9"><a href="mailto:<?php echo $row_followUps['email']; ?>"><?php echo $row_followUps['email']; ?></a></span>
				</div>
				<?php } ?>
				
				<div  class="row">
					<strong class="col-sm-3">Address:</strong>
					<span class="col-sm-9">
						<?php echo $row_followUps['physical_address_number']; ?>
						<?php echo $row_followUps['physical_address_street']; ?><br />
						<?php echo $row_followUps['physical_address_city']; ?>
						<?php echo $row_followUps['physical_address_state']; ?>
						<?php echo $row_followUps['physical_address_zip']; ?><br />
						<a href="http://maps.apple.com/?q=<?php echo $row_followUps['physical_address_number']; ?>
						<?php echo $row_followUps['physical_address_street']; ?>
						<?php echo $row_followUps['physical_address_city']; ?>, 
						<?php echo $row_followUps['physical_address_state']; ?> 
						<?php echo $row_followUps['physical_address_zip']; ?>" target="mapwin">Link to map</a>
					</span>
				</div>
				<div class="row">
					<strong class="col-sm-3">Contact:</strong>
					<span class="col-sm-9"><?php echo $row_followUps['contact_first_name'] . ' ' . $row_followUps['contact_last_name']; ?></span>
				</div>
				<div class="row">
					<strong class="col-sm-3">Rep:</strong>
					<span class="col-sm-9"><?php echo $row_followUps['first_name']; ?> <?php echo $row_followUps['last_name']; ?> ( <?php echo $row_followUps['distributor']; ?>)</span>
				</div>

			</div>
		</div>
	</div>
	<hr />
		<?php } while ($row_followUps = mysql_fetch_assoc($followUps)); ?>
	<?php } //end if we have results
			else {
		?>
	<h4>No follow ups scheduled for you ... why not go check in with a <a href="../customers.php">customer</a>?</h4>
	<?php } //end else (no results ?>
	<?php include('includes/footer.php'); ?>
</div>
<?php include('includes/pageClose.php'); ?>
</body>
</html>
<?php
mysql_free_result($employees);

mysql_free_result($employee_id_for_session);

mysql_free_result($followUps);

?>