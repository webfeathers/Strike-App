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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addRep")) {
  $insertSQL = sprintf("INSERT INTO distributors (name) VALUES (%s)",
                       GetSQLValueString($_POST['name'], "text"));

  mysql_select_db($database_strike, $strike);
  $Result1 = mysql_query($insertSQL, $strike) or die(mysql_error());

  $insertGoTo = "?added=true";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editItem")) {
  $updateSQL = sprintf("UPDATE distributors SET name=%s WHERE id=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_strike, $strike);
  $Result1 = mysql_query($updateSQL, $strike) or die(mysql_error());

  $updateGoTo = "?updated=true";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_this_distributor = "-1";
if (isset($_POST['id'])) {
  $colname_this_distributor = $_POST['id'];
}
mysql_select_db($database_strike, $strike);
$query_this_distributor = sprintf("SELECT * FROM distributors WHERE id = %s", GetSQLValueString($colname_this_distributor, "int"));
$this_distributor = mysql_query($query_this_distributor, $strike) or die(mysql_error());
$row_this_distributor = mysql_fetch_assoc($this_distributor);
$totalRows_this_distributor = "-1";
if (isset($_POST['id'])) {
  $totalRows_this_distributor = $_POST['id'];
}
mysql_select_db($database_strike, $strike);
$query_this_distributor = sprintf("SELECT * FROM distributors WHERE id = %s", GetSQLValueString($colname_this_distributor, "int"));
$this_distributor = mysql_query($query_this_distributor, $strike) or die(mysql_error());
$row_this_distributor = mysql_fetch_assoc($this_distributor);
$totalRows_this_distributor = mysql_num_rows($this_distributor);

mysql_select_db($database_strike, $strike);
$query_distributors = "SELECT * FROM distributors";
$distributors = mysql_query($query_distributors, $strike) or die(mysql_error());
$row_distributors = mysql_fetch_assoc($distributors);
$totalRows_distributors = mysql_num_rows($distributors);

?>
<?php
	require('../includes/pagetop.php');
?>
<?php
	$selectedHeaderTab = "admin";
	include('../includes/header.php');
?>
<div class="container">
	<h1>Add/Edit Distributor</h1>
	<?php if(isset($_GET['edited'])){ ?>
	<div class="alert alert-success">
		<h2>Done</h2>
		<p>The distributor has been edited</p>
	</div>
	<?php } ?>
	<?php if(isset($_GET['added'])){ ?>
	<div class="alert alert-success">
		<h2>Done</h2>
		<p>The distributor has been added</p>
	</div>
	<?php } ?>
	
	<!-- Example row of columns -->
	<form role="form" method="post" action="/admin/distributors.php" id="selectAndGoTarget">
		<fieldset>
			<legend>Select a distributor to edit</legend>
			<div class="form-group">
			<select name="id" class="selectAndGoTrigger form-control">
				<option value="">Select a distributor to edit</option>
				<?php
do {  
?>
				<option value="<?php echo $row_distributors['id']?>"><?php echo $row_distributors['name']; ?></option>
				<?php
} while ($row_distributors = mysql_fetch_assoc($distributors));
  $rows = mysql_num_rows($distributors);
  if($rows > 0) {
      mysql_data_seek($distributors, 0);
	  $row_dbiReps = mysql_fetch_assoc($distributors);
  }
?>
			</select>
			</div>

		</fieldset>
	</form>
	<?php if(isset($row_this_distributor['id'])){ //if we have a customer to edit ?>
	
<a class="btn btn-default btn-lg btn-block" href="/admin/distributors.php">Add a new Rep</a>
	<h2>Edit </h2>
	<form action="<?php echo $editFormAction; ?>" method="POST" name="editItem" id="editItem">
		<input name="id" type="hidden" value="<?php echo $row_this_distributor['id']; ?>"  />
		<fieldset>
			<legend>Distributor name</legend>
			<div class="form-group">
				<label for="rep_last_name">Distributor</label>
				<input  class="form-control" name="name" type="text" value="<?php echo $row_this_distributor['name']; ?>" />
			</div>
		</fieldset>
		<button class="btn btn-primary">Submit change</button>
		<input type="hidden" name="MM_update" value="editItem">
	</form>
	<?php 
	} //end if we have an rep to edit
	else {// otherwise, present the 'add rep' form 
	?>
	<h2>Add a new Distributor</h2>
	<form action="<?php echo $editFormAction; ?>" method="POST" name="addRep" id="addRep">
		<fieldset>
			<legend>Name</legend>
			<div class="form-group">
				<label for="rep_first_name">Distributor name</label>
				<input type="text" class="form-control" name="name" value="" required />
			</div>
			<div class="form-group">
				<button class="btn btn-primary">Add Distributor</button>
			</div>
		</fieldset>
		<input type="hidden" name="MM_insert" value="addRep">
	</form>
	<?php } //end add new rep form ?>
	<?php include('../includes/footer.php'); ?>
</div>
<!-- /container --> 

	<?php include('../includes/pageClose.php'); ?>

</body>
</html>
<?php
mysql_free_result($this_distributor);

mysql_free_result($distributors);
?>
