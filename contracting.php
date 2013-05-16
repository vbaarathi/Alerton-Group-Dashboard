<?php 	
		/* CONFIGURATION */
		require "db.php";
		require_once('login-auth.php');
		require "function.php";
		require "filtered_function.php";
		$dashboard_table = "filtered";
		
		
		/* CODE TO CHECK IF NEW DATE EXISTS FOR DATABASE UPDATE*/
		check_date();
		
		
		/* GET DAATE RANGE */
		$stop_date = date("F Y");
		$start_date_six = get_start_date($stop_date,6);
		$start_date_twelve = get_start_date($stop_date,12);
		
		//******************************************************
		
		/* CODE TO DETERMINE IF PAGE SHOULD FILTER BY TEAM OR INDIVIDUAL*/
		
		if(isset($_GET['fil'])){
			
			$team_filter = "dt.branch";
			$local_path = "".$_SERVER['PHP_SELF']."?fil=team&";
		}else {
			$team_filter = "dt.manager";
			$local_path = "".$_SERVER['PHP_SELF']."?";
			}
			
		
		/* CODE TO DETERMINE DURATION SELECTION BASED ON INFORMATION PASSED VIA URL*/
		if(isset($_GET['dur']) || !empty($_GET['dur'])){
			$duration = $_GET['dur'];
		
			if ($duration == 12){
				$dur_filter = "".$dashboard_table."_data_twelve";
				$title = "Summary from $start_date_twelve to $stop_date (12 months)";
				}else if ($duration == 6){
					$dur_filter = "".$dashboard_table."_data_six";
					$title = "Summary from $start_date_six to $stop_date (6 months)";
					}else if ($duration == 0){
						$dur_filter = "".$dashboard_table."_data";
						$title	= "CONTROL DATA FROM 1 JULY 2012 TO 31 DECEMBER 2012";
					}

			
		}else {	$duration = 6;
				$dur_filter = "".$dashboard_table."_data_six";
				$title = "Summary from $start_date_six to $stop_date (6 months)";}
				
		//********************************************************

		
?>
<!DOCTYPE html>
<html>
  <head>
  <title>Alerton Contracting Dashboard</title>
  
  <link rel="apple-touch-icon" href="/custom_icon.png"/>
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="apple-mobile-web-app-capable" content="yes" />
    
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" media="screen">
  </head>
  <body>
  
  	<div class="container-fluid">
  		<div class="row-fluid header">
  			<div class="span10"><h2>Alerton Contracting Dashboard</h2></div>
  			<div class="span2">
  			<div class="btn-group top-drop pull-right">
							  	<a class="btn btn-small dropdown-toggle" data-toggle="dropdown" href="#">
								  	Menu
								  	<span class="caret"></span>
								  	</a>
								  	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
									  	<li><a tabindex="-1" href="index.php">Home</a></li>
									  	<li><a tabindex="-1" href="dashboard.php">Projects</a></li>
									  	<li><a tabindex="-1" href="service.php">Service</a></li>
									  	<li><a tabindex="-1" href="contracting.php">Contracting</a></li>
									  	<li><a tabindex="-1" href="sales.php">Sales</a></li>
									  	
									  	<li class="divider"></li>
									  	<li><a tabindex="-1" href="logout.php">Log Out</a></li> 
									 </ul>
							</div>
  			<!--<i class="icon-print icon-arrangement"></i><a href="fullscreen.php" rel="tooltip" title="Launch Full Screen">
  			<i class="icon-fullscreen icon-arrangement"></i></a>
  			<a href="logout.php" rel="tooltip" title="Logout">
  			<i class="icon-off"></i></a>-->
  			</div>
  			
  		</div>
  		<div class="row-fluid">
  			<div class="navbar">
	  			<div class="navbar-inner">
		  			<div class="container">
 
			  			<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
			  			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				  			<ul class="nav">
					  			<li>
						  			<a href="dashboard.php">All</a>
						  		</li>
						  		<li><a href="dashboard.php?fil=team">Team</a></li>
						  		
						  		
						  		
						  	</ul>
						  	
						  	<div class="btn-group pull-right">
							  	<a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
								  	Duration Filter
								  	<span class="caret"></span>
								  	</a>
								  	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
									  	<li><a tabindex="-1" href="<?php echo $local_path;?>dur=6">6 Months</a></li>
									  	<li><a tabindex="-1" href="<?php echo $local_path;?>dur=12">12 Months</a></li>
									  	
									  	<li class="divider"></li>
									  	<li><a tabindex="-1" href="<?php echo $local_path;?>dur=0">Control Data</a></li> 
									 </ul>
							</div>
						 </a>
 
 
 
						 <!-- Everything you want hidden at 940px or less, place within here -->
						 <div class="nav-collapse collapse">
							 <!-- .nav, .navbar-search, .navbar-form, etc -->
						</div>
 
					</div>
				</div>
			</div>
  		</div>
  		<div class="container-fluid">
  			<div class="row-fluid table-title">
  				<h4><?php echo "$title" ; ?></h4>
  			</div>
  		</div>
  		<div class="container-fluid">
  			<div class="row-fluid">
  				  <?php
  				  	retrieve_contracting_data($team_filter,$dur_filter,null,null);
  				  ?>
  				
  			</div>
  		</div>
  	</div>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
<script>    $('body').on('touchstart.dropdown', '.dropdown-menu', function (e) { 
    e.stopPropagation();
}); </script>
  </body>
</html>
