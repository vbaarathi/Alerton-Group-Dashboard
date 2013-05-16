<?php 
	//Start session
	session_start();
		
	//Include Admin Auth
	include('adminauth.php');
	
	// Links
	include('links.php');

?>

<!doctype html>
<html>

	<head>
		<meta charset="utf-8"/>
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		
		<title>My Footprint Calculator</title>
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<script type="text/javascript" src="../js/jquery-min.js"></script>
		<link rel="stylesheet" media="all" href="../css/less.css"/>		
		<link href='http://fonts.googleapis.com/css?family=Copse' rel='stylesheet' type='text/css'>
		
		<!-- iPhone -->
		<link rel="icon" href="images/favicon.png" type="image/gif"/> 
		<link rel="apple-touch-icon-precomposed" href="images/apple.png"/>		
		<link rel="stylesheet" href="css/add2home.css">
		<script type="application/javascript" src="js/add2home.js"></script>

	</head>

	<body lang="en">
	
		<!-- Header -->
		<div class="head">
			<div id="logo"><a href="index.php"><img src="../images/logo.png" alt="" /></a></div>
			<div id="title">My Carbon Footprint</div>
			<span id="beta">Beta v1.0</span>
		</div>
		<div class="divider"></div>