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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addRecipe")) {
  $insertSQL = sprintf("INSERT INTO recipes (name) VALUES (%s)",
                       GetSQLValueString($_POST['name'], "text"));

  mysql_select_db($database_strike, $strike);
  $Result1 = mysql_query($insertSQL, $strike) or die(mysql_error());

  $insertGoTo = "/admin/recipes.php?added=true";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editRecipe")) {
	$updateSQL = sprintf("UPDATE recipes SET name=%s WHERE id=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['id'], "int"));
  mysql_select_db($database_strike, $strike);
  $Result1 = mysql_query($updateSQL, $strike) or die(mysql_error());

  $updateGoTo = "/admin/recipes.php?edited=true";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_strike, $strike);
$query_recipes = "SELECT * FROM recipes";
$recipes = mysql_query($query_recipes, $strike) or die(mysql_error());
$row_recipes = mysql_fetch_assoc($recipes);
$totalRows_recipes = mysql_num_rows($recipes);

$colname_this_recipe = "-1";
if (isset($_POST['id'])) {
  $colname_this_recipe = $_POST['id'];
}
mysql_select_db($database_strike, $strike);
$query_this_recipe = sprintf("SELECT * FROM recipes WHERE id = %s", GetSQLValueString($colname_this_recipe, "int"));
$this_recipe = mysql_query($query_this_recipe, $strike) or die(mysql_error());
$row_this_recipe = mysql_fetch_assoc($this_recipe);
$totalRows_this_recipe = mysql_num_rows($this_recipe);
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
	<h1>Add/Edit Recipes (Styles)</h1>
	<?php if(isset($_GET['edited'])){ ?>
	<div class="alert alert-success">
		<h2>Done</h2>
		<p>The recipe has been edited</p>
	</div>
	<?php } ?>
	<?php if(isset($_GET['added'])){ ?>
	<div class="alert alert-success">
		<h2>Done</h2>
		<p>The recipe has been added</p>
	</div>
	<?php } ?>
	
	<!-- Example row of columns -->
	<form role="form" method="post" action="/admin/recipes.php" id="selectAndGoTarget">
		<fieldset>
			<legend>Select a recipe to edit</legend>
			<div class="form-group">
					<select name="id" class="selectAndGoTrigger form-control">
						<option value="">Select a recipe to edit</option>
						

				<?php do { ?>
						<option value="<?php echo $row_recipes['id']?>"><?php echo $row_recipes['name'];?></option>
					<?php } while ($row_recipes = mysql_fetch_assoc($recipes)); ?>
					</select>
			</div>

		</fieldset>
	</form>
	<?php if(isset($row_this_recipe['id'])){ //if we have a customer to edit ?>
	
<a class="btn btn-default btn-lg btn-block" href="/admin/recipe.php">Add a new Recipe</a>
	<h2>Edit </h2>
	<form action="<?php echo $editFormAction; ?>" method="POST" name="editRecipe" id="editRecipe">
		<input name="id" type="hidden" value="<?php echo $row_this_recipe['id']; ?>"  />
		<fieldset>
			<legend>Recipe</legend>
			<div class="form-group">
				<label for="name">Recipe Name</label>
				<input class="form-control" name="name" type="text" value="<?php echo $row_this_recipe['name']; ?>" />
			</div>
		</fieldset>
		<button class="btn btn-primary">Submit change</button>
		<input type="hidden" name="MM_update" value="editRecipe">
	</form>
	<?php 
	} //end if we have an rep to edit
	else {// otherwise, present the 'add rep' form 
	?>
	<h2>Add a new Recipe</h2>
	<form action="<?php echo $editFormAction; ?>" method="POST" name="addRecipe" id="addRecipe">
		<fieldset>
			<legend>Size</legend>
			<div class="form-group">
				<label for="name">Recipe Name</label>
				<input type="text" class="form-control" name="name" value="" required />
			</div>
			<div class="form-group">
				<button class="btn btn-primary">Add Recipe</button>
			</div>
		</fieldset>
		<input type="hidden" name="MM_insert" value="addRecipe">
	</form>
	<?php } //end add new size form ?>
	<?php include('../includes/footer.php'); ?>
</div>
<!-- /container --> 

	<?php include('../includes/pageClose.php'); ?>

</body>
</html>
<?php
mysql_free_result($recipes);

mysql_free_result($this_recipe);
?>
