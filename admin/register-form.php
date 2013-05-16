<?php
	session_start();
?>
<!doctype html>
<html>

	<head>
		
	</head>

	<body lang="en">
	

		
<?php
	if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
		echo '<ul class="err">';
		foreach($_SESSION['ERRMSG_ARR'] as $msg) {
			echo '<li>',$msg,'</li>'; 
		}
		echo '</ul>';
		unset($_SESSION['ERRMSG_ARR']);
	}
?>

		<!-- Main -->
		<div class="userform">
			<p><h4>Registration Form</h4></p>
			<div id="landingform">
				<form id="loginForm" name="loginForm" method="post" action="register-exec.php">
				
					<p><label for="username">Full Name</label><br /><input name="Name" type="text" class="textfield" id="Name" autofocus/></p>
					<p><label>Email</label><br /><input name="Email" type="text" class="textfield" id="Email"  /></p>
					<p><label>Username</label><br /><input name="login" type="text" class="textfield" id="login" /></p>
					<p><label>Password</label><br /><input name="password" type="password" class="textfield" id="password" /></p>
					<p><label for="password">Confirm Password</label><br /><input name="cpassword" type="password" class="textfield" id="cpassword" /></p>
					<p><input type="submit" name="submit" value="Register"/></p>
					<p>
					<!-- Note -->
					<div class="forgot">
					<a href="login.php">Login</a> | <a href="forgotpassword.php">Forgot your password?</a>
					</div>
					
					</p>
				</form>
			</div>
		</div>

		<div class="divider"></div>
		<!-- Footer -->
		
	</body>
	
</html>
