<?php require_once('../Connections/strike.php'); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addSize")) {
  $insertSQL = sprintf("INSERT INTO sizes (name, abbr) VALUES (%s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['abbr'], "text"));

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
  $updateSQL = sprintf("UPDATE sizes SET name=%s, abbr=%s WHERE id=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['abbr'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_strike, $strike);
  $Result1 = mysql_query($updateSQL, $strike) or die(mysql_error());

  $updateGoTo = "?edited=true";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_strike, $strike);
$query_sizes = "SELECT * FROM sizes";
$sizes = mysql_query($query_sizes, $strike) or die(mysql_error());
$row_sizes = mysql_fetch_assoc($sizes);
$totalRows_sizes = mysql_num_rows($sizes);

$colname_this_size = "-1";
if (isset($_POST['id'])) {
  $colname_this_size = $_POST['id'];
}
mysql_select_db($database_strike, $strike);
$query_this_size = sprintf("SELECT * FROM sizes WHERE id = %s", GetSQLValueString($colname_this_size, "int"));
$this_size = mysql_query($query_this_size, $strike) or die(mysql_error());
$row_this_size = mysql_fetch_assoc($this_size);
$totalRows_this_size = mysql_num_rows($this_size);
?>
<?php require_once('../includes/security_check.php'); ?>

<?php
	require('../includes/pagetop.php');
?>
<?php
	$selectedHeaderTab = "admin";
	include('../includes/header.php');
?>
<div class="container">
	<h1>Add/Edit Sizes</h1>
	<?php if(isset($_GET['edited'])){ ?>
	<div class="alert alert-success">
		<h2>Done</h2>
		<p>The size has been edited</p>
	</div>
	<?php } ?>
	<?php if(isset($_GET['added'])){ ?>
	<div class="alert alert-success">
		<h2>Done</h2>
		<p>The size has been added</p>
	</div>
	<?php } ?>
	
	<!-- Example row of columns -->
	<form role="form" method="post" action="/admin/sizes.php" id="selectAndGoTarget">
		<fieldset>
			<legend>Select a size to edit</legend>
			<div class="form-group">
			<select name="id" class="selectAndGoTrigger form-control">
				<option value="">Select a size to edit</option>
				<?php
do {  
?>
				<option value="<?php echo $row_sizes['id']?>"><?php echo $row_sizes['name'] . ' (' . $row_sizes['abbr'] . ')';?></option>
				<?php
} while ($row_sizes = mysql_fetch_assoc($sizes));
  $rows = mysql_num_rows($sizes);
  if($rows > 0) {
      mysql_data_seek($sizes, 0);
	  $row_sizes = mysql_fetch_assoc($sizes);
  }
?>
			</select>
			</div>

		</fieldset>
	</form>
	<?php if(isset($row_this_size['id'])){ //if we have a customer to edit ?>
	
<a class="btn btn-default btn-lg btn-block" href="/admin/reps.php">Add a new Size</a>
	<h2>Edit </h2>
	<form action="<?php echo $editFormAction; ?>" method="POST" name="editItem" id="editItem">
		<input name="id" type="hidden" value="<?php echo $row_this_size['id']; ?>"  />
		<fieldset>
			<legend>Size</legend>
			<div class="form-group">
				<label for="name">Size Name</label>
				<input class="form-control" name="name" type="text" value="<?php echo $row_this_size['name']; ?>" />
			</div>
			<div class="form-group">
				<label for="abbr">Abbreviation (Abbr.)</label>
				<input  class="form-control" name="abbr" type="text" value="<?php echo $row_this_size['abbr']; ?>" />
			</div>
		</fieldset>
		<button class="btn btn-primary">Submit change</button>
		<input type="hidden" name="MM_update" value="editItem">
		<input type="hidden" name="MM_insert" value="editItem">
	</form>
	<?php 
	} //end if we have an rep to edit
	else {// otherwise, present the 'add rep' form 
	?>
	<h2>Add a new Size</h2>
	<form action="<?php echo $editFormAction; ?>" method="POST" name="addSize" id="addSize">
		<fieldset>
			<legend>Size</legend>
			<div class="form-group">
				<label for="name">Size Name</label>
				<input type="text" class="form-control" name="name" value="" required />
			</div>
			<div class="form-group">
				<label for="abbr">Abbreviation (abbr.)</label>
				<input type="text" class="form-control" name="abbr" value="" required />
			</div>
			<div class="form-group">
				<button class="btn btn-primary">Add Size</button>
			</div>
		</fieldset>
		<input type="hidden" name="MM_insert" value="addSize">
	</form>
	<?php } //end add new size form ?>
	<?php include('../includes/footer.php'); ?>
</div>
<!-- /container --> 

	<?php include('../includes/pageClose.php'); ?>

</body>
</html>
<?php
mysql_free_result($sizes);

mysql_free_result($this_size);
?>
