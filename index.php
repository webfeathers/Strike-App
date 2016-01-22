<?php require_once('Connections/strike.php'); ?>
<?php require_once('includes/security_check.php'); ?>

<?php require_once('includes/pagetop.php'); ?>
<?php
		$selectedHeaderTab = "home";
		include('includes/header.php');
?>

<!-- Main hero unit for a primary marketing message or call to action -->
<!--<div class="jumbotron">
	<h1>Strike CRM App</h1>
	<p>Home page for the Strike app... more to come.</p>
</div> -->
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
			<h2>Check In</h2>
			<p>Upon visiting a customer, comment on your visit</p>
			<p><a class="btn btn-default" href="customer-check-in.php">Check in &raquo;</a></p>
		</div>
		<div class="col-md-4">
			<h2>Customers</h2>
			<p>Add new customers or Edit or Delete existing ones</p>
			<p><a class="btn btn-default" href="customers.php">Manage &raquo;</a></p>
		</div>
		<div class="col-md-4">
			<h2>Follow Up</h2>
			<p>Check the customers you need to follow up with</p>
			<p><a class="btn btn-default" href="follow-ups.php">Follow up&raquo;</a></p>
		</div>
	</div>
	<?php include('includes/footer.php'); ?>
</div>
<!-- /container -->

<?php include('includes/pageClose.php'); ?>
</body>
</html>