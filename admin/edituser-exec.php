<?php
	//include header
	include('header.php');

	
	include('../include/config.php');

	$id = $_POST['id'];
	$name = $_POST['Name'];
	$login = $_POST['Login'];
	$email = $_POST['Email'];
	
	if(!isset($_POST['banned'])) {
	$banned = '0';
	} else {
	$banned = '1';
	}
	
	if(!isset($_POST['admin'])) {
	$admin = '0';
	} else {
	$admin = '1';
	}
	
	if(!isset($_POST['delete'])) {

	// setup MySQL query 
    $query = "UPDATE users SET Name = '$name', Login = '$login' , Email = '$email', admin = '$admin', banned = '$banned' WHERE id = '$id'";

	// Execute Query 
    $result = mysql_query($query) or die( "An error has ocured: " .mysql_error (). ":" .mysql_errno ());
	echo "<br /><br /><br />Database updated. <a href='index.php'>Return to edit info</a><br /><br />";
	
	} else {
		
	// setup MySQL query 
    $query = "DELETE users FROM users WHERE id = '$id'";
	
	// Execute Query 
    $result = mysql_query($query) or die( "An error has ocured: " .mysql_error (). ":" .mysql_errno ());
	echo "<br /><br /><br />User has been deleted. <a href='edituser.php'>Return to edit info</a><br /><br />";
	
	}	

?>

<?php include('../footer.php'); ?>