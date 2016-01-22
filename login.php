<?php require_once('Connections/strike.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "role";
  $MM_redirectLoginSuccess = "index.php?loginsuccess";
  $MM_redirectLoginFailed = "login.php?loginfail";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_strike, $strike);
  	
  $LoginRS__query=sprintf("SELECT username, password, role FROM employee WHERE username=%s AND password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $strike) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'role');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
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
	<?php if(isset($_GET['loggedout'])){ ?>
	<div class="alert">
		<h3>Logged out</h3>
		<p>You have been successfully logged out. (you may log in again below)</p>
	</div>
	<?php } ?>
	<?php if(isset($_GET['loginfail'])){ ?>
	<div class="alert alert-error">
		<h3>Oops!</h3>
		<p>That login didn't work. Try again?</p>
	</div>
	<?php } ?>
	<form role="form" ACTION="<?php echo $loginFormAction; ?>" METHOD="POST" name="login" id="loginForm" onSubmit="return setRememberMe()">
		<h2>Log in</h2>
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" class="form-control" name="username" id="username" placeholder="username">
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password"class="form-control"  name="password" id="password" placeholder="password">
		</div>
		<div class="checkbox">
			<label for="rememberme">Remember Me
				<input type="checkbox"  name="rememberme" id="rememberme" value="yes">
			</label>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary">Log In</button>
		</div>
	</form>
	<?php include('includes/footer.php'); ?>
</div>
<!-- /container --> 

<script src="http://code.jquery.com/jquery-latest.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script>
	function setRememberMe(){
		var rememberMe = $('#rememberme:checked').val(),
			userName = $('#username').val(),
			password = $('#password').val();
		if(rememberMe === "yes"){
			localStorage.setItem("username", userName);
			localStorage.setItem("password", password);
		}
	}
	if(localStorage.getItem("username") !== '' && localStorage.getItem("username") !== 'undefined' && localStorage.getItem("username") !== null){
			$('#username').val(localStorage.getItem("username"));
		$('#password').val(localStorage.getItem("password"));
		$('#loginForm').submit();
	}
</script>
</body>
</html>