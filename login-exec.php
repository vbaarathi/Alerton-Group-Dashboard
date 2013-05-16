<?php
	
	//Start session
	session_start();
	
	//Include database connection details
	require_once('db.php');
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
		
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
	//Sanitize the POST values
	$login = clean($_POST['login']);
	$password = clean($_POST['password']);
	$i_ans = clean($_POST['i_ans']);
	$ans = clean($_POST['ans']);
	
	//Input Validations
	if($login == '') {
		$errmsg_arr = 'Login ID missing';
		$errflag = true;
	}
	if($password == '') {
		$errmsg_arr = 'Password missing';
		$errflag = true;
	}
	
	if($i_ans == '') {
		$errmsg_arr = 'Secret answer missing';
		$errflag = true;
	}
	
	if($i_ans != $ans){
		$errmsg_arr = 'Wrong secret answer';
		$errflag = true;
	}

	
	//If there are input validations, redirect back to the login form
	if($errflag) {
		//$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		//session_write_close();
		header("location: login.php?err=$errmsg_arr");
		exit();
	}
	
	//Create query
	$qry="SELECT * FROM users WHERE Login='$login' AND Password='".sha1($_POST['password'])."'";
	$result=mysql_query($qry);
		
	//Check whether the query was successful or not
	if($result) {
		if(mysql_num_rows($result) == 1) {
		
			//Login Successful
			session_regenerate_id();
			$member = mysql_fetch_assoc($result);
			
			$id = $member['id'];
			$time1 = $member['lockouttime'];
			$time2 = $member['unlocktime'];
			$time3 = date('D-m-Y H:i:s');
					
			$_SESSION['SESS_MEMBER_ID'] = $member['id'];
	
			session_write_close();
			
			if($member['Login']=="Admin"){
				header("location: fullscreen.php");
			}else header("location: index.php");
			exit();
		}	else {
		$errmsg_arr = "Wrong username or password. Please try again.";
		header("location: login.php?err=$errmsg_arr");}
	}
?>