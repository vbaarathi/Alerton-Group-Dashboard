<?php 	require "db.php";
require_once('login-auth.php');
		require "function.php";
		require "filtered_function.php";
		
		
		
		
		
?>
<!DOCTYPE html>
<html>
  <head>
  <link rel="apple-touch-icon" href="/custom_icon.png"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>Alerton Project Dashboard</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" media="screen">
  </head>
  <body>


	  <div id="myCarousel" class="carousel slide">
  <!-- Carousel items -->
  <div class="carousel-inner">
    <div class="active item">
    	<div class="row-fluid table-title">
  				<h4>Projects Summary for the past 6 months</h4>
  				<p class="label label-info">Page 1 of 4</p>
  		<?php		retrieve_projects_data(
  		"dt.manager","filtered_data_six",0,15) ?>
  		
  		<div class="progress">
  <div id="progressBar" class="bar" style="width: 5.5%"></div>
  		</div>
  		
    	</div>
    </div>
    <div class="item">
    	<div class="row-fluid table-title">
  				<h4>Projects Summary for the past 6 months</h4>
  				<p class="label label-info">Page 2 of 4</p>
  				<?php		retrieve_projects_data(
  		"dt.manager","filtered_data_six",15,40) ?>

  		
  		
    	</div>

    </div>
    
        <div class="item">
    	<div class="row-fluid table-title">
  				<h4>Projects Summary for the past 12 months</h4>
  				<p class="label label-info">Page 3 of 4</p>
  				<?php		retrieve_projects_data(
  		"dt.manager","filtered_data_twelve",0,15) ?>

    	</div>

    </div>
    
        <div class="item">
    	<div class="row-fluid table-title">
  				<h4>Projects Summary for the past 12 months</h4>
  				<p class="label label-info">Page 4 of 4</p>
  				<?php		retrieve_projects_data(
  		"dt.manager","filtered_data_twelve",15,40) ?>

    	</div>

    </div>
    
<!--     <div class="item">
    	<div class="row-fluid table-title">
  				<h4>Projects Team Summary for the past 6 months</h4>
  	
    	</div>

    </div>
    
    <div class="item">
    	<div class="row-fluid table-title">
  				<h4>Projects Team Summary for the past 12 months</h4>
  			
  	
    	</div>

    </div> -->



    
    

    
  </div>
  <!-- Carousel nav 
  <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
  <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>-->
</div>

	 <script>
  	setInterval(function(){myTimer()},1000);
  	
  	function myTimer(){
  		var mydiv = document.getElementById("progressBar");
  		var curr_width = parseFloat(mydiv.style.width);
	  	document.getElementById("progressBar").style.width= (curr_width+5) + "%";
  	}
  </script>


    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
  </body>
</html>