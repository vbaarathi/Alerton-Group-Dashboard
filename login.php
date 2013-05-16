<?php 
	include('db.php');


		?>	




<!DOCTYPE html>
<html>
  <head>
  <title>Alerton Project Dashboard</title>
  
  <link rel="apple-touch-icon" href="custom_icon.png"/>
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="apple-mobile-web-app-capable" content="yes" />
    
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" media="screen">
  </head>
  <body>
  	<div class="container-fluid">
	  	<div class="row-fluid">
	  		<div class="span12 login-screen">
  	  		<!-- Button to trigger modal -->
  	  		<h2>ALERTON DASHBOARDS</h2>
<a href="#myModal" role="button" class="btn btn-success" data-toggle="modal">Log In Now</a>
	  		</div>
	  	</div>


<div class="row-fluid">

<?php 	

if( isset($_GET['err'])) {
				$err_msg = $_GET['err'];
				
				echo "<div class=\"alert alert-error\">
  <strong>Warning!</strong> $err_msg
</div>";
							}
			
			$rand1 = rand(1,5);
			$rand2 = rand(1,5);
			$ans = $rand1 + $rand2;
?>
</div>
 
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
    <h3 id="myModalLabel">Authentication Required</h3>
  </div>
  <div class="modal-body">
    <form class="form-horizontal" method="post" action="login-exec.php">
	    <div class="control-group">
		    <label class="control-label" for="inputEmail">Username</label>
		    <div class="controls">
			    <input type="text" id="login" placeholder="login" name="login">
			</div>
		</div>
			    <div class="control-group">
				    <label class="control-label" for="inputPassword">Password</label>
				    <div class="controls">
					    <input type="password" id="password" placeholder="password" name="password">
					</div>
				</div>
		<div class="control-group">
		    <label class="control-label" for="inputEmail">Human? <?php echo "$rand1 + $rand2"; ?></label>
		    <div class="controls">
			    <input type="text" id="i_ans" placeholder="Enter answer here" name="i_ans">
			    <input type="hidden" value="<?php echo $ans; ?>" id="ans" name="ans">
			</div>
		</div>
  </div>
  <div class="modal-footer">
    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
    <button class="btn btn-success">Login</button>
    </form>
  </div>
</div>
<!-- Modal End -->
</div>




    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
  </body>
</html>
