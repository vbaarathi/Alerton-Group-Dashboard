<?php include ('includes/header_html.php') ;?>
<!doctype html>
<html>


	<body lang="en" id="loginbg">
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
	
		
	<div class="container">
		
		<!-- Header -->
		
		<div class="span-24">
			<H1>Wiring Diagram Generator</H1>
		</div>	
		
		<div class="usermenu">
			<a href="login.php">Login</a> | <a href="register-form.php">Register</a> | <a href="forgotpassword.php">Forgot password</a></a> 
		</div>
		
		<!-- Main -->
		
		<div class="span-24" id="loginform" >
			
			<p><h4 id="loginheader">LOGIN</h4></p>
			
			<form  name="loginForm" method="post" action="login-exec.php">
				<ul>
					<li><label for="login">Username</label> 
					<input name="login" type="text"  />
					</li>
					
					<li><label for="password">Password</label>
					<input name="password" type="password" />
					</li>
					
					<input type="submit" name="submit" value="LOGIN" />
				</ul>
				<p>
			
					<a href="register-form.php">Register</a> | <a href="forgotpassword.php">Forgot your password?</a>
						
				</p>
			
			</form>
			</div>
		</div>
		
		

		

	</div>	
	</body>
	
</html>