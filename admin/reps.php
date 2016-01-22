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
  $insertSQL = sprintf("INSERT INTO distributor_reps (first_name, last_name, distributor) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['rep_first_name'], "text"),
                       GetSQLValueString($_POST['rep_last_name'], "text"),
                       GetSQLValueString($_POST['distributor'], "text"));

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
  $updateSQL = sprintf("UPDATE distributor_reps SET first_name=%s, last_name=%s, distributor=%s WHERE id=%s",
                       GetSQLValueString($_POST['rep_first_name'], "text"),
                       GetSQLValueString($_POST['rep_last_name'], "text"),
                       GetSQLValueString($_POST['distributor'], "text"),
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

mysql_select_db($database_strike, $strike);
$query_dbiReps = "SELECT * FROM distributor_reps";
$dbiReps = mysql_query($query_dbiReps, $strike) or die(mysql_error());
$row_dbiReps = mysql_fetch_assoc($dbiReps);
$totalRows_dbiReps = mysql_num_rows($dbiReps);

$colname_this_rep = "-1";
if (isset($_POST['id'])) {
  $colname_this_rep = $_POST['id'];
}
mysql_select_db($database_strike, $strike);
$query_this_rep = sprintf("SELECT * FROM distributor_reps WHERE id = %s", GetSQLValueString($colname_this_rep, "int"));
$this_rep = mysql_query($query_this_rep, $strike) or die(mysql_error());
$row_this_rep = mysql_fetch_assoc($this_rep);
$totalRows_this_rep = mysql_num_rows($this_rep);

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
	<h1>Add/Edit Distributor Reps</h1>
	<?php if(isset($_GET['edited'])){ ?>
	<div class="alert alert-success">
		<h2>Done</h2>
		<p>The rep has been edited</p>
	</div>
	<?php } ?>
	<?php if(isset($_GET['added'])){ ?>
	<div class="alert alert-success">
		<h2>Done</h2>
		<p>The rep has been added</p>
	</div>
	<?php } ?>
	
	<!-- Example row of columns -->
	<form role="form" method="post" action="/admin/reps.php" id="selectAndGoTarget">
		<fieldset>
			<legend>Select a rep to edit</legend>
			<div class="form-group">
			<select name="id" class="selectAndGoTrigger form-control">
				<option value="">Select a rep to edit</option>
				<?php
do {  
?>
				<option value="<?php echo $row_dbiReps['id']?>"><?php echo $row_dbiReps['first_name'] . ' ' . $row_dbiReps['last_name'] . ' (' . $row_dbiReps['distributor'] . ')';?></option>
				<?php
} while ($row_dbiReps = mysql_fetch_assoc($dbiReps));
  $rows = mysql_num_rows($dbiReps);
  if($rows > 0) {
      mysql_data_seek($dbiReps, 0);
	  $row_dbiReps = mysql_fetch_assoc($dbiReps);
  }
?>
			</select>
			</div>

		</fieldset>
	</form>
	<?php if(isset($row_this_rep['id'])){ //if we have a customer to edit ?>
	
<a class="btn btn-default btn-lg btn-block" href="/admin/reps.php">Add a new Rep</a>
	<h2>Edit </h2>
	<form action="<?php echo $editFormAction; ?>" method="POST" name="editItem" id="editItem">
		<input name="id" type="hidden" value="<?php echo $row_this_rep['id']; ?>"  />
		<fieldset>
			<legend>Rep name</legend>
			<div class="form-group">
				<label for="rep_first_name">First Name</label>
				<input class="form-control" name="rep_first_name" type="text" value="<?php echo $row_this_rep['first_name']; ?>" />
			</div>
			<div class="form-group">
				<label for="rep_last_name">Last Name</label>
				<input  class="form-control" name="rep_last_name" type="text" value="<?php echo $row_this_rep['last_name']; ?>" />
			</div>
			<div class="form-group">
				<label for="distributor">Distributor</label>
				<select class="form-control" name="distributor">
					<option value="" <?php if (!(strcmp("", $row_this_rep['distributor']))) {echo "selected=\"selected\"";} ?>>Please Choose</option>
					<?php
do {  
?>
					<option value="<?php echo $row_distributors['name']?>"<?php if (!(strcmp($row_distributors['name'], $row_this_rep['distributor']))) {echo "selected=\"selected\"";} ?>><?php echo $row_distributors['name']?></option>
					<?php
} while ($row_distributors = mysql_fetch_assoc($distributors));
  $rows = mysql_num_rows($distributors);
  if($rows > 0) {
      mysql_data_seek($distributors, 0);
	  $row_distributors = mysql_fetch_assoc($distributors);
  }
?>
				</select>
			</div>
		</fieldset>
		<button class="btn btn-primary">Submit change</button>
		<input type="hidden" name="MM_update" value="editItem">
	</form>
	<?php 
	} //end if we have an rep to edit
	else {// otherwise, present the 'add rep' form 
	?>
	<h2>Add a new Rep</h2>
	<form action="<?php echo $editFormAction; ?>" method="POST" name="addRep" id="addRep">
		<fieldset>
			<legend>Rep name</legend>
			<div class="form-group">
				<label for="rep_first_name">First Name</label>
				<input type="text" class="form-control" name="rep_first_name" value="" required />
			</div>
			<div class="form-group">
				<label for="rep_last_name">Last Name</label>
				<input type="text" class="form-control" name="rep_last_name" value="" required />
			</div>
			<div class="form-group">
				<label for="distributor">Distributor</label>
				<select name="distributor" class="form-control" required>
					<option value="">Please Choose</option>
					<?php
do {  
?>
					<option value="<?php echo $row_distributors['name']?>"><?php echo $row_distributors['name']?></option>
					<?php
} while ($row_distributors = mysql_fetch_assoc($distributors));
  $rows = mysql_num_rows($distributors);
  if($rows > 0) {
      mysql_data_seek($distributors, 0);
	  $row_distributors = mysql_fetch_assoc($distributors);
  }
?>
				</select>
			</div>
			<div class="form-group">
				<button class="btn btn-primary">Add Distributor Rep</button>
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
mysql_free_result($dbiReps);

mysql_free_result($this_rep);

mysql_free_result($distributors);
?>
