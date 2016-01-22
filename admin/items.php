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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addItem")) {
  $insertSQL = sprintf("INSERT INTO item_types (type) VALUES (%s)",
                       GetSQLValueString($_POST['item_name'], "text"));

  mysql_select_db($database_strike, $strike);
  $Result1 = mysql_query($insertSQL, $strike) or die(mysql_error());

  $insertGoTo = "/admin/items.php?added=true";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editItem")) {
  $updateSQL = sprintf("UPDATE item_types SET type=%s WHERE id=%s",
                       GetSQLValueString($_POST['item_name'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_strike, $strike);
  $Result1 = mysql_query($updateSQL, $strike) or die(mysql_error());

  $updateGoTo = "/admin/items.php?edited=true";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_strike, $strike);
$query_items = "SELECT * FROM item_types";
$items = mysql_query($query_items, $strike) or die(mysql_error());
$row_items = mysql_fetch_assoc($items);
$totalRows_items = mysql_num_rows($items);

$colname_item = "-1";
if (isset($_POST['item_id'])) {
  $colname_item = $_POST['item_id'];
}
mysql_select_db($database_strike, $strike);
$query_item = sprintf("SELECT * FROM item_types WHERE id = %s", GetSQLValueString($colname_item, "int"));
$item = mysql_query($query_item, $strike) or die(mysql_error());
$row_item = mysql_fetch_assoc($item);
$totalRows_item = mysql_num_rows($item);
?>
<?php
	require('../includes/pagetop.php');
?>
<?php
	$selectedHeaderTab = "admin";
	include('../includes/header.php');
?>
<div class="container">
	<h1>Add/Edit Items</h1>
	<?php if(isset($_GET['edited'])){ ?>
		<div class="alert alert-success">
			<h2>Done</h2>
			<p>The item has been edited</p>
		</div>
	<?php } ?>
	<?php if(isset($_GET['added'])){ ?>
		<div class="alert alert-success">
			<h2>Done</h2>
			<p>The item has been added</p>
		</div>
	<?php } ?>

	<!-- Example row of columns -->
	<div class="row">
		<div class="span12">
			<p>If there is a new item type to track, add it here or edit existing ones</p>
			<form method="post" action="/admin/items.php" id="selectAndGoTarget">
				<fieldset>
					<legend>Select an item to edit</legend>
					<div class="row-fluid">
					<div class="span4">
						<select name="item_id" class="selectAndGoTrigger">
							<option>Select an item to edit</option>
							<?php do { ?>
							<option value="<?php echo $row_items['id']; ?>"><?php echo $row_items['type']; ?></option>
							<?php } while ($row_items = mysql_fetch_assoc($items)); ?>
						</select>
					</div>
					<div class="span4"> <a class="btn btn-link" href="/admin/items.php">or add a new item</a> </div>
				</fieldset>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<?php if(isset($row_item['id'])){ //if we have a customer to edit ?>
			<h2>Edit </h2>
			<form action="<?php echo $editFormAction; ?>" method="POST" name="editItem" id="editItem">
				<div class="span4">
					<fieldset>
						<input type="text" name="item_name" value="<?php echo $row_item['type']; ?>" />
						<input type="hidden" name="id" value="<?php echo $row_item['id']; ?>" />
					</fieldset>
				</div>
				<button>Submit change</button>
				<input type="hidden" name="MM_update" value="editItem">
			</form>
			<?php 
	} //end if we have an item to edit
	else {// otherwise, present the 'add item' form 
	?>
			<h2>Add a new item</h2>
			<form method="POST" action="<?php echo $editFormAction; ?>" name="addItem" id="addItem">
				<input type="text" name="item_name" />
				<button>Add item</button>
				<input type="hidden" name="MM_insert" value="addItem">
			</form>
			<?php } //end add new item form ?>
		</div>
	</div>
	<?php include('../includes/footer.php'); ?>
</div>
<!-- /container --> 

<script src="http://code.jquery.com/jquery-latest.js"></script> 
<script src="/js/bootstrap.min.js"></script> 
<script src="/js/strike-kegs.js"></script>
</body>
</html>
<?php
mysql_free_result($items);

mysql_free_result($item);
?>
