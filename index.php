<?php 
	include('db.php');
	require_once('login-auth.php');
	
	//header("Location:dashboard.php");
	//hello
	
?>

<!DOCTYPE html>
<html lang="en">
	<head>
	
	  <link rel="apple-touch-icon" href="/custom_icon.png"/>
  
	  
  <meta name="apple-mobile-web-app-capable" content="yes" />
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Dashboard. </title>
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.1.0/css/bootstrap.no-icons.min.css" />
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/2.0/css/font-awesome.css" />
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<style type="text/css">
			body {text-align: center; padding-top:100px} .main a {font-size: 10em; line-height: 1em} a:hover {text-decoration: none; color: black} footer {padding-bottom:50px}
		</style>
	</head>

	<body>
		
		<div class="container">
			<div class="row">
				<div class="span12">
					<h1>Dashboard.</h1>
					<p></p>
					<hr />
				</div>
			</div>
		</div>
		<div class="main">
			<div class="container">
				<div class="row">
					<div class="span3">
						<a href="dashboard.php" rel="tooltip" title="Projects Dashboard" target="_self"><i class="icon-folder-open"></i></a>
						<p>Projects</p>
					</div>

					<div class="span3">
						<a href="service.php" rel="tooltip" title="Service Dashboard" target="_self"><i class="icon-cog"></i></a>
						<p>Service</p>
					</div>

					<div class="span3">
						<a href="contracting.php" rel="tooltip" title="Contracting Dashboard" target="_self"><i class="icon-briefcase"></i></a>
						<p>Contracting</p>
					</div>
					
					<div class="span3">
						<a href="sales.php" rel="tooltip" title="Sales Dashboard" ><i class="icon-user"></i></a>
						<p>Sales</p>
					</div>					
					
				</div>
			</div>
		</div>
		<footer class="container">
			<div class="row">
				<div class="span12">
					<hr />
					&copy; 2013 - Alerton Australia
				</div>
			</div>
		</footer>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.1.0/bootstrap.min.js"></script>
		<script type="text/javascript">
			$(function(){
				$('a').attr('target','_self');
				$('.main a').tooltip();
			})
		</script>
	</body>

</html>
