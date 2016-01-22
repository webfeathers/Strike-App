<?php
// *** Logout the current user.
$logoutGoTo = "";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['MM_Username'] = NULL;
$_SESSION['MM_UserGroup'] = NULL;
unset($_SESSION['MM_Username']);
unset($_SESSION['MM_UserGroup']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>
<?php
	require('includes/pagetop.php');
?>
<?php
		$selectedHeaderTab = "login";
		include('includes/header.php');
	?>
<div class="container"> 	
	<!-- Main hero unit for a primary marketing message or call to action -->
	
	<div class="row">
		<div class="span12">
			<div class="well">
				<form METHOD="POST" name="login">
					<input type="text" name="username" placeholder="username">
					<input type="password" name="password" placeholder="password">
					<button type="submit">Log In</button>
				</form>
			</div>
		</div>
	</div>
		
	
	<?php include('includes/footer.php'); ?>
</div>
<!-- /container --> 

<script src="http://code.jquery.com/jquery-latest.js"></script> 
<script src="js/bootstrap.min.js"></script>

<script>
	localStorage.clear();
	location.href="/index.php?loggedout=true";
</script>

</body>
</html>