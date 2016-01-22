<?php require_once('../includes/security_check.php'); ?>

<?php
	require('../includes/pagetop.php');
?>
<?php
	$selectedHeaderTab = "admin";
	include('../includes/header.php');
?>
<div class="container">

	<?php if(isset($_GET['loginsuccess'])){ ?>
		<div class="alert alert-success">
			<h3>Success!</h3>
			<p>You are logged in</p>
		</div>
	<?php } ?>
	
	<!-- Example row of columns -->
	<div class="row">
		<div class="col-md-4">
			<h2>Employees</h2>
			<p>Manage employee names here</p>
			<p><a class="btn btn-default" href="/admin/employees.php">Manage &raquo;</a></p>
		</div>
		<div class="col-md-4">
			<h2>Distributors</h2>
			<p>Manage Distributors</p>
			<p><a class="btn btn-default" href="/admin/distributors.php">Manage &raquo;</a></p>
		</div>
		<div class="col-md-4">
			<h2>Distributor Reps</h2>
			<p>Manage Reps</p>
			<p><a class="btn btn-default" href="/admin/reps.php">Manage &raquo;</a></p>
		</div>
		<div class="col-md-4">
			<h2>Check-Ins</h2>
			<p>See recent check-ins</p>
			<p><a class="btn btn-default" href="/admin/checkins.php">Manage &raquo;</a></p>
		</div>
		<div class="col-md-4">
			<h2>Follow Ups</h2>
			<p>See pending Follow-Ups</p>
			<p><a class="btn btn-default" href="/admin/follow-ups.php">Manage &raquo;</a></p>
		</div>
		<div class="col-md-4">
			<h2>Sizes</h2>
			<p>Manage container sizes</p>
			<p><a class="btn btn-default" href="/admin/sizes.php">Manage &raquo;</a></p>
		</div>
		<div class="col-md-4">
			<h2>Recipes</h2>
			<p>Manage Recipes tracked</p>
			<p><a class="btn btn-default" href="/admin/recipes.php">Manage &raquo;</a></p>
		</div>
	</div>
	<?php include('../includes/footer.php'); ?>
</div>
<!-- /container --> 

<script src="http://code.jquery.com/jquery-latest.js"></script> 
<script src="/js/bootstrap.min.js"></script>


</body>
</html>