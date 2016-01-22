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

mysql_select_db($database_strike, $strike);
$WHERE = '';
if(isset($_REQUEST['checkins_by_employee'])){
	$WHERE = ' WHERE t1.employee_id = ' . $_REQUEST['checkins_by_employee'];
}
$query_checkins = "SELECT t1.*, t2.business_name, t3.state, t4.emp_first_name FROM check_ins t1 INNER JOIN customer t2 ON t2.customer_id = t1.customer_id INNER JOIN customer_states t3 ON t3.id = t1.customer_state LEFT JOIN employee t4     ON t4.id = t1.employee_id " . $WHERE . " ORDER BY t1.date DESC "  ;
$checkins = mysql_query($query_checkins, $strike) or die(mysql_error());
$row_checkins = mysql_fetch_assoc($checkins);
$totalRows_checkins = mysql_num_rows($checkins);

mysql_select_db($database_strike, $strike);
$query_employees = "SELECT id, emp_first_name, emp_last_name FROM employee";
$employees = mysql_query($query_employees, $strike) or die(mysql_error());
$row_employees = mysql_fetch_assoc($employees);
$totalRows_employees = mysql_num_rows($employees);

mysql_select_db($database_strike, $strike);
$query_recipes = "SELECT * FROM recipes";
$recipes = mysql_query($query_recipes, $strike) or die(mysql_error());
$row_recipes = mysql_fetch_assoc($recipes);
$totalRows_recipes = mysql_num_rows($recipes);

mysql_select_db($database_strike, $strike);
$query_sizes = "SELECT * FROM sizes";
$sizes = mysql_query($query_sizes, $strike) or die(mysql_error());
$row_sizes = mysql_fetch_assoc($sizes);
$totalRows_sizes = mysql_num_rows($sizes);

?>
<?php
	require('../includes/pagetop.php');
?>
<?php
	$selectedHeaderTab = "admin";
	include('../includes/header.php');
?>
<div class="container">
	<h1>Check-ins</h1>
	<h2>Recent Checkins </h2>
	<form class="form-inline" method="post" >
		<select name="checkins_by_employee" id="checkins_by_employee">
				<?php
do {  
?>
			<option value="<?php echo $row_employees['id']?>"><?php echo $row_employees['emp_first_name']?></option>
			<?php
} while ($row_employees = mysql_fetch_assoc($employees));
  $rows = mysql_num_rows($employees);
  if($rows > 0) {
      mysql_data_seek($employees, 0);
	  $row_employees = mysql_fetch_assoc($employees);
  }
?>
		</select>
		<button type="submit" class="btn btn-primary">Go</button>
	</form>
	<ul>
		<?php do { ?>
			<li> <strong><?php echo $row_checkins['emp_first_name']; ?></strong> checked in with <strong><?php echo $row_checkins['business_name']; ?></strong> on <?php echo $row_checkins['date']; ?>
				<?php
					//if has recips selection
					if($row_checkins['relevant_recipe_size']){
				?>
				<div> Interested in:
					
					<?php do { ?>
					<?php 
						if($row_checkins['relevant_recipe_size'] == $row_sizes['id']){
							echo $row_sizes['name'];
						} ?>
					<?php } while ($row_sizes = mysql_fetch_assoc($sizes)); ?>
					of
					<?php do { ?>
					<?php 
						if($row_checkins['relevant_recipe_size'] == $row_recipes['id']){
							echo $row_recipes['name'];
						} ?>
					<?php } while ($row_recipes = mysql_fetch_assoc($recipes)); ?>
				</div>
				<?php } //end if has recipe selection ?>
			
				<div><?php echo $row_checkins['comment']; ?></div>
				<?php
							if(isset($row_checkins['emp_first_name'])){
						?>
				<div>Follow up on <?php echo $row_checkins['next_step_date']; ?> 
					
					<!-- <a href="webcal://app.strikebrewingco.com/tools/setReminder.php?id=<?php echo $row_checkins['id']; ?>"><i class="icon-calendar"></i> add to calendar</a>
							--> 
				</div>
				<?php } ?>
			</li>
			<?php } while ($row_checkins = mysql_fetch_assoc($checkins)); ?>
	</ul>
<?php include('../includes/footer.php'); ?>
</div>
<?php include('../includes/pageClose.php'); ?>


</body>
</html>
<?php
mysql_free_result($checkins);

mysql_free_result($employees);

mysql_free_result($recipes);

mysql_free_result($sizes);

?>
