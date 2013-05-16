<?php
	//Start session
	session_start();
	
	include('db.php');
	
	$querySelect = "SELECT Login FROM users WHERE id = '".$_SESSION['SESS_MEMBER_ID']."'";
	$resultSelect = mysql_query($querySelect);
	
	$row = mysql_fetch_array($resultSelect);
		
	// Inserts into logging database the login
			
		
	//Unset the variables stored in session
	unset($_SESSION['SESS_MEMBER_ID']);
?>
<!doctype html>
<html>


<body lang="en">
	

		
		<!-- Main -->
		<div class="userform" style="text-align:center">
			<p><h4>You have been successfully logged out</h4></p>
			<p>Click here to <a href="login.php">login</a> back.</p>
		</div>


		<!-- Footer -->
		
	</body>
	
</html>
